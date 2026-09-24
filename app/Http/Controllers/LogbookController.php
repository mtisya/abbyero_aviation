<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Logbook;
use App\Notifications\LogbookApproved;
use App\Notifications\LogbookCreatedNotification;
use App\Services\AI\AIService;
use App\Models\AiLogbookAnalysis;
use App\Models\User;
use App\Models\Student;
use App\Notifications\AircraftHoursReached;
use App\Models\FlightSchedule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
use App\Services\MaintenanceService;
use App\Models\Dispatch;
use App\Models\BlockTimeRequest;
use App\Models\UserBlockTime;
use Illuminate\Support\Facades\Log;
use App\Models\Invoice;
use App\Models\InvoiceItem;

class LogbookController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return back()->with(
                'error',
                'User not authenticated.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | USER BLOCK TIME
        |--------------------------------------------------------------------------
        |
        | One block-time account belongs to each user.
        |
        */

        $logbookUserId = $user->id;

        $userBlockTime = UserBlockTime::firstOrCreate(
            [
                'user_id' => $logbookUserId,
            ],
            [
                'initial_block_time' => 0,
            ]
        );

        $mode = $request->get('mode', 'logbook');

        $query = Logbook::with([
            'user',
            'student.user',
            'instructor',
            'aiAnalysis',
            'flight',
            'schedule'
        ]);

        /*
        |--------------------------------------------------------------------------
        | ROLE FILTER
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'student') {

            $query->where(
                'student_id',
                $user->student?->id
            );

        } elseif ($user->role === 'instructor') {

            $query->where(function ($q) use ($user) {

                $q->where(
                    'instructor_id',
                    $user->id
                )
                ->orWhere(
                    'user_id',
                    $user->id
                );

            });
        }

        /*
        |--------------------------------------------------------------------------
        | SELECTED SCHEDULE
        |--------------------------------------------------------------------------
        */

        $selectedSchedule = null;

        if ($request->filled('schedule_id')) {

            $selectedSchedule = FlightSchedule::with([
                'flight'
            ])->find($request->schedule_id);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTERS
        |--------------------------------------------------------------------------
        */

        if ($request->filled('aircraft')) {

            $query->where(
                'aircraft',
                $request->aircraft
            );
        }

        if ($request->filled('from')) {

            $query->whereDate(
                'flight_date',
                '>=',
                $request->from
            );
        }

        if ($request->filled('to')) {

            $query->whereDate(
                'flight_date',
                '<=',
                $request->to
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $logbooks = $query
            ->latest()
            ->paginate(5);

        $logbooks->appends(
            $request->query()
        );

        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $allLogsForStats = $query->get();

        $totalScheduledMinutes = $allLogsForStats->sum(
            fn ($log) =>
                $log->block_time
                    ? $log->block_time * 60
                    : $log->scheduled_minutes
        );

        $totalFlownMinutes = $allLogsForStats->sum(
            fn ($log) =>
                $log->hours * 60
        );

        $remainingMinutes = max(
            0,
            $totalScheduledMinutes - $totalFlownMinutes
        );

        /*
        |--------------------------------------------------------------------------
        | AIRCRAFT LIST
        |--------------------------------------------------------------------------
        */

        $aircraftList = Logbook::select(
            'aircraft'
        )
            ->whereNotNull('aircraft')
            ->distinct()
            ->orderBy('aircraft')
            ->pluck('aircraft');

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'logbooks.index',
            compact(
                'logbooks',
                'totalScheduledMinutes',
                'totalFlownMinutes',
                'remainingMinutes',
                'aircraftList',
                'selectedSchedule',
                'mode',
                'userBlockTime'
            )
        );
    }


public function requestBlockTime(
    Request $request,
    FlightSchedule $schedule
    ) {
    $user = Auth::user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not authenticated.',
        ], 401);
    }

    /*
    |--------------------------------------------------------------------------
    | SCHEDULE OWNERSHIP
    |--------------------------------------------------------------------------
    */

    if ((int) $schedule->user_id !== (int) $user->id) {

        return response()->json([
            'success' => false,
            'message' =>
                'You can only request block time for your own schedule.',
        ], 403);
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $validated = $request->validate([
        'hours' => [
            'required',
            'numeric',
            'min:0.1',
        ],

        'reason' => [
            'nullable',
            'string',
            'max:1000',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | CREATE REQUEST
    |--------------------------------------------------------------------------
    */

    BlockTimeRequest::create([

        'flight_id' =>
            $schedule->flight_id,

        'schedule_id' =>
            $schedule->id,

        'requested_by' =>
            $user->id,

        'hours' =>
            $validated['hours'],

        'status' =>
            BlockTimeRequest::STATUS_PENDING,

        'reason' =>
            $validated['reason'] ?? null,
    ]);

    return response()->json([
        'success' => true,
        'message' =>
            'Additional block time submitted for admin approval.',
    ]);
}


public function approveBlockTimeRequest(
    BlockTimeRequest $blockTimeRequest
) {
    $user = Auth::user();

    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY
    |--------------------------------------------------------------------------
    */

    abort_unless(
        $user && $user->role === 'admin',
        403
    );

    /*
    |--------------------------------------------------------------------------
    | PREVENT DOUBLE PROCESSING
    |--------------------------------------------------------------------------
    */

    if (!$blockTimeRequest->isPending()) {

        return back()->with(
            'error',
            'This request has already been processed.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVE
    |--------------------------------------------------------------------------
    */

    $blockTimeRequest->update([

        'status' =>
            BlockTimeRequest::STATUS_APPROVED,

        'approved_by' =>
            $user->id,

        'approved_at' =>
            now(),
    ]);

    return back()->with(
        'success',
        'Additional block time approved successfully.'
    );
}


    public function edit(Logbook $logbook)
    {
        return response()->json([

            'id' => $logbook->id,

            'flight_date' => $logbook->flight_date,

            'route' => $logbook->route,

            'hobbs_start' => $logbook->hobbs_start,

            'hobbs_end' => $logbook->hobbs_end,

            'tach_start' => $logbook->tach_start,

            'tach_end' => $logbook->tach_end,

            'remarks' => $logbook->remarks,

            'approved' => $logbook->approved,

            'user_role' => auth()->user()->role,

        ]);
    }
    public function update(Request $request, Logbook $logbook)
    {
        $request->validate([
            'route' => 'required',
            'flight_date' => 'required|date',
            'hobbs_start' => 'required',
            'hobbs_end' => 'required',
            'tach_start' => 'required',
            'tach_end' => 'required',
        ]);

        $logbook->update([
            'route' => $request->route,
            'flight_date' => $request->flight_date,
            'hobbs_start' => $request->hobbs_start,
            'hobbs_end' => $request->hobbs_end,
            'tach_start' => $request->tach_start,
            'tach_end' => $request->tach_end,
            'remarks' => $request->remarks,

            // 👇 ADD THESE
            'override_edit' => $request->override_edit ?? false,
            'override_by' => $request->override_edit ? auth()->id() : null,
            'override_reason' => $request->override_reason,
        ]);

        return response()->json([
            'message' => 'Logbook updated successfully'
        ]);
    }

    public function show($id)
    {
        $log = Logbook::with(['aiAnalysis', 'student.user'])->findOrFail($id);

        return view('logbooks.show', compact('log'));
    }

    public function exportPdf()
    {
        $logs = Logbook::with('aiAnalysis')->get();

        $pdf = PDF::loadView('logbooks.pdf', compact('logs'));

        return $pdf->download('logbooks.pdf');
    }


    public function storeworking(Request $request, AIService $aiService)
    {
        $request->validate([
            'schedule_id' => 'required|exists:flight_schedules,id',
            'flight_id' => 'required|exists:flights,id',
            'flight_date' => 'required|date',
            'route' => 'nullable|string|max:255',

            'hobbs_start' => 'required|numeric|min:0',
            'hobbs_end' => 'required|numeric|gt:hobbs_start',

            'tach_start' => 'nullable|numeric|min:0',
            'tach_end' => 'nullable|numeric|gte:tach_start',

            'block_time' => 'nullable|numeric|min:0.1',

            'fuel_added' => 'nullable|numeric|min:0',
            'oil_added' => 'nullable|numeric|min:0',

            'type' => 'required|in:dual,solo',
            'remarks' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        if (!$user) {
            return back()->with('error', 'User not authenticated');
        }

        /*
        |----------------------------------------------------
        | LOAD SCHEDULE
        |----------------------------------------------------
        */
        $schedule = FlightSchedule::findOrFail($request->schedule_id);
        // STRICT OWNERSHIP CHECK
        if ((int) $schedule->user_id !== (int) $user->id) {
            abort(403, 'You can only create logbooks for your own schedules.');
        }

        /*
        |----------------------------------------------------
        | DERIVE STUDENT + INSTRUCTOR (NO EXTRA QUERIES)
        |----------------------------------------------------
        */
        $studentId = null;
        $instructorId = null;

        $student = Student::where('user_id', $schedule->user_id)->first();

        if ($student) {
            // Schedule belongs to a student
            $studentId = $student->id;
            $instructorId = $student->instructor_id;
        } else {
            // Schedule belongs to an admin or instructor
            if ($user->role === 'instructor') {
                $instructorId = $user->id;
            }
        }

        /*
        |----------------------------------------------------
        | CALCULATIONS
        |----------------------------------------------------
        */
        $hobbsHours = round($request->hobbs_end - $request->hobbs_start, 2);

        $tachHours = ($request->filled('tach_start') && $request->filled('tach_end'))
            ? round($request->tach_end - $request->tach_start, 2)
            : 0;


        /*
        |--------------------------------------------------------------------------
        | PREVENT DUPLICATE LOGBOOK FOR SAME SCHEDULE
        |--------------------------------------------------------------------------
        */

        $existingLogbook = Logbook::where(
            'schedule_id',
            $schedule->id
        )->first();

        if ($existingLogbook) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'A logbook has already been created. Needs to be approved or rejected before creating a new one.'
                );
        }

        /*
        |----------------------------------------------------
        | SAVE LOGBOOK
        |----------------------------------------------------
        */
        try {
            $log = Logbook::create([
                'user_id' => $user->id,

                'student_id' => $studentId,
                'instructor_id' => $instructorId,

                'schedule_id' => $schedule->id,
                'flight_id' => $schedule->flight_id,
                'flight_date' => $request->flight_date,
                'aircraft' => $schedule->flight?->registration_number,
                'route' => $request->route,

                'hobbs_start' => $request->hobbs_start,
                'hobbs_end' => $request->hobbs_end,

                'tach_start' => $request->tach_start,
                'tach_end' => $request->tach_end,

                'hours' => $hobbsHours,
                'tach_hours' => $tachHours,

                'block_time' => $request->block_time,
                'fuel_added' => $request->fuel_added,
                'oil_added' => $request->oil_added,

                'type' => $request->type,
                'approved' => false,
            ]);


            if ($instructorId) {

                $instructor = User::find($instructorId);

                if ($instructor) {
                    $instructor->notify(new LogbookCreatedNotification($log));
                }
            }

        } catch (\Throwable $e) {

            \Log::error('Logbook save failed', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return back()->with('error', 'Failed to save logbook entry.');
        }

        /*
        |----------------------------------------------------
        | AIRCRAFT TOTALS
        |----------------------------------------------------
        */
        $totalHours = Logbook::where('aircraft', $log->aircraft)->sum('hours');
        $totalTach = Logbook::where('aircraft', $log->aircraft)->sum('tach_hours');

        $admins = User::where('role', 'admin')->get();

        $notifyAdmins = function ($level, $value) use ($admins, $log) {
            foreach ($admins as $admin) {
                $admin->notify(
                    new AircraftHoursReached($log->aircraft, $value, $level)
                );
            }
        };

        if ($totalHours >= 80 && $totalHours < 95) {
            $notifyAdmins('warning', $totalHours);
        }

        if ($totalHours >= 95) {
            $notifyAdmins('danger', $totalHours);
        }

        /*
        |----------------------------------------------------
        | MAINTENANCE ALERTS
        |----------------------------------------------------
        */
        if ($totalTach >= 45 && $totalTach < 50) {
            $notifyAdmins('50hr inspection due soon', $totalTach);
        }

        if ($totalTach >= 95 && $totalTach < 100) {
            $notifyAdmins('100hr inspection due soon', $totalTach);
        }

        /*
        |----------------------------------------------------
        | AI ANALYSIS
        |----------------------------------------------------
        */
        try {
            $aiResponse = $aiService->analyzeLogbook($log);

            $content = $aiResponse['choices'][0]['message']['content'] ?? null;

            $data = $content ? json_decode($content, true) : null;

            AiLogbookAnalysis::create([
                'logbook_id' => $log->id,
                'summary' => $data['summary'] ?? 'Flight analyzed successfully.',
                'feedback' => $data['feedback'] ?? 'Continue improving aircraft handling.',
                'flags' => json_encode($data['flags'] ?? ['No major issues']),
            ]);

        } catch (\Throwable $e) {

            \Log::error('AI failed', ['error' => $e->getMessage()]);

            AiLogbookAnalysis::create([
                'logbook_id' => $log->id,
                'summary' => 'Fallback summary',
                'feedback' => 'AI unavailable',
                'flags' => json_encode(['System error']),
            ]);
        }

        return redirect()
            ->route('logbooks.index')
            ->with(
                'success',
                'Logbook entry created successfully.'
            );
    }

public function store(
    Request $request,
    AIService $aiService
) {
    $user = Auth::user();

    if (!$user) {
        return back()->with(
            'error',
            'User not authenticated.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $request->validate([
        'schedule_id' => 'required|exists:flight_schedules,id',

        'flight_id' => 'required|exists:flights,id',

        'flight_date' => 'required|date',

        'route' => 'nullable|string|max:255',

        'hobbs_start' => 'required|numeric|min:0',

        'hobbs_end' => 'required|numeric|gt:hobbs_start',

        'tach_start' => 'nullable|numeric|min:0',

        'tach_end' => 'nullable|numeric|gte:tach_start',

        'block_time' => 'nullable|numeric|min:0.1',

        'fuel_added' => 'nullable|numeric|min:0',

        'oil_added' => 'nullable|numeric|min:0',

        'type' => 'required|in:dual,solo',

        'remarks' => 'nullable|string|max:255',
    ]);

    /*
    |--------------------------------------------------------------------------
    | LOAD SCHEDULE
    |--------------------------------------------------------------------------
    */

    $schedule = FlightSchedule::with('flight')
        ->findOrFail($request->schedule_id);

    /*
    |--------------------------------------------------------------------------
    | STRICT OWNERSHIP CHECK
    |--------------------------------------------------------------------------
    */

   if (
        $user->role !== 'admin'
        && (int) $schedule->user_id !== (int) $user->id
    ) {
        abort(
            403,
            'You can only create logbooks for your own schedules.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | USER BLOCK TIME
    |--------------------------------------------------------------------------
    */

    $userBlockTime = UserBlockTime::firstOrCreate(
        [
            'user_id' => $user->id,
        ],
        [
            'initial_block_time' => 0,
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | FIRST BLOCK-TIME ALLOCATION
    |--------------------------------------------------------------------------
    |
    | The block_time entered on the first logbook establishes
    | the user's initial block-time allocation.
    |
    */

    /*
|--------------------------------------------------------------------------
| USER BLOCK TIME
|--------------------------------------------------------------------------
*/

$userBlockTime = UserBlockTime::firstOrCreate(
    [
        'user_id' => $user->id,
    ],
    [
        'initial_block_time' => 0,
    ]
);

/*
|--------------------------------------------------------------------------
| INITIAL BLOCK TIME
|--------------------------------------------------------------------------
*/

$isInitialBlockTime = (
    (float) $userBlockTime->initial_block_time <= 0
    && $request->filled('block_time')
);

if ($isInitialBlockTime) {

    $initialBlockTime = round(
        (float) $request->block_time,
        1
    );

    $userBlockTime->update([
        'initial_block_time' => $initialBlockTime,
    ]);

} else {

    $initialBlockTime = null;
}

    /*
    |--------------------------------------------------------------------------
    | DERIVE STUDENT + INSTRUCTOR
    |--------------------------------------------------------------------------
    */

    $studentId = null;
    $instructorId = null;

    $student = Student::where(
        'user_id',
        $schedule->user_id
    )->first();

    if ($student) {

        $studentId = $student->id;

        $instructorId = $student->instructor_id;

    } else {

        if ($user->role === 'instructor') {

            $instructorId = $user->id;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CALCULATIONS
    |--------------------------------------------------------------------------
    */

    $hobbsHours = round(
        $request->hobbs_end
            - $request->hobbs_start,
        2
    );

    $tachHours = (
        $request->filled('tach_start')
        && $request->filled('tach_end')
    )
        ? round(
            $request->tach_end
                - $request->tach_start,
            2
        )
        : 0;

    /*
    |--------------------------------------------------------------------------
    | PREVENT DUPLICATE LOGBOOK FOR SAME SCHEDULE
    |--------------------------------------------------------------------------
    */

    $existingLogbook = Logbook::where(
        'schedule_id',
        $schedule->id
    )->first();

    if ($existingLogbook) {

        return back()
            ->withInput()
            ->with(
                'error',
                'A logbook has already been created. Needs to be approved or rejected before creating a new one.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK AVAILABLE BLOCK TIME
    |--------------------------------------------------------------------------
    |
    | Do not allow the user to create a flight exceeding their
    | currently available block time.
    |
    */

    $availableBlockTime = (float)
        $userBlockTime->remaining_block_time;

    /*
    |--------------------------------------------------------------------------
    | CURRENT LOGBOOK HOURS
    |--------------------------------------------------------------------------
    */

    $flightHours = $hobbsHours;

    /*
    |--------------------------------------------------------------------------
    | BLOCK-TIME VALIDATION
    |--------------------------------------------------------------------------
    |
    | If the user already has an allocation, the new flight cannot
    | exceed the user's remaining block time.
    |
    */

    if (
        $availableBlockTime > 0
        && $flightHours > $availableBlockTime
    ) {

        return back()
            ->withInput()
            ->with(
                'error',
                'This flight exceeds your remaining block time of '
                . number_format(
                    $availableBlockTime,
                    1
                )
                . ' hours.'
            );
    }
    $logbookUserId = $schedule->user_id;

    /*
    |--------------------------------------------------------------------------
    | SAVE LOGBOOK
    |--------------------------------------------------------------------------
    */

    try {

        $log = Logbook::create([

            'user_id' => $logbookUserId,

            'student_id' => $studentId,

            'instructor_id' => $instructorId,

            'schedule_id' => $schedule->id,

            'flight_id' => $schedule->flight_id,

            'flight_date' => $request->flight_date,

            'aircraft' => $schedule->flight?->registration_number,

            'route' => $request->route,

            'hobbs_start' => $request->hobbs_start,

            'hobbs_end' => $request->hobbs_end,

            'tach_start' => $request->tach_start,

            'tach_end' => $request->tach_end,

            'hours' => $hobbsHours,

            'tach_hours' => $tachHours,
            
            'block_time' => $initialBlockTime,

            'fuel_added' => $request->fuel_added,

            'oil_added' => $request->oil_added,

            'type' => $request->type,

            'approved' => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | NOTIFY INSTRUCTOR
        |--------------------------------------------------------------------------
        */

        if ($instructorId) {

            $instructor = User::find(
                $instructorId
            );

            if ($instructor) {

                $instructor->notify(
                    new LogbookCreatedNotification($log)
                );
            }
        }

    } catch (\Throwable $e) {

        Log::error(
            'Logbook save failed',
            [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
            ]
        );

        return back()->with(
            'error',
            'Failed to save logbook entry.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AIRCRAFT TOTALS
    |--------------------------------------------------------------------------
    */

    $totalHours = Logbook::where(
        'aircraft',
        $log->aircraft
    )->sum('hours');

    $totalTach = Logbook::where(
        'aircraft',
        $log->aircraft
    )->sum('tach_hours');

    $admins = User::where(
        'role',
        'admin'
    )->get();

    $notifyAdmins = function (
        $level,
        $value
    ) use (
        $admins,
        $log
    ) {

        foreach ($admins as $admin) {

            $admin->notify(
                new AircraftHoursReached(
                    $log->aircraft,
                    $value,
                    $level
                )
            );
        }
    };

    if (
        $totalHours >= 80
        && $totalHours < 95
    ) {

        $notifyAdmins(
            'warning',
            $totalHours
        );
    }

    if ($totalHours >= 95) {

        $notifyAdmins(
            'danger',
            $totalHours
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MAINTENANCE ALERTS
    |--------------------------------------------------------------------------
    */

    if (
        $totalTach >= 45
        && $totalTach < 50
    ) {

        $notifyAdmins(
            '50hr inspection due soon',
            $totalTach
        );
    }

    if (
        $totalTach >= 95
        && $totalTach < 100
    ) {

        $notifyAdmins(
            '100hr inspection due soon',
            $totalTach
        );
    }

    /*
    |--------------------------------------------------------------------------
    | AI ANALYSIS
    |--------------------------------------------------------------------------
    */

    try {

        $aiResponse =
            $aiService->analyzeLogbook($log);

        $content =
            $aiResponse['choices'][0]['message']['content']
            ?? null;

        $data = $content
            ? json_decode(
                $content,
                true
            )
            : null;

        AiLogbookAnalysis::create([

            'logbook_id' => $log->id,

            'summary' =>
                $data['summary']
                ?? 'Flight analyzed successfully.',

            'feedback' =>
                $data['feedback']
                ?? 'Continue improving aircraft handling.',

            'flags' =>
                json_encode(
                    $data['flags']
                    ?? ['No major issues']
                ),
        ]);

    } catch (\Throwable $e) {

        Log::error(
            'AI failed',
            [
                'error' => $e->getMessage()
            ]
        );

        AiLogbookAnalysis::create([

            'logbook_id' => $log->id,

            'summary' =>
                'Fallback summary',

            'feedback' =>
                'AI unavailable',

            'flags' =>
                json_encode(
                    ['System error']
                ),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RETURN
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('logbooks.index')
        ->with(
            'success',
            'Logbook entry created successfully.'
        );
}

public function storeold(
    Request $request,
    AIService $aiService
) {
    $user = Auth::user();

    if (!$user) {
        return back()->with(
            'error',
            'User not authenticated.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $request->validate([
        'schedule_id' => 'required|exists:flight_schedules,id',

        'flight_id' => 'required|exists:flights,id',

        'flight_date' => 'required|date',

        'route' => 'nullable|string|max:255',

        'hobbs_start' => 'required|numeric|min:0',

        'hobbs_end' => 'required|numeric|gt:hobbs_start',

        'tach_start' => 'nullable|numeric|min:0',

        'tach_end' => 'nullable|numeric|gte:tach_start',

        'block_time' => 'nullable|numeric|min:0.1',

        'fuel_added' => 'nullable|numeric|min:0',

        'oil_added' => 'nullable|numeric|min:0',

        'type' => 'required|in:dual,solo',

        'remarks' => 'nullable|string|max:255',
    ]);

    /*
    |--------------------------------------------------------------------------
    | LOAD SCHEDULE
    |--------------------------------------------------------------------------
    */

    $schedule = FlightSchedule::with([
        'flight',
        'user',
    ])->findOrFail($request->schedule_id);

    /*
    |--------------------------------------------------------------------------
    | OWNERSHIP CHECK
    |--------------------------------------------------------------------------
    |
    | Admin can create for anyone.
    | Other users can only create for their own schedule.
    |--------------------------------------------------------------------------
    */

    if (
        $user->role !== 'admin'
        && (int) $schedule->user_id !== (int) $user->id
    ) {
        abort(
            403,
            'You can only create logbooks for your own schedules.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOGBOOK OWNER
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | The logbook belongs to the person who owns the schedule,
    | NOT necessarily the person currently logged in.
    |--------------------------------------------------------------------------
    */

    $logbookUserId = $schedule->user_id;

    /*
    |--------------------------------------------------------------------------
    | USER BLOCK TIME
    |--------------------------------------------------------------------------
    */

    $userBlockTime = UserBlockTime::firstOrCreate(
        [
            'user_id' => $logbookUserId,
        ],
        [
            'initial_block_time' => 0,
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | INITIAL BLOCK TIME
    |--------------------------------------------------------------------------
    */

    $isInitialBlockTime = (
        (float) $userBlockTime->initial_block_time <= 0
        && $request->filled('block_time')
    );

    if ($isInitialBlockTime) {

        $initialBlockTime = round(
            (float) $request->block_time,
            1
        );

        $userBlockTime->update([
            'initial_block_time' => $initialBlockTime,
        ]);

    } else {

        $initialBlockTime = null;
    }

    /*
    |--------------------------------------------------------------------------
    | DERIVE STUDENT + INSTRUCTOR
    |--------------------------------------------------------------------------
    */

    $studentId = null;
    $instructorId = null;

    $student = Student::where(
        'user_id',
        $schedule->user_id
    )->first();

    if ($student) {

        $studentId = $student->id;

        $instructorId = $student->instructor_id;

    } else {

        /*
         * If the schedule belongs directly to an instructor,
         * use that instructor.
         */

        $scheduleOwner = User::find($schedule->user_id);

        if ($scheduleOwner?->role === 'instructor') {

            $instructorId = $scheduleOwner->id;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CALCULATIONS
    |--------------------------------------------------------------------------
    */

    $hobbsHours = round(
        $request->hobbs_end
        - $request->hobbs_start,
        2
    );

    $tachHours = (
        $request->filled('tach_start')
        && $request->filled('tach_end')
    )
        ? round(
            $request->tach_end
            - $request->tach_start,
            2
        )
        : 0;

    /*
    |--------------------------------------------------------------------------
    | PREVENT DUPLICATE LOGBOOK
    |--------------------------------------------------------------------------
    */

    $existingLogbook = Logbook::where(
        'schedule_id',
        $schedule->id
    )->first();

    if ($existingLogbook) {

        return back()
            ->withInput()
            ->with(
                'error',
                'A logbook has already been created. Needs to be approved or rejected before creating a new one.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | AVAILABLE BLOCK TIME
    |--------------------------------------------------------------------------
    */

    $availableBlockTime = (float)
        $userBlockTime->remaining_block_time;

    $flightHours = $hobbsHours;

    /*
    |--------------------------------------------------------------------------
    | BLOCK TIME VALIDATION
    |--------------------------------------------------------------------------
    */

    if (
        $availableBlockTime > 0
        && $flightHours > $availableBlockTime
    ) {

        return back()
            ->withInput()
            ->with(
                'error',
                'This flight exceeds the remaining block time of '
                . number_format(
                    $availableBlockTime,
                    1
                )
                . ' hours for '
                . ($schedule->user?->name ?? 'this user')
                . '.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SAVE LOGBOOK
    |--------------------------------------------------------------------------
    */

    try {

        $log = Logbook::create([

            /*
             * Schedule owner, NOT logged-in admin
             */
            'user_id' => $logbookUserId,

            'student_id' => $studentId,

            'instructor_id' => $instructorId,

            'schedule_id' => $schedule->id,

            'flight_id' => $schedule->flight_id,

            'flight_date' => $request->flight_date,

            'aircraft' =>
                $schedule->flight?->registration_number,

            'route' => $request->route,

            'hobbs_start' => $request->hobbs_start,

            'hobbs_end' => $request->hobbs_end,

            'tach_start' => $request->tach_start,

            'tach_end' => $request->tach_end,

            'hours' => $hobbsHours,

            'tach_hours' => $tachHours,

            'block_time' => $initialBlockTime,

            'fuel_added' => $request->fuel_added,

            'oil_added' => $request->oil_added,

            'type' => $request->type,

            'approved' => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | NOTIFY INSTRUCTOR
        |--------------------------------------------------------------------------
        */

        if ($instructorId) {

            $instructor = User::find($instructorId);

            if ($instructor) {

                $instructor->notify(
                    new LogbookCreatedNotification($log)
                );
            }
        }

    } catch (\Throwable $e) {

        Log::error(
            'Logbook save failed',
            [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'logbook_user_id' => $logbookUserId,
            ]
        );

        return back()->with(
            'error',
            'Failed to save logbook entry.'
        );
    }

    // Keep the rest of your existing:
    // - Aircraft totals
    // - Maintenance alerts
    // - AI analysis
    // - Redirect

    return redirect()
        ->route('logbooks.index')
        ->with(
            'success',
            'Logbook entry created successfully.'
        );
}



    public function approve1($id)
    {
        $log = Logbook::findOrFail($id);

        abort_unless(
            auth()->user()->role === 'instructor',
            403
        );

        $log->update([
            'approved' => true,
            'approved_by' => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Logbook approved.'
        );
    }


public function approve(
    $id,
    MaintenanceService $maintenanceService
) {
    abort_unless(
        in_array(auth()->user()->role, ['admin', 'instructor']),
        403
    );

    DB::transaction(function () use ($id, $maintenanceService) {

        /*
        |--------------------------------------------------------------------------
        | LOAD LOGBOOK
        |--------------------------------------------------------------------------
        */

        $log = Logbook::with([
            'user',
            'schedule.flight'
        ])->findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | PREVENT DUPLICATE APPROVAL
        |--------------------------------------------------------------------------
        */

        if ($log->approved) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | APPROVE LOGBOOK
        |--------------------------------------------------------------------------
        */

        $log->update([
            'approved' => true,
            'approved_by' => auth()->id(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | CREATE INVOICE
        |--------------------------------------------------------------------------
        */

        $existingInvoiceItem = InvoiceItem::where(
            'logbook_id',
            $log->id
        )->first();

        if (!$existingInvoiceItem) {

            $amount = round(
                (float) $log->hours *
                (float) $log->hourly_rate,
                2
            );

            $invoice = Invoice::create([
                'user_id' => $log->user_id,

                'invoice_number' =>
                    'INV-' . now()->format('Ymd') . '-' .
                    str_pad(
                        (string) (
                            Invoice::withTrashed()->max('id') + 1
                        ),
                        5,
                        '0',
                        STR_PAD_LEFT
                    ),

                'invoice_date' => now()->toDateString(),

                'subtotal' => $amount,

                'tax' => 0,

                'total' => $amount,

                'currency' => 'USD',

                'status' => 'pending',
            ]);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,

                'logbook_id' => $log->id,

                'flight_id' => $log->flight_id,

                'description' =>
                    'Flight Training - ' .
                    ($log->aircraft ?? 'Aircraft'),

                'quantity' => $log->hours,

                'unit_price' => $log->hourly_rate,

                'amount' => $amount,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | NOTIFY STUDENT
        |--------------------------------------------------------------------------
        */

        if ($log->user) {
            $log->user->notify(
                new LogbookApproved($log)
            );
        }


        /*
        |--------------------------------------------------------------------------
        | COMPLETE DISPATCH
        |--------------------------------------------------------------------------
        */

        $dispatch = Dispatch::where(
            'schedule_id',
            $log->schedule_id
        )
            ->whereIn('status', [
                'Dispatched',
                'In Flight'
            ])
            ->latest('id')
            ->first();

        if ($dispatch) {

            $dispatch->update([
                'status' => 'Completed',
            ]);


            /*
            |--------------------------------------------------------------------------
            | UPDATE AIRCRAFT HOURS + MAINTENANCE
            |--------------------------------------------------------------------------
            */

            if ($dispatch->aircraft) {

                $maintenanceService->updateAircraftFromLogbook(
                    $dispatch->aircraft,
                    $log
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | COMPLETE FLIGHT SCHEDULE
        |--------------------------------------------------------------------------
        */

        if ($log->schedule) {

            $log->schedule->update([
                'status' => 'Completed',
            ]);
        }
    });


    return redirect()
        ->route('logbooks.index')
        ->with(
            'success',
            'Logbook approved successfully and flight schedule completed.'
        );
}


    public function destroy($id)
    {
        $log = Logbook::findOrFail($id);
        $user = Auth::user();

        // STUDENT RULE: can only delete own logs
        if ($user->role === 'student') {
            if ($log->student->user_id !== $user->id) {
                abort(403, 'Not your logbook entry');
            }
        }

        // INSTRUCTOR RULE: can only delete UNAPPROVED logs
        if ($user->role === 'instructor') {
            if ($log->approved) {
                return back()->with('error', 'Cannot delete approved flight logs');
            }
        }

        // ADMIN: full access (no restriction)

        $log->deleted_by = $user->id;
        $log->save();

        $log->delete(); // soft delete

        return back()->with('success', 'Logbook moved to archive');
    }

    public function restore($id)
    {
        $log = Logbook::withTrashed()->findOrFail($id);

        $log->restore();
        $log->deleted_by = null;
        $log->save();

        return back()->with('success', 'Logbook restored successfully');
    }

    public function reject(Request $request, $id)
    {
        abort_unless(
            auth()->user()->role === 'instructor',
            403
        );

        $request->validate([
            'remarks' => 'required'
        ]);

        $log = Logbook::findOrFail($id);

        $log->update([
            'approved' => false,
            'approved_by' => null,
            'remarks' => $request->remarks,
        ]);

        return back()->with(
            'success',
            'Logbook rejected.'
        );
    }
}