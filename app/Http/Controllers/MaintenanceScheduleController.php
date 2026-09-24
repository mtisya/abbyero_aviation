<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\MaintenanceType;
use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MaintenanceScheduleController extends Controller
{
    public function index()
    {
        $schedules = MaintenanceSchedule::with(['aircraft', 'maintenanceType'])
            ->latest()
            ->paginate(5);

        $aircraft = Flight::orderBy('registration_number')->get();
        $types = MaintenanceType::where('is_active', 1)->get();

        return view('admin.index_schedules', compact('schedules', 'aircraft', 'types'));
    }

    public function store1(Request $request)
    {
        $request->validate([
            'aircraft_id' => 'required|exists:flights,id',
            'maintenance_type_id' => 'required|exists:maintenance_types,id',

            'description' => 'nullable|string',
            'interval_hours' => 'nullable|numeric',
            'interval_days' => 'nullable|integer',

            'last_done_tach' => 'nullable|numeric',
            'last_done_date' => 'nullable|date',
        ]);

        $type = MaintenanceType::findOrFail($request->maintenance_type_id);

        $schedule = new MaintenanceSchedule();

        $schedule->aircraft_id = $request->aircraft_id;
        $schedule->maintenance_type_id = $type->id;

        $schedule->description = $request->description;

        $schedule->interval_hours = $type->default_interval_hours ?? $request->interval_hours;
        $schedule->interval_days = $type->default_interval_days ?? $request->interval_days;

        $schedule->last_done_tach = $request->last_done_tach;
        $schedule->last_done_date = $request->last_done_date;

        /*
        | AUTO CALCULATION (FIXED)
        */
        if (!is_null($schedule->interval_hours)) {
            $schedule->next_due_tach =
                ((float) $request->performed_tach) + ((float) $schedule->interval_hours);
        }

        if (!is_null($schedule->interval_days)) {
            $schedule->next_due_date =
                Carbon::parse($request->performed_date)
                    ->addDays((int) $schedule->interval_days);
        }

        $schedule->status = 'pending';
        $schedule->save();

        return back()->with('success', 'Maintenance schedule created successfully.');
    }
    public function store(Request $request)
    {
        $request->validate([
            'aircraft_id' => 'required|exists:flights,id',
            'maintenance_type_id' => 'required|exists:maintenance_types,id',

            'description' => 'nullable|string',
        ]);

        $type = MaintenanceType::findOrFail($request->maintenance_type_id);
        $aircraft = Flight::findOrFail($request->aircraft_id);

        // Get latest maintenance history
        $history = MaintenanceHistory::where('aircraft_id', $aircraft->id)
            ->where('maintenance_type', $type->name)
            ->latest('performed_date')
            ->first();

        $lastDoneDate = $history?->performed_date;
        $lastDoneTach = $history?->performed_tach ?? $aircraft->current_tach;

        // FINAL INTERVAL VALUES (from type OR fallback)
        $intervalHours = $type->default_interval_hours;
        $intervalDays = $type->default_interval_days;

        $schedule = new MaintenanceSchedule();

        $schedule->aircraft_id = $aircraft->id;
        $schedule->maintenance_type_id = $type->id;

        $schedule->description = $request->description;

        $schedule->interval_hours = $intervalHours;
        $schedule->interval_days = $intervalDays;

        $schedule->last_done_tach = $lastDoneTach;
        $schedule->last_done_date = $lastDoneDate;

        // NEXT DUE TACH
        if (!is_null($intervalHours)) {
            $schedule->next_due_tach = $lastDoneTach + $intervalHours;
        }

        // NEXT DUE DATE
        if (!is_null($intervalDays) && $lastDoneDate) {
            $schedule->next_due_date = Carbon::parse($lastDoneDate)
                ->addDays($intervalDays);
        }

        $schedule->status = 'pending';
        $schedule->save();

        return back()->with('success', 'Maintenance schedule created successfully.');
    }
    public function update(Request $request, MaintenanceSchedule $schedule)
    {
        $request->validate([
            'description' => 'nullable|string',

            'interval_hours' => 'nullable|numeric|min:0',
            'interval_days' => 'nullable|integer|min:0',

            'last_done_tach' => 'nullable|numeric|min:0',
            'last_done_date' => 'nullable|date',

            'status' => 'nullable|in:pending,due_soon,overdue,completed',
        ]);

        // update basic fields
        $schedule->description = $request->description;

        $schedule->interval_hours = $request->interval_hours;
        $schedule->interval_days = $request->interval_days;

        $schedule->last_done_tach = $request->last_done_tach;
        $schedule->last_done_date = $request->last_done_date;

        /*
        |------------------------------------------------------------
        | AUTO RECALCULATE NEXT DUE VALUES (IMPORTANT FIX)
        |------------------------------------------------------------
        */

        if ($schedule->last_done_tach !== null && $schedule->interval_hours !== null) {
            $schedule->next_due_tach =
                $schedule->last_done_tach + $schedule->interval_hours;
        }

        if ($schedule->last_done_date && $schedule->interval_days) {
            $schedule->next_due_date =
                Carbon::parse($schedule->last_done_date)
                    ->addDays($schedule->interval_days);
        }

        /*
        |------------------------------------------------------------
        | AUTO STATUS UPDATE (ENGINE LOGIC)
        |------------------------------------------------------------
        */

        if ($schedule->next_due_date && now()->gt($schedule->next_due_date)) {
            $schedule->status = 'overdue';
        } elseif ($schedule->next_due_date && now()->addDays(7)->gt($schedule->next_due_date)) {
            $schedule->status = 'due_soon';
        } else {
            $schedule->status = $request->status ?? 'pending';
        }

        $schedule->save();

        return back()->with('success', 'Schedule updated successfully.');
    }
    public function destroy(MaintenanceSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->back()->with(
            'success',
            'Maintenance schedule deleted successfully.'
        );
    }
}