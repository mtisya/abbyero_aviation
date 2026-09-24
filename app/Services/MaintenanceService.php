<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Flight;
use App\Models\Logbook;
use App\Models\AircraftTachHistory;

class MaintenanceService
{
    /**
     * Update aircraft hours after a logbook is approved.
     */
    public function updateAircraftFromLogbook(
    Flight $aircraft,
    Logbook $logbook
): void
{

    $oldTach = $aircraft->current_tach;


    $aircraft->update([

        'current_tach' =>
            $logbook->tach_end,

        'current_hobbs' =>
            $logbook->hobbs_end,

    ]);


    AircraftTachHistory::create([

        'aircraft_id' => $aircraft->id,

        'old_tach' => $oldTach,

        'new_tach' =>
            $logbook->tach_end,

        'logbook_id' =>
            $logbook->id,

        'updated_by' =>
            auth()->id(),

        'reason' =>
            'Logbook approval',

    ]);


    $this->recalculateSchedules($aircraft);


    $this->updateAircraftMaintenanceStatus($aircraft);

}

public function updateAircraftMaintenanceStatus(
    Flight $aircraft
)
{

    $overdue =
        $aircraft->maintenanceSchedules()
        ->where('status','overdue')
        ->exists();


    $dueSoon =
        $aircraft->maintenanceSchedules()
        ->where('status','due_soon')
        ->exists();



    if ($overdue) {

        $status = 'grounded';

    } elseif ($dueSoon) {

        $status = 'due_soon';

    } else {

        $status = 'serviceable';

    }


    $aircraft->update([

        'maintenance_status'=>$status

    ]);

}

    /**
     * Recalculate every maintenance schedule.
     */
    public function recalculateSchedules(Flight $aircraft): void
    {
        $aircraft->load(
            'maintenanceSchedules.maintenanceType'
        );

        foreach ($aircraft->maintenanceSchedules as $schedule) {

            $this->updateScheduleStatus($aircraft, $schedule);
        }
    }

    /**
     * Calculate one schedule.
     */
    protected function updateScheduleStatus($aircraft, $schedule): void
    {
        $status = 'pending';

        /*
        |--------------------------------------------------------------------------
        | Hour based maintenance
        |--------------------------------------------------------------------------
        */

        if ($schedule->next_due_tach) {

            $remainingHours =
                $schedule->next_due_tach -
                $aircraft->current_tach;

            $warningHours = 10;

            if ($schedule->maintenanceType?->default_interval_hours) {

                $interval =
                    $schedule->maintenanceType
                        ->default_interval_hours;

                $warningHours =
                    max(5, round($interval * 0.20));
            }

            if ($remainingHours <= 0) {

                $status = 'overdue';

            } elseif ($remainingHours <= $warningHours) {

                $status = 'due_soon';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Calendar based maintenance
        |--------------------------------------------------------------------------
        */

        if ($schedule->next_due_date) {

            $remainingDays = Carbon::today()
                ->diffInDays($schedule->next_due_date, false);

            $warningDays = 30;

            if ($schedule->maintenanceType?->default_interval_days) {

                $interval =
                    $schedule->maintenanceType
                        ->default_interval_days;

                $warningDays =
                    max(7, round($interval * 0.10));
            }

            if ($remainingDays <= 0) {

                $status = 'overdue';

            } elseif (
                $status !== 'overdue' &&
                $remainingDays <= $warningDays
            ) {

                $status = 'due_soon';
            }
        }

        $schedule->update([
            'status' => $status
        ]);
    }
}