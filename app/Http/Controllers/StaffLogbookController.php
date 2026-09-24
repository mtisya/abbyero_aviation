<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use App\Models\FlightSchedule;
use Carbon\Carbon;

class StaffLogbookController extends Controller
{
    public function modalDataworking()
    {
       $schedules = FlightSchedule::with(['flight', 'dispatch'])
        ->where('user_id', auth()->id())
        ->whereHas('dispatches', fn ($q) =>
            $q->where('status', 'Dispatched')
        )
        ->orderBy('start_time')
        ->get();

        // ✅ FIX: group logs by flight_id (NOT aircraft string)
            $latestLogPerFlight = Logbook::whereNotNull('flight_id')
                ->orderByDesc('id')
                ->get()
                ->groupBy('flight_id');

            $data = $schedules->map(function ($schedule) use ($latestLogPerFlight) {

            $flightId = $schedule->flight_id;

            $log = $latestLogPerFlight[$flightId][0] ?? null;

            $dispatch = $schedule->dispatch;

            return [
                'schedule_id' => $schedule->id,
                'flight_id'   => $schedule->flight_id,
                // ✅ unified aircraft reference
                'aircraft' => optional($schedule->flight)->registration_number,

                'hobbs' => $schedule->flight?->current_hobbs ?? 0,
                'tach'  => $schedule->flight?->current_tach ?? 0,
                'start_time' => optional($schedule->start_time)
                    ?->format('d M H:i'),
            ];
        })->values();

        return response()->json([
            'schedules' => $data
        ]);
    }

    public function modalData()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | LOAD DISPATCHED SCHEDULES
        |--------------------------------------------------------------------------
        */

        $query = FlightSchedule::with([
            'flight',
            'dispatch',
            'user',
        ])
        ->whereHas('dispatches', function ($q) {
            $q->where('status', 'Dispatched');
        });

        /*
        |--------------------------------------------------------------------------
        | ADMIN CAN SEE EVERYONE
        |--------------------------------------------------------------------------
        */

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        $schedules = $query
            ->orderBy('start_time')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | GET LATEST LOGBOOK PER FLIGHT
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Use flight_id, not aircraft registration.
        |
        */

        $latestLogPerFlight = Logbook::whereNotNull('flight_id')
            ->orderByDesc('id')
            ->get()
            ->groupBy('flight_id');

        /*
        |--------------------------------------------------------------------------
        | BUILD RESPONSE
        |--------------------------------------------------------------------------
        */

        $data = $schedules->map(function ($schedule) use ($latestLogPerFlight) {

            $flightId = $schedule->flight_id;

            /*
            |--------------------------------------------------------------------------
            | LATEST LOGBOOK FOR THIS SPECIFIC AIRCRAFT / FLIGHT
            |--------------------------------------------------------------------------
            */

            $log = $latestLogPerFlight[$flightId][0] ?? null;

            /*
            |--------------------------------------------------------------------------
            | HOBBS / TACH START
            |--------------------------------------------------------------------------
            |
            | Follow the old working behavior:
            |
            | 1. Latest logbook end value
            | 2. Otherwise aircraft current value
            |
            */

            $hobbsStart = $log?->hobbs_end
                ?? $schedule->flight?->current_hobbs
                ?? 0;

            $tachStart = $log?->tach_end
                ?? $schedule->flight?->current_tach
                ?? 0;

            return [
                'schedule_id' => $schedule->id,

                'flight_id' => $schedule->flight_id,

                /*
                * Person this schedule belongs to.
                */
                'user_id' => $schedule->user_id,

                'user_name' => $schedule->user?->name,

                /*
                * Unified aircraft reference.
                */
                'aircraft' => $schedule->flight?->registration_number,

                /*
                * Correct starting meter values.
                */
                'hobbs' => (float) $hobbsStart,

                'tach' => (float) $tachStart,

                'start_time' => $schedule->start_time
                    ?->format('d M H:i'),
            ];

        })->values();

        return response()->json([
            'schedules' => $data,
        ]);
    }
}