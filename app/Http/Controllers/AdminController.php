<?php
namespace App\Http\Controllers;

use App\Models\AircraftTachHistory;
use App\Models\BlockTimeRequest;
use App\Models\Flight;
use App\Models\FlightSchedule;
use App\Models\Logbook;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 🔥 Admin check (optional since middleware already does it)
        if ($user->role !== 'admin') {
            abort(403);
        }

        $logs = Logbook::with('flight')->get();

        $totalHours    = $logs->sum('hours');
        $approvedHours = $logs->where('approved', true)->sum('hours');

        $schedules = FlightSchedule::with('flight')
            ->orderBy('start_time', 'asc')
            ->get();

        $totalScheduledMinutes = $schedules->sum(function ($s) {
            return Carbon::parse($s->start_time)
                ->diffInMinutes(Carbon::parse($s->end_time));
        });

        $totalFlownMinutes = $logs->sum(fn($log) => $log->hours * 60);

        $remainingMinutes = max(0, $totalScheduledMinutes - $totalFlownMinutes);

        $totalRevenue = $logs->sum(function ($log) {
            return $log->flight_cost;
        });

        $aircraftHours = Logbook::select('aircraft')
            ->selectRaw('SUM(tach_hours) as total_hours')
            ->groupBy('aircraft')
            ->get();

        // Aircraft availability based on actual maintenance schedules.
        $aircraftAvailability = Flight::with([
            'maintenanceSchedules.maintenanceType',
        ])->get()->map(function ($aircraft) {

            $currentTach = (float) ($aircraft->current_tach ?? 0);

            $maintenanceItems = $aircraft->maintenanceSchedules
                ->map(function ($schedule) use ($currentTach) {

                    $type = $schedule->maintenanceType;

                    $hasTachDue = $schedule->next_due_tach !== null;
                    $hasDateDue = $schedule->next_due_date !== null;

                    $remainingHours = $hasTachDue
                        ? (float) $schedule->next_due_tach - $currentTach
                        : null;

                    $remainingDays = $hasDateDue
                        ? Carbon::today()->diffInDays(
                        Carbon::parse($schedule->next_due_date)->startOfDay(),
                        false
                    )
                        : null;

                    $intervalHours = $schedule->interval_hours ?? $type?->default_interval_hours;

                    $intervalDays = $schedule->interval_days ?? $type?->default_interval_days;

                    // Match MaintenanceService warning thresholds.
                    $warningHours = $type?->default_interval_hours
                        ? max(5, round($type->default_interval_hours * 0.20))
                        : 10;

                    $warningDays = $type?->default_interval_days
                        ? max(7, round($type->default_interval_days * 0.10))
                        : 30;

                    $isOverdue =
                        ($remainingHours !== null && $remainingHours <= 0) ||
                        ($remainingDays !== null && $remainingDays <= 0);

                    $isDueSoon = ! $isOverdue && (
                        ($remainingHours !== null && $remainingHours <= $warningHours) ||
                        ($remainingDays !== null && $remainingDays <= $warningDays)
                    );

                    $status = $isOverdue
                        ? 'overdue'
                        : ($isDueSoon ? 'due_soon' : 'serviceable');

                    // Calculate progress using whichever interval is further advanced.
                    $progressValues = [];

                    if ($intervalHours && $intervalHours > 0 && $remainingHours !== null) {
                        $progressValues[] = (
                            ($intervalHours - $remainingHours) / $intervalHours
                        ) * 100;
                    }

                    if ($intervalDays && $intervalDays > 0 && $remainingDays !== null) {
                        $progressValues[] = (
                            ($intervalDays - $remainingDays) / $intervalDays
                        ) * 100;
                    }

                    $progress = count($progressValues)
                        ? round(max(0, min(100, max($progressValues))))
                        : null;

                    return [
                        'reminder'        => $type?->name ?? $schedule->description ?? 'Maintenance',
                        'remaining_hours' => $remainingHours !== null
                            ? round(max(0, $remainingHours), 1)
                            : null,
                        'remaining_days'  => $remainingDays,
                        'due_tach'        => $schedule->next_due_tach,
                        'due_date'        => $schedule->next_due_date,
                        'status'          => $status,
                        'progress'        => $progress,

                        // Used to order the most urgent item first.
                        'priority'        => match ($status) {
                            'overdue'  => 1,
                            'due_soon' => 2,
                            default    => 3,
                        },
                    ];
                })
                ->sortBy(function ($item) {
                    return ($item['priority'] * 1000)
                         - ($item['progress'] ?? 0);
                })
                ->values();

            return [
                'registration' => $aircraft->registration_number,
                'model'        => $aircraft->aircraft_model,
                'current_tach' => $currentTach,
                'maintenance'  => $maintenanceItems,
            ];
        });

        $flightStats = FlightSchedule::select('status')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $monthlyHours = Logbook::selectRaw('MONTH(flight_date) as month, SUM(hours) as total_hours')
            ->groupBy('month')
            ->get();

        $studentsCount = User::where('role', 'student')->count();

        $instructorStats = \DB::table('logbooks')
            ->join('users', 'logbooks.approved_by', '=', 'users.id')
            ->select('users.name', \DB::raw('COUNT(logbooks.id) as approvals'))
            ->groupBy('users.name')
            ->get();

        return view('admin.dashboard', compact(
            'totalHours',
            'approvedHours',
            'schedules',
            'totalScheduledMinutes',
            'totalFlownMinutes',
            'remainingMinutes',
            'totalRevenue',
            'aircraftHours',
            'flightStats',
            'monthlyHours',
            'studentsCount',
            'instructorStats',
            'aircraftAvailability'
        ));
    }

    public function blockTimeRequests()
    {
        $user = Auth::user();

        abort_unless(
            $user && $user->role === 'admin',
            403
        );

        $blockTimeRequests = BlockTimeRequest::with([
            'requester',
            'flight',
            'schedule',
            'approver',
        ])
            ->latest()
            ->paginate(15);

        return view(
            'admin.block-time-requests.index',
            compact('blockTimeRequests')
        );
    }
    public function approveBlockTimeRequest(
        BlockTimeRequest $blockTimeRequest
    ) {
        $user = Auth::user();

        abort_unless(
            $user && $user->role === 'admin',
            403
        );

        if (! $blockTimeRequest->isPending()) {
            return back()->with(
                'error',
                'This block-time request has already been processed.'
            );
        }

        $blockTimeRequest->update([
            'status'      => BlockTimeRequest::STATUS_APPROVED,
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        return back()->with(
            'success',
            'Additional block time approved successfully.'
        );
    }

    public function tachHistory()
    {
        $histories = AircraftTachHistory::with([
            'aircraft',
            'logbook.student',
            'user',
        ])
            ->latest()
            ->paginate(5);

        return view('admin.tach-history', compact('histories'));
    }

    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|max:2048',
        ]);

        $user = auth()->user();

        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        $path = $request->file('profile_image')
            ->store('profile-images', 'public');

        $user->update([
            'profile_image' => $path,
        ]);

        return back()->with('success', 'Profile picture updated successfully.');
    }
}
