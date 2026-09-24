<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\User;
use App\Models\Logbook;
use App\Models\FlightSchedule;
use App\Models\InstructorRequest;
use App\Notifications\InstructorRequestNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class StudentController extends Controller
{
    /**
     * Show student dashboard/profile
     */
    public function requestInstructor(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $student = auth()->user()->student;

        $instructor = User::where('email', $request->email)
            ->where('role', 'instructor')
            ->first();

        if (!$instructor) {
            return back()->with('error', 'Instructor not found');
        }

        $req = InstructorRequest::create([
            'student_id' => $student->id,
            'instructor_id' => $instructor->id,
        ]);


    $instructor->notify(new InstructorRequestNotification($req));
        return back()->with('success', 'Request sent to instructor');
        }

    public function dashboard()
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            abort(403, 'Unauthorized');
        }

        $student = $user->student;

        // Create student profile if missing
        if (!$student) {
            $student = Student::create(['user_id' => $user->id]);
        }

        // Instructor requests
        $requests = InstructorRequest::where('student_id', $student->id)->get();

        // Student flight log entries
        $logs = Logbook::with([
            'aiAnalysis',
            'flight'
        ])
        ->where('student_id', $student->id)
        ->latest()
        ->get();
        $latestAI = $logs->first()?->aiAnalysis;
        $totalHours = $logs->sum('hours') ?? 0;
        $approvedHours = $logs->where('approved', true)->sum('hours') ?? 0;

        $schedules = FlightSchedule::with(['flight', 'dispatch'])
            ->where('user_id', auth()->id())
            // ->where('end_time', '>', Carbon::now())
            ->whereHas('dispatches', function ($q) {
                $q->where('status', 'Dispatched');
            })
            ->orderBy('start_time')
            ->get();

        // 🔥 Calculate total scheduled minutes
        $totalScheduledMinutes = $schedules->sum(function ($s) {
            $start = Carbon::parse($s->start_time);
            $end = Carbon::parse($s->end_time);
            return $start->diffInMinutes($end);
        });

        // 🔥 Calculate total flown minutes from logs (0.1 = 6 mins)
        $totalFlownMinutes = $logs->sum(function ($log) {
            return $log->hours * 60;
        });

        // 🔥 Remaining time in minutes
        $remainingMinutes = $totalScheduledMinutes - $totalFlownMinutes;
        if ($remainingMinutes < 0) $remainingMinutes = 0;

        // 🔥 Total cost for flown hours
        $approvedLogs = $logs->where('approved', true);

        $totalHours = $approvedLogs->sum('hours');
        $totalCost = $approvedLogs->sum('flight_cost');
                
        $notifications = $user->notifications()->latest()->get();

           $latestLogPerAircraft = Logbook::whereNotNull('aircraft')
            ->latest('id')
            ->get()
            ->groupBy('aircraft')
            ->map(fn($group) => $group->first());
        

        return view('student.dashboard', compact(
            'student',
            'logs',
            'totalHours',
            'approvedHours',
            'schedules',
            'requests',
            'totalScheduledMinutes',
            'totalFlownMinutes',
            'remainingMinutes',
            'totalCost',
            'notifications',
            'latestAI',
            'latestLogPerAircraft'
        ));
    }

    public function calendar()
    {
        $events = FlightSchedule::with('flight')
            ->where('user_id', auth()->id())
            ->get()
            ->map(function ($s) {

                $color = match($s->status){
                    'scheduled' => '#0d6efd',
                    'completed' => '#198754',
                    'cancelled' => '#dc3545',
                    'maintenance' => '#6f42c1',
                    default => '#6c757d'
                };

                return [
                    'id' => $s->id,

                    // 🔥 REQUIRED FOR TIMELINE
                    'resourceId' => $s->flight_id,

                    'title' => optional($s->flight)->registration_number ?? 'Aircraft',
                    'start' => $s->start_time,
                    'end' => $s->end_time,

                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'textColor' => '#fff',

                    'extendedProps' => [
                        'status' => $s->status,
                        'aircraft' => optional($s->flight)->registration_number,
                    ]
                ];
            });

        return response()->json($events);
    }

    public function reschedule(Request $request, $id)
    {
        $schedule = FlightSchedule::findOrFail($id);

        // 🔒 SECURITY
        if ($schedule->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $start = Carbon::parse($request->start);
        $end   = Carbon::parse($request->end);

        // 🔥 CONFLICT CHECK
        $conflict = FlightSchedule::where('flight_id', $schedule->flight_id)
            ->where('id', '!=', $schedule->id)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                ->orWhereBetween('end_time', [$start, $end])
                ->orWhere(function ($q2) use ($start, $end) {
                    $q2->where('start_time', '<=', $start)
                        ->where('end_time', '>=', $end);
                });
            })
            ->exists();

        if ($conflict) {
            return response()->json([
                'success' => false,
                'message' => 'Conflict detected'
            ]);
        }

        $schedule->update([
            'start_time' => $start,
            'end_time' => $end
        ]);

        return response()->json(['success' => true]);
    }
    public function aircraftResources()
    {
        $resources = FlightSchedule::with('flight')
            ->where('user_id', auth()->id())
            ->get()
            ->unique('flight_id')
            ->map(function ($s) {
                return [
                    'id' => $s->flight_id,
                    'title' => optional($s->flight)->registration_number ?? 'Aircraft'
                ];
            })
            ->values();

        return response()->json($resources);
    }



    public function downloadPdf(Request $request, $id)
    {
        $student = Student::with('user')->findOrFail($id);

        // Fetch approved logs
        $logs = Logbook::where('student_id', $student->id)
        ->where(function($q) {
            $q->where('approved_by', auth()->id()) // approved by this instructor
            ->orWhereNull('approved_by');       // or pending
        })
        ->when($request->from && $request->to, function($q) use ($request) {
            $q->whereBetween('flight_date', [
                Carbon::parse($request->from),
                Carbon::parse($request->to)
            ]);
        })
        ->get();

        // Fetch student's scheduled flights
        $schedules = FlightSchedule::with('flight')
            ->where('user_id', $student->user_id)
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->get();

        // Totals
        $totalHours = $logs->sum('hours');
        $approvedHours = $logs->where('approved', true)->sum('hours');

        // Doughnut chart (Approved vs Pending)
        $doughnutUrl = "https://quickchart.io/chart?c=" . urlencode(json_encode([
            "type" => "doughnut",
            "data" => [
                "labels" => ["Approved", "Pending"],
                "datasets" => [[
                    "data" => [$approvedHours, $totalHours - $approvedHours],
                    "backgroundColor" => ["#198754", "#ffc107"]
                ]]
            ]
        ]));
        $doughnutImage = file_get_contents($doughnutUrl);
        $base64Doughnut = 'data:image/png;base64,' . base64_encode($doughnutImage);

        // Bar chart (Scheduled vs Flown)
        $barDataScheduled = [];
        $barDataFlown = [];
        $barLabels = [];

        foreach ($schedules as $schedule) {
            $barLabels[] = $schedule->aircraft;
            $barDataScheduled[] = round(
                Carbon::parse($schedule->end_time)
                ->diffInMinutes(Carbon::parse($schedule->start_time)) / 60, 2
            );

            // Sum flown hours for this aircraft
            $flown = $logs->where('approved', true)->sum('hours');
            $barDataFlown[] = round($flown, 2);
        }

        $barUrl = "https://quickchart.io/chart?c=" . urlencode(json_encode([
            "type" => "bar",
            "data" => [
                "labels" => $barLabels,
                "datasets" => [
                    [
                        "label" => "Scheduled (hrs)",
                        "data" => $barDataScheduled,
                        "backgroundColor" => "#0d6efd"
                    ],
                    [
                        "label" => "Flown (hrs)",
                        "data" => $barDataFlown,
                        "backgroundColor" => "#198754"
                    ]
                ]
            ],
            "options" => [
                "responsive" => true,
                "plugins" => [
                    "legend" => ["position" => "bottom"],
                    "title" => ["display" => true, "text" => "Scheduled vs Flown Hours"]
                ],
                "scales" => ["y" => ["beginAtZero" => true, "title" => ["display" => true, "text" => "Hours"]]]
            ]
        ]));
        $barImage = file_get_contents($barUrl);
        $base64Bar = 'data:image/png;base64,' . base64_encode($barImage);
        $logoPath = public_path('assets/images/logos/abbyerologo.png');
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

        $monthlySummary = $logs->groupBy(function($log) {
        return Carbon::parse($log->flight_date)->format('F Y');
        })->map(function($monthLogs) {
            return $monthLogs->sum('hours');
        });

        return Pdf::loadView('pdf.student-profile', compact(
            'student', 'logs', 'totalHours', 'approvedHours', 'base64Doughnut', 'base64Bar', 'monthlySummary', 'logoBase64'
        ))->stream('student_profile.pdf');
    }
    public function cancel($id)
    {
        $schedule = FlightSchedule::findOrFail($id);
        $schedule->update(['status' => 'cancelled']);

        return back()->with('success', 'Schedule cancelled successfully.');
    }

    /**
     * Assign instructor to student
     */
    public function assignInstructor(Request $request)
    {
        $request->validate([
            'instructor_id' => 'required|exists:users,id',
        ]);

        $student = Student::where('user_id', Auth::id())->first();

        $student->update([
            'instructor_id' => $request->instructor_id,
        ]);

        return back()->with('success', 'Instructor assigned successfully');
    }

    /**
     * Update progress
     */
    public function updateProgress(Request $request)
    {
        $request->validate([
            'total_hours' => 'required|integer|min:0',
            'completed_lessons' => 'required|integer|min:0',
        ]);

        $student = Student::where('user_id', Auth::id())->first();

        $student->update([
            'total_hours' => $request->total_hours,
            'completed_lessons' => $request->completed_lessons,
        ]);

        return back()->with('success', 'Progress updated');
    }
}