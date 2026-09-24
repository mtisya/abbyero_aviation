<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Flight;
use App\Models\Logbook;
use App\Models\FlightSchedule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\FlightApplicationMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;


class FlightController extends Controller
{
    /**
     * Display a listing of flights
     */
    public function index()
    {
        $flights = Flight::all();
        return view('flights.index', compact('flights'));
    }

    public function apply(Request $request)
    {
        // 1. Validate the form data
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'message' => 'nullable|string|max:2000',
        ]);

        // 2. Generate a PDF from the data
        $pdf = Pdf::loadView('pdf.flight_application', compact('data'));

        // 3. Send email to emails with PDF attached
        Mail::to('abbyeroaviation@gmail.com')
            ->cc('samtish2010@gmail.com')
            ->bcc('sammymutisya415@gmail.com')
            ->send(new FlightApplicationMail($data, $pdf));

        // 4. Return JSON response for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Your application has been submitted successfully! Admin will review it and get back to you soon.'
        ]);
    }
    /**
     * Show flight schedule page
     */
    public function schedule()
    {
        $flights = Flight::all();
        return view('flights.schedule', compact('flights'));
    }

    /**
     * Show calendar view
     */
    public function calendar()
    {
        return view('flights.calendar'); // no need to pass flights, loaded via AJAX
    }

    /**
     * Fetch flights as resources (rows) for FullCalendar
     */
    public function aircraftResources()
    {
        $flights = Flight::all();

        return response()->json(
            $flights->map(fn($f) => [
                'id' => $f->id,
                'title' => $f->registration_number
            ])
        );
    }

    public function calendarEvents(
        Request $request,
        Instructor $instructor,
        Student $students
    ) {
       $schedules = FlightSchedule::with([
            'flight:id,registration_number,current_hobbs,current_tach,status',
            'user:id,name',
            'instructor:id,name',
            'dispatch'
        ])->get();


        $statusColors = [
            'scheduled' => '#3788d8',
            'completed' => '#2ecc71',
            'cancelled' => '#e74c3c',
            'maintenance' => '#8e44ad',
            'dispatched' => '#f39c12',
        ];


        $events = $schedules->map(function ($s) use ($statusColors) {

    $aircraft = $s->flight;

    $aircraftReg = $aircraft?->registration_number
        ?? 'Unknown Aircraft';

    /*
     * Normalize schedule status.
     */
    $status = strtolower(trim($s->status ?? 'scheduled'));

    /*
     * If a dispatch exists, make sure dispatched
     * status is reflected on the calendar.
     */
    if ($s->dispatch) {

        $dispatchStatus = strtolower(
            trim($s->dispatch->status ?? '')
        );

        if (in_array($dispatchStatus, [
            'dispatched',
            'in flight',
        ], true)) {
            $status = $dispatchStatus;
        }
    }

    /*
     * Determine calendar color.
     */
    $color = $statusColors[$status] ?? '#6c757d';

            $dispatchNo = $s->dispatch?->dispatch_no;

            if (!$dispatchNo) {
                $dispatchNo = 'ABY-LLC-' .
                    now()->format('Ymd') . '-' .
                    strtoupper(Str::random(5));
            }
            return [

                'id' => $s->id,

                'resourceId' => $s->flight_id,

                'title' => $aircraftReg,


                'start' => $s->start_time
                    ? $s->start_time->utc()->toIso8601String()
                    : null,


                'end' => $s->end_time
                    ? $s->end_time->utc()->toIso8601String()
                    : null,


                'backgroundColor' => $color,

                'borderColor' => $color,

                'textColor' => '#ffffff',


                'extendedProps' => [

                    /*
                    |--------------------------------------------------------------------------
                    | IDENTIFIERS
                    |--------------------------------------------------------------------------
                    */
                    'schedule_id' => $s->id,
                    'aircraft_id' => $s->flight_id,
                    'flight_id' => $s->flight_id,
                    'pilot_id' => $s->user_id,
                    'instructor_id' => $s->instructor_id,

                    /*
                    |--------------------------------------------------------------------------
                    | CREW
                    |--------------------------------------------------------------------------
                    */

                    'pilot' => $s->user?->name,

                    'instructor' => $s->instructor?->name,

                    'copilot' => $s->instructor?->name,


                    /*
                    |--------------------------------------------------------------------------
                    | FLIGHT INFORMATION
                    |--------------------------------------------------------------------------
                    */

                    'status' => $status,

                    'user' => $s->user?->name ?? 'System',

                    'departure' => $s->departure,

                    'destination' => $s->destination,

                    'remarks' => $s->remarks,


                    /*
                    |--------------------------------------------------------------------------
                    | CURRENT FLIGHT LEG READINGS
                    |--------------------------------------------------------------------------
                    */

                    'hobbs_start' => (float) ($s->hobbs_start ?? 0),

                    'hobbs_end' => (float) ($s->hobbs_end ?? 0),


                    'tach_start' => (float) ($s->tach_start ?? 0),

                    'tach_end' => (float) ($s->tach_end ?? 0),



                    /*
                    |--------------------------------------------------------------------------
                    | AIRCRAFT MASTER METERS
                    |--------------------------------------------------------------------------
                    */

                    'current_hobbs' => (float) (
                        $aircraft?->current_hobbs ?? 0
                    ),

                    'current_tach' => (float) (
                        $aircraft?->current_tach ?? 0
                    ),



                    /*
                    |--------------------------------------------------------------------------
                    | AIRCRAFT STATUS
                    |--------------------------------------------------------------------------
                    */

                    'aircraft_status' => $aircraft?->status
                        ?? 'available',


                    /*
                    |--------------------------------------------------------------------------
                    | DISPATCH
                    |--------------------------------------------------------------------------
                    */

                    'dispatch_no' => $dispatchNo ?? (
                        'ABY-LLC-' .
                        now()->format('Ymd') . '-' .
                        strtoupper(Str::random(5))
                    ),

                ]

            ];

        });


        return response()->json($events);
    }

    /**
     * Create a new flight schedule from calendar selection
     */

    public function createSchedule(Request $request)
    {
        $validated = $request->validate([
            'flight_id' => ['required', 'integer', 'exists:flights,id'],
            'instructor_id' => ['nullable', 'exists:users,id'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date', 'after:start'],
        ]);

        try {

            $start = Carbon::parse($validated['start']);
            $end = Carbon::parse($validated['end']);

            // ==================================================
            // AIRCRAFT CONFLICT CHECK
            // ==================================================
            $hasConflict = FlightSchedule::where('flight_id', $validated['flight_id'])
                ->where(function ($query) use ($start, $end) {

                    $query->whereBetween('start_time', [$start, $end])
                        ->orWhereBetween('end_time', [$start, $end])
                        ->orWhere(function ($subQuery) use ($start, $end) {

                            $subQuery->where('start_time', '<=', $start)
                                ->where('end_time', '>=', $end);
                        });
                })
                ->exists();

            if ($hasConflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'This aircraft is already scheduled during the selected time period.'
                ], 422);
            }

            // ==================================================
            // CREATE SCHEDULE
            // ==================================================
            $student = auth()->user()->studentProfile;

            $instructorId = $student?->instructor_id;


            $schedule = FlightSchedule::create([
                'flight_id' => $validated['flight_id'],
                'user_id' => auth()->id(),
                'instructor_id' => $instructorId,
                'start_time' => $start,
                'end_time' => $end,
                'status' => 'scheduled',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Flight scheduled successfully.',
                'schedule_id' => $schedule->id,
                'flight_id' => $schedule->flight_id,
                'pilot_id' => $schedule->user_id,
                'instructor_id' => $schedule->instructor_id,
            ]);

        } catch (\Exception $e) {

            \Log::error('Schedule Creation Failed', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to create schedule at this time.'
            ], 500);
        }
    }

    /**
     * Update a schedule from drag/resize on calendar
     */

    public function updateFromCalendar(Request $request, $id)
    {
        $schedule = FlightSchedule::findOrFail($id);

        if ($schedule->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to edit this schedule'
            ], 403);
        }

        // ✅ REMOVE ->utc()
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);

        $conflict = FlightSchedule::where('flight_id', $schedule->flight_id)
            ->where('id', '!=', $id)
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
                'message' => 'Time conflict detected'
            ]);
        }

        $schedule->update([
            'start_time' => $start,
            'end_time' => $end
        ]);

        return response()->json([
            'success' => true
        ]);
    }


    /**
     * Show all schedules (dashboard)
     */
    public function schedules()
    {
        $schedules = FlightSchedule::with('flight')
            ->latest()
            ->paginate(5);
        $flights = Flight::all();

        return view('flights.schedule-dashboard', compact('flights', 'schedules'));
    }

    /**
     * Edit schedule form
     */


    public function editSchedule($id)
    {
        $schedule = FlightSchedule::findOrFail($id);

        // ✅ Authorization
        if ($schedule->user_id !== Auth::id()) {

            return back()->with([
                'error' => 'You are not authorized to edit this schedule.'
            ]);
        }

        $flights = Flight::all();

        return view('flights.schedule_edit', compact('schedule', 'flights'));
    }

    /**
     * Update schedule from form
     */

    public function updateSchedule(Request $request, $id)
    {
        $schedule = FlightSchedule::findOrFail($id);

        if ($schedule->user_id !== Auth::id()) {

            return back()->with([
                'error' => 'You are not authorized to update this schedule.'
            ]);
        }

        $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time'
        ]);

        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);

        $conflict = FlightSchedule::where('flight_id', $request->flight_id)
            ->where('id', '!=', $id)
            ->where('status', 'scheduled')
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
            return back()->with('error', 'Time slot already taken.');
        }

        $schedule->update([
            'flight_id' => $request->flight_id,
            'start_time' => $start->format('Y-m-d H:i:s'),
            'end_time' => $end->format('Y-m-d H:i:s'),
            'status' => $request->status,
        ]);

        return redirect()
            ->route('flights.schedules', $schedule->flight)
            ->with('success', 'Schedule updated.');
    }

    /**
     * Cancel a schedule
     */
   public function destroySchedule($id)
    {
        $schedule = FlightSchedule::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this schedule.'
            ], 403);
        }

        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Flight schedule deleted successfully.'
        ]);
    }

    /**
     * Store a new schedule from form (not calendar)
     */
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'flight_id' => 'required|exists:flights,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        // Conflict check
        $conflict = FlightSchedule::where('flight_id', $request->flight_id)
            ->where('status', 'scheduled')
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($q2) use ($request) {
                        $q2->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                    });
            })->exists();

        if ($conflict) {
            return back()->with('error', 'This flight is already scheduled at that time.');
        }

        FlightSchedule::create([
            'flight_id' => $request->flight_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'scheduled',
            'user_id' => auth()->id()
        ]);

        return back()->with('success', 'Flight scheduled successfully.');
    }

    /**
     * CRUD for Flights
     */
    public function create()
    {
        $instructors = Instructor::where('active', true)->get();
        return view('flights.create', compact('instructors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'aircraft_model' => 'required|string|max:255',
            'registration_number' => 'required|string|max:100',

           'current_hobbs' => 'nullable|numeric|min:0',
            'current_tach' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'maintenance_status' => 'required|in:serviceable,due_soon,maintenance,grounded',
            'status' => 'required|in:Available,Maintenance,Grounded',
        ]);

       Flight::create([
            'aircraft_model'      => $request->aircraft_model,
            'registration_number' => $request->registration_number,
            'current_hobbs'       => $request->current_hobbs ?? 0,
            'current_tach'        => $request->current_tach ?? 0,
            'hourly_rate'         => $request->hourly_rate ?? 0,
            'maintenance_status'  => $request->maintenance_status ?? 'serviceable',
            'status'              => $request->status,
        ]);

        return redirect()
            ->route('flights.available')
            ->with('success', 'Aircraft created successfully.');
    }

    public function availableold()
    {
        $query = Flight::with([
            'maintenanceSchedules.maintenanceType'
        ]);


        /**
         * Non-admin users only see available aircraft
         */
        if (!auth()->check() || auth()->user()->role !== 'admin') {

            $query->where('status', 'available');

        }


        $flights = $query->get();


        foreach ($flights as $aircraft) {

            $aircraft->maintenance_due = false;
            $aircraft->maintenance_warning = false;
            $aircraft->hours_remaining = null;
            $aircraft->maintenance_type = null;


            foreach ($aircraft->maintenanceSchedules as $schedule) {


                if (is_null($schedule->next_due_tach)) {
                    continue;
                }


                $remainingHours =
                    $schedule->next_due_tach -
                    $aircraft->current_tach;



                /*
                |--------------------------------------------------------------------------
                | Find closest maintenance requirement
                |--------------------------------------------------------------------------
                */

                if (
                    is_null($aircraft->hours_remaining) ||
                    $remainingHours < $aircraft->hours_remaining
                ) {

                    $aircraft->hours_remaining = max(
                        0,
                        $remainingHours
                    );


                    $aircraft->maintenance_type =
                        $schedule->maintenanceType?->name;

                }



                /*
                |--------------------------------------------------------------------------
                | Determine maintenance urgency
                |--------------------------------------------------------------------------
                */

                if ($remainingHours <= 0) {

                    $aircraft->maintenance_due = true;

                } elseif ($remainingHours <= 10) {

                    $aircraft->maintenance_warning = true;

                }

            }



            /*
            |--------------------------------------------------------------------------
            | Final aircraft display status
            |--------------------------------------------------------------------------
            */

            $aircraft->display_status = match (true) {

                $aircraft->maintenance_due =>
                'Maintenance Due',

                $aircraft->maintenance_warning =>
                'Due Soon',

                default =>
                ucfirst($aircraft->status),

            };

        }


        return view(
            'flights.available',
            [
                'flights' => $flights
            ]
        );
    }

    public function available()
{
    $query = Flight::with([
        'maintenanceSchedules.maintenanceType'
    ]);

    // Non-admin users only see available aircraft
    if (!auth()->check() || auth()->user()->role !== 'admin') {

        $query->where('status', 'available');

    }


    $flights = $query->get();


    foreach ($flights as $aircraft) {


        /*
        |--------------------------------------------------------------------------
        | Default values
        |--------------------------------------------------------------------------
        */
        $aircraft->maintenance_due = false;
        $aircraft->maintenance_warning = false;

        $aircraft->hours_remaining = null;
        $aircraft->maintenance_type = null;
        $aircraft->next_due_tach = null;


        /*
        |--------------------------------------------------------------------------
        | Find nearest maintenance schedule
        |--------------------------------------------------------------------------
        */
        $nextSchedule = $aircraft->maintenanceSchedules
            ->filter(function ($schedule) {

                return !is_null($schedule->next_due_tach);

            })
            ->map(function ($schedule) use ($aircraft) {

                $schedule->remaining =
                    $schedule->next_due_tach -
                    $aircraft->current_tach;

                return $schedule;

            })
            ->sortBy('remaining')
            ->first();



        if ($nextSchedule) {


            /*
            |--------------------------------------------------------------------------
            | Maintenance information
            |--------------------------------------------------------------------------
            */
            $remainingHours = $nextSchedule->remaining;


            $aircraft->hours_remaining = round(
                $remainingHours,
                1
            );


            $aircraft->next_due_tach =
                $nextSchedule->next_due_tach;


            $aircraft->maintenance_type =
                $nextSchedule->maintenanceType?->name;



            /*
            |--------------------------------------------------------------------------
            | Maintenance status
            |--------------------------------------------------------------------------
            */
            if ($remainingHours <= 0) {

                $aircraft->maintenance_due = true;

            } elseif ($remainingHours <= 10) {

                $aircraft->maintenance_warning = true;

            }

        }



        /*
        |--------------------------------------------------------------------------
        | Display Status
        |--------------------------------------------------------------------------
        */
        $aircraft->display_status = match (true) {


            $aircraft->maintenance_due =>
                'Maintenance Due',


            $aircraft->maintenance_warning =>
                'Due Soon',


            default =>
                'Available',

        };

    }


    return view('flights.available', [
        'flights' => $flights
    ]);
    }

    public function show($id)
    {
        $flight = Flight::findOrFail($id);

        // 🔥 get user's schedule for this flight
        $schedule = FlightSchedule::where('flight_id', $id)
            ->where('user_id', auth()->id())
            ->latest()
            ->first();

        return view('flights.show', compact('flight', 'schedule'));
    }

    public function edit($id)
    {
        $flight = Flight::findOrFail($id);
        return view('flights.edit', compact('flight'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'aircraft_model' => 'required|string|max:255',
            'registration_number' => 'required|string|max:100',

            'current_tach' => 'nullable|numeric|min:0',
            'current_hobbs' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',

            'status' => 'required|in:available,maintenance,grounded',
        ]);

        $aircraft = Flight::findOrFail($id);

        $aircraft->update([
            'aircraft_model' => $request->aircraft_model,
            'registration_number' => $request->registration_number,
            'current_tach' => $request->current_tach ?? $aircraft->current_tach,
            'current_hobbs' => $request->current_hobbs ?? $aircraft->current_hobbs,
            'hourly_rate' => $request->hourly_rate ?? $aircraft->hourly_rate,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('flights.available')
            ->with('success', 'Aircraft updated successfully ✈');
    }
    
    public function destroy($id)
    {
        $aircraft = Flight::findOrFail($id);

        if (
            $aircraft->schedules()->exists() ||
            $aircraft->dispatches()->exists()
        ) {
            return back()->with(
                'error',
                'Aircraft cannot be deleted because it has scheduling or dispatch history.'
            );
        }

        $aircraft->delete();

        return back()->with(
            'success',
            'Aircraft deleted successfully.'
        );
    }

    public function summary($id)
    {
        $aircraft = Flight::with([
            'maintenanceSchedules.maintenanceType'
        ])->findOrFail($id);

        $currentTach = $aircraft->current_tach ?? 0;

        $reminders = $aircraft->maintenanceSchedules
            ->map(function ($schedule) use ($aircraft, $currentTach) {

                $remainingHours = $schedule->next_due_tach
                    ? $schedule->next_due_tach - $currentTach
                    : 0;

                $intervalHours = $schedule->maintenanceType?->default_interval_hours;

                return [

                    'tail_no' => $aircraft->registration_number,

                    'aircraft' => $aircraft->aircraft_model,

                    'reminder' => $schedule->maintenanceType?->name ?? 'Maintenance',

                    'interval' => $intervalHours,

                    'remaining' => round(max(0, $remainingHours), 1),

                    'due' => $schedule->next_due_tach
                        ? round($schedule->next_due_tach, 1)
                        : null,

                    'hours' => round($currentTach, 1),

                    'days' => $schedule->next_due_date
                        ? now()->diffInDays($schedule->next_due_date, false)
                        : null,

                    'status' => match (true) {

                        $remainingHours <= 0 => 'Overdue',

                        $remainingHours <= 10 => 'Due Soon',

                        default => 'Scheduled',

                    },

                ];
            })
            ->values();

        return response()->json([

            'aircraft_model' => $aircraft->aircraft_model,

            'registration_number' => $aircraft->registration_number,

            'current_tach' => round($currentTach, 1),

            'reminders' => $reminders

        ]);
    }
}
