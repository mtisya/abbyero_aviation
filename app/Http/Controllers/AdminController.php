<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BlockTimeRequest;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Logbook;
use App\Models\FlightSchedule;
use App\Models\User;
use App\Models\AircraftTachHistory;


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

        $totalHours = $logs->sum('hours');
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
            'instructorStats'
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

    if (!$blockTimeRequest->isPending()) {
        return back()->with(
            'error',
            'This block-time request has already been processed.'
        );
    }

    $blockTimeRequest->update([
        'status' => BlockTimeRequest::STATUS_APPROVED,
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
            'user'
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
            'profile_image' => $path
        ]);

        return back()->with('success', 'Profile picture updated successfully.');
    }
}

