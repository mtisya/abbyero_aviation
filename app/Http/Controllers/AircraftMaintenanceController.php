<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Logbook;
use App\Models\MaintenanceType;

class AircraftMaintenanceController extends Controller
{
    public function dashboard()
    {
        $aircraft = Flight::with([
            'maintenanceSchedules' => function ($q) {
                $q->where('status', '!=', 'completed');
            },
            'maintenanceSchedules.maintenanceType'
        ])
            ->orderBy('registration_number')
            ->get();

        $dueSoon = $aircraft->sum(function ($plane) {
            return $plane->maintenanceSchedules
                ->where('status', 'due_soon')
                ->count();
        });

        $overdue = $aircraft->sum(function ($plane) {
            return $plane->maintenanceSchedules
                ->where('status', 'overdue')
                ->count();
        });

        return view('admin.aircraftdashboard', compact(
            'aircraft',
            'dueSoon',
            'overdue'
        ));
    }

    public function history(Request $request, Flight $aircraft)
    {
        $query = MaintenanceHistory::with([
            'maintenanceSchedule.maintenanceType',
            'maintenanceSchedule.aircraft'
        ])
            ->where('aircraft_id', $aircraft->id)
            ->latest();

        /*
        |--------------------------------------------------
        | FILTER: Maintenance Type (from schedule)
        |--------------------------------------------------
        */
        if ($request->filled('type')) {

            $typeId = $request->type;

            $query->whereHas('maintenanceSchedule', function ($q) use ($typeId) {
                $q->where('maintenance_type_id', $typeId);
            });
        }

        $history = $query->paginate(10)->withQueryString();

        $types = MaintenanceType::orderBy('name')->get();

        return view('admin.aircraft-history', compact(
            'aircraft',
            'history',
            'types'
        ));
    }

    public function allHistory(Request $request)
    {
        $query = MaintenanceHistory::with([
            'aircraft',
            'maintenanceSchedule.maintenanceType'
        ])
            ->latest();

        // optional filter by type
        if ($request->filled('type')) {
            $query->whereHas('maintenanceSchedule', function ($q) use ($request) {
                $q->where('maintenance_type_id', $request->type);
            });
        }

        $history = $query->paginate(15)->withQueryString();

        $types = MaintenanceType::all();

        return view('admin.index_history', compact('history', 'types'));
    }

    public function showAircraft(Flight $aircraft)
    {
        $aircraft->load([
            'maintenanceSchedules.maintenanceType',
        ]);

        return view('admin.aircraft-show', compact('aircraft'));
    }

    public function perform(Request $request)
    {

        $request->validate([
            'schedule_id' => 'required|exists:maintenance_schedules,id',
            'performed_date' => 'required|date',
            'performed_tach' => 'required|numeric',
            'performed_hobbs' => 'nullable|numeric',
            'performed_by' => 'nullable|string',
            'engineer_license' => 'nullable|string',
            'cost' => 'nullable|numeric',
            'work_performed' => 'required|string',
            'remarks' => 'nullable|string',
            'parts_replaced' => 'nullable|string',
        ]);


        $schedule = MaintenanceSchedule::with(['maintenanceType', 'aircraft'])
            ->findOrFail($request->schedule_id);


        /*
        |--------------------------------------------------
        | 1. SAVE HISTORY
        |--------------------------------------------------
        */
        MaintenanceHistory::create([
            'aircraft_id' => $schedule->aircraft_id,
            'maintenance_schedule_id' => $schedule->id,
            'maintenance_type' => $schedule->maintenanceType->name,
            'performed_date' => $request->performed_date,
            'performed_tach' => $request->performed_tach,
            'performed_hobbs' => $request->performed_hobbs,
            'performed_by' => $request->performed_by,
            'engineer_license' => $request->engineer_license,
            'cost' => $request->cost,
            'work_performed' => $request->work_performed,
            'parts_replaced' => $request->parts_replaced,
            'remarks' => $request->remarks,
            'recorded_by' => auth()->id(),
        ]);

        /*
        |--------------------------------------------------
        | 2. UPDATE SCHEDULE
        |--------------------------------------------------
        */

        $schedule->last_done_tach = $request->performed_tach;
        $schedule->last_done_date = $request->performed_date;

        if ($schedule->interval_hours) {
            $schedule->next_due_tach =
                $request->performed_tach + $schedule->interval_hours;
        }

        if ($schedule->interval_days) {
            $schedule->next_due_date =
                Carbon::parse($request->performed_date)
                    ->addDays($schedule->interval_days);
        }

        // AUTO STATUS FIX
        if ($schedule->next_due_date && now()->gt($schedule->next_due_date)) {
            $schedule->status = 'overdue';
        } elseif ($schedule->next_due_date && now()->addDays(7)->gt($schedule->next_due_date)) {
            $schedule->status = 'due_soon';
        } else {
            $schedule->status = 'completed';
        }

        $schedule->save();

        /*
        |--------------------------------------------------
        | 3. UPDATE AIRCRAFT
        |--------------------------------------------------
        */

        $schedule->aircraft->update([
            'current_tach' => $request->performed_tach
        ]);

        return back()->with('success', 'Maintenance completed successfully.');
    }

    public function maintenanceDetails(MaintenanceSchedule $schedule)
    {
        $schedule->load(['aircraft', 'maintenanceType']);

        $aircraft = $schedule->aircraft;

        $latestLogbook = Logbook::where('aircraft', $aircraft->registration_number)
            ->where('approved', 1)
            ->latest('flight_date')
            ->first();

        return response()->json([
            'aircraft' => [
                'registration' => $aircraft->registration_number,
                'model' => $aircraft->aircraft_model,
            ],

            'schedule' => [
                'id' => $schedule->id,
                'maintenance_type' => $schedule->maintenanceType->name,
                'next_due_tach' => $schedule->next_due_tach,
                'next_due_date' => optional($schedule->next_due_date)->format('Y-m-d'),
            ],

            'current_tach' =>
                $latestLogbook?->tach_end
                ?? $aircraft->current_tach,

            'current_hobbs' =>
                $latestLogbook?->hobbs_end
                ?? $aircraft->current_hobbs ?? null,
        ]);
    }
}