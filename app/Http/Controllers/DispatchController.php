<?php

namespace App\Http\Controllers;

use App\Models\Dispatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;
use App\Models\FlightSchedule;
use Illuminate\Support\Facades\Log;

class DispatchController extends Controller
{
    public function index()
    {
        $dispatches = Dispatch::with([
            'aircraft',
            'pilot',
            'dispatcher',
            'schedule'
        ])
            ->latest()
            ->paginate(5);
        $stats = [
            'scheduled' => FlightSchedule::where('status', 'scheduled')->count(),
            'dispatched' => Dispatch::where('status', 'Dispatched')->count(),
            'completed' => Dispatch::where('status', 'Completed')->count(),
            'cancelled' => Dispatch::where('status', 'Cancelled')->count(),
        ];

        return view('flights.dispatch', compact('dispatches', 'stats'));
    }
    public function show($id)
    {
        $dispatch = Dispatch::with(['aircraft', 'pilot'])->findOrFail($id);

        return view('dispatch.show', compact('dispatch'));
    }
    public function complete($id)
    {
        $dispatch = Dispatch::findOrFail($id);

        $dispatch->update([
            'status' => 'Completed'
        ]);

        return back()->with('success', 'Dispatch marked as completed');
    }
    public function cancel($id)
    {
        $dispatch = Dispatch::findOrFail($id);

        $dispatch->update([
            'status' => 'Cancelled'
        ]);

        return back()->with('success', 'Dispatch cancelled');
    }
    public function destroy($id)
    {
        $dispatch = Dispatch::findOrFail($id);

        $dispatch->delete();

        return back()->with('success', 'Dispatch deleted');
    }

    public function unDispatch(Request $request)
    {
        try {

            $request->validate([
                'schedule_id' => 'required|exists:flight_schedules,id',
            ]);

            /*
            |--------------------------------------------------------------------------
            | GET SCHEDULE
            |--------------------------------------------------------------------------
            */

            $schedule = FlightSchedule::findOrFail(
                $request->schedule_id
            );

            /*
            |--------------------------------------------------------------------------
            | CHECK SCHEDULE OWNERSHIP
            |--------------------------------------------------------------------------
            */

            if (
                auth()->user()->role !== 'admin' &&
                $schedule->user_id !== auth()->id()
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to un-dispatch this schedule.'
                ], 403);
            }

            /*
            |--------------------------------------------------------------------------
            | FIND ACTIVE DISPATCH
            |--------------------------------------------------------------------------
            */

            $dispatch = Dispatch::where(
                    'schedule_id',
                    $request->schedule_id
                )
                ->whereIn('status', [
                    'dispatched',
                    'in_flight',
                    'Dispatched',
                    'In Flight',
                ])
                ->latest('id')
                ->first();

            if (!$dispatch) {

                return response()->json([
                    'success' => false,
                    'message' => 'No active dispatch was found for this schedule.'
                ], 404);
            }

            /*
            |--------------------------------------------------------------------------
            | PREVENT UN-DISPATCH AFTER COMPLETION
            |--------------------------------------------------------------------------
            */

            if (strtolower($dispatch->status) === 'completed') {

                return response()->json([
                    'success' => false,
                    'message' => 'A completed dispatch cannot be un-dispatched.'
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | REMOVE / CANCEL DISPATCH
            |--------------------------------------------------------------------------
            */

            DB::transaction(function () use ($dispatch, $schedule) {

                /*
                | Soft-delete dispatch
                */

                $dispatch->delete();

                /*
                | Return schedule to scheduled
                */

                $schedule->update([
                    'status' => 'scheduled',
                ]);
            });

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,
                'message' =>
                    'Aircraft un-dispatched successfully and schedule returned to scheduled status.',
                'schedule_id' => $schedule->id,
                'status' => 'scheduled',
            ]);

        } catch (Throwable $e) {

            Log::error(
                'Un-Dispatch Failed',
                [
                    'schedule_id' => $request->schedule_id,
                    'user_id' => auth()->id(),
                    'error' => $e->getMessage(),
                ]
            );

            return response()->json([
                'success' => false,
                'message' => 'Unable to un-dispatch the aircraft.'
            ], 500);
        }
    }

    public function store1(Request $request)
    {
        try {

            return DB::transaction(function () use ($request) {

            /*
            |--------------------------------------------------------------------------
            | VALIDATE REQUIRED DATA
            |--------------------------------------------------------------------------
            */

            $request->validate([
                'schedule_id' => 'required|exists:flight_schedules,id',
                'aircraft_id' => 'required',
                'pilot_id' => 'nullable',
                'hobbs_out' => 'required|numeric|min:0',
                'tach_out' => 'required|numeric|min:0',
            ]);

            /*
            |--------------------------------------------------------------------------
            | GET SCHEDULE AND CHECK OWNERSHIP
            |--------------------------------------------------------------------------
            */

            $schedule = FlightSchedule::findOrFail(
                $request->schedule_id
            );

            // Admin can dispatch any schedule.
            // Other users can only dispatch their own schedules.
            if (
                auth()->user()->role !== 'admin' &&
                $schedule->user_id !== auth()->id()
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to dispatch this schedule.'
                ], 403);
            }

            /*
            |--------------------------------------------------------------------------
            | CHECK IF THIS SCHEDULE WAS ALREADY DISPATCHED
            |--------------------------------------------------------------------------
            */

            $existingDispatch = Dispatch::where(
                    'schedule_id',
                    $request->schedule_id
                )
                ->where(
                    'status',
                    Dispatch::STATUS_DISPATCHED
                )
                ->latest('id')
                ->first();

            if ($existingDispatch) {

                return response()->json([
                    'success' => true,
                    'already_dispatched' => true,
                    'message' => 'This schedule is already dispatched.',
                    'dispatch_id' => $existingDispatch->id,
                    'dispatch_no' => $existingDispatch->dispatch_no,
                    'schedule_id' => $request->schedule_id,
                    'status' => 'dispatched',
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | CHECK FOR EXISTING ACTIVE DISPATCH FOR AIRCRAFT
            |--------------------------------------------------------------------------
            */

            $activeDispatch = Dispatch::active()
                ->where('aircraft_id', $request->aircraft_id)
                ->latest('id')
                ->first();

            if ($activeDispatch) {

                return response()->json([
                    'success' => false,
                    'message' =>
                        'This aircraft already has an active dispatch. ' .
                        'Complete the current dispatch before dispatching it again.',
                    'dispatch_id' => $activeDispatch->id,
                    'dispatch_no' => $activeDispatch->dispatch_no,
                    'status' => $activeDispatch->status,
                ], 409);
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE DISPATCH
            |--------------------------------------------------------------------------
            */

            $dispatch = Dispatch::create([
                'schedule_id' => $request->schedule_id,
                'aircraft_id' => $request->aircraft_id,
                'pilot_id' => $request->pilot_id,

                'dispatch_no' => $request->dispatch_no
                    ?: 'ABY-LLC-' .
                        now()->format('Ymd') . '-' .
                        strtoupper(Str::random(5)),

                'hobbs_out' => $request->hobbs_out,
                'tach_out' => $request->tach_out,
                'dispatch_time' => now(),
                'remarks' => $request->remarks,
                'status' => Dispatch::STATUS_DISPATCHED,
                'dispatched_by' => auth()->id(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | UPDATE FLIGHT SCHEDULE STATUS
            |--------------------------------------------------------------------------
            */

            $schedule->update([
                'status' => 'dispatched',
            ]);

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,
                'already_dispatched' => false,
                'message' => 'Aircraft dispatched successfully.',
                'dispatch_id' => $dispatch->id,
                'schedule_id' => $schedule->id,
                'dispatch_no' => $dispatch->dispatch_no,
                'status' => 'dispatched',
            ]);
        });

        } catch (Exception $e) {

            Log::error('Dispatch Creation Failed', [
                'user_id' => auth()->id(),
                'schedule_id' => $request->schedule_id,
                'aircraft_id' => $request->aircraft_id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'schedule_id' => [
            'required',
            'exists:flight_schedules,id',
        ],
        'aircraft_id' => [
            'required',
            'exists:flights,id',
        ],
        'pilot_id' => [
            'nullable',
            'exists:users,id',
        ],
        'hobbs_out' => [
            'required',
            'numeric',
            'min:0',
        ],
        'tach_out' => [
            'required',
            'numeric',
            'min:0',
        ],
        'dispatch_no' => [
            'nullable',
            'string',
            'max:100',
        ],
        'remarks' => [
            'nullable',
            'string',
        ],
    ]);

    try {
        return DB::transaction(function () use ($validated) {

            /*
            |--------------------------------------------------------------------------
            | GET SCHEDULE
            |--------------------------------------------------------------------------
            */

            $schedule = FlightSchedule::findOrFail(
                $validated['schedule_id']
            );

            /*
            |--------------------------------------------------------------------------
            | AUTHORIZATION
            |--------------------------------------------------------------------------
            */

            if (
                auth()->user()->role !== 'admin' &&
                (int) $schedule->user_id !== (int) auth()->id()
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to dispatch this schedule.',
                ], 403);
            }

            /*
            |--------------------------------------------------------------------------
            | CHECK IF SCHEDULE IS ALREADY DISPATCHED
            |--------------------------------------------------------------------------
            */

            $existingDispatch = Dispatch::where(
                'schedule_id',
                $schedule->id
            )
                ->whereIn('status', [
                    Dispatch::STATUS_DISPATCHED,
                    Dispatch::STATUS_IN_FLIGHT,
                ])
                ->latest('id')
                ->first();

            if ($existingDispatch) {

                return response()->json([
                    'success' => true,
                    'already_dispatched' => true,
                    'message' => 'This schedule is already dispatched.',
                    'dispatch_id' => $existingDispatch->id,
                    'dispatch_no' => $existingDispatch->dispatch_no,
                    'schedule_id' => $schedule->id,
                    'status' => strtolower(
                        $existingDispatch->status
                    ),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | CHECK FOR ACTIVE AIRCRAFT DISPATCH
            |--------------------------------------------------------------------------
            */

            $activeDispatch = Dispatch::active()
                ->where(
                    'aircraft_id',
                    $validated['aircraft_id']
                )
                ->latest('id')
                ->first();

            if ($activeDispatch) {

                return response()->json([
                    'success' => false,
                    'message' =>
                        'This aircraft already has an active dispatch. ' .
                        'Complete the current dispatch before dispatching it again.',
                    'dispatch_id' => $activeDispatch->id,
                    'dispatch_no' => $activeDispatch->dispatch_no,
                    'status' => strtolower(
                        $activeDispatch->status
                    ),
                ], 409);
            }

            /*
            |--------------------------------------------------------------------------
            | GENERATE DISPATCH NUMBER
            |--------------------------------------------------------------------------
            */

            $dispatchNo = $validated['dispatch_no']
                ?? null;

            if (!$dispatchNo) {
                $dispatchNo =
                    'ABY-LLC-' .
                    now()->format('Ymd') .
                    '-' .
                    strtoupper(Str::random(5));
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE DISPATCH
            |--------------------------------------------------------------------------
            */

            $dispatch = Dispatch::create([
                'schedule_id' => $schedule->id,
                'aircraft_id' => $validated['aircraft_id'],
                'pilot_id' => $validated['pilot_id'] ?? $schedule->user_id,
                'dispatch_no' => $dispatchNo,
                'hobbs_out' => $validated['hobbs_out'],
                'tach_out' => $validated['tach_out'],
                'dispatch_time' => now(),
                'remarks' => $validated['remarks'] ?? null,
                'status' => Dispatch::STATUS_DISPATCHED,
                'dispatched_by' => auth()->id(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | UPDATE FLIGHT SCHEDULE
            |--------------------------------------------------------------------------
            |
            | This is what controls the FullCalendar event color.
            |
            */

            $schedule->update([
                'status' => 'dispatched',
            ]);

            /*
            |--------------------------------------------------------------------------
            | RESPONSE
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,
                'already_dispatched' => false,
                'message' => 'Aircraft dispatched successfully.',
                'dispatch_id' => $dispatch->id,
                'schedule_id' => $schedule->id,
                'dispatch_no' => $dispatch->dispatch_no,
                'status' => 'dispatched',
            ]);
        });

    } catch (\Throwable $e) {

        Log::error('Dispatch Creation Failed', [
            'user_id' => auth()->id(),
            'schedule_id' => $request->schedule_id,
            'aircraft_id' => $request->aircraft_id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Server error occurred while dispatching the aircraft.',
        ], 500);
    }
    }

}