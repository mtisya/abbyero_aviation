<?php
namespace App\Services;

use App\Models\AircraftTachHistory;
use App\Models\Flight;
use App\Models\Logbook;
use App\Models\User;
use App\Notifications\MaintenanceAlert;
use Carbon\Carbon;

class MaintenanceService
{
    /**
     * Update aircraft hours after a logbook is approved.
     */
    public function updateAircraftFromLogbook(
        Flight $aircraft,
        Logbook $logbook
    ): void {

        $oldTach = $aircraft->current_tach;

        $aircraft->update([

            'current_tach'  =>
            $logbook->tach_end,

            'current_hobbs' =>
            $logbook->hobbs_end,

        ]);

        AircraftTachHistory::create([

            'aircraft_id' => $aircraft->id,

            'old_tach'    => $oldTach,

            'new_tach'    =>
            $logbook->tach_end,

            'logbook_id'  =>
            $logbook->id,

            'updated_by'  =>
            auth()->id(),

            'reason'      =>
            'Logbook approval',

        ]);

        $this->recalculateSchedules($aircraft);

        $this->updateAircraftMaintenanceStatus($aircraft);

    }

    public function updateAircraftMaintenanceStatus(
        Flight $aircraft
    ) {

        $overdue =
        $aircraft->maintenanceSchedules()
            ->where('status', 'overdue')
            ->exists();

        $dueSoon =
        $aircraft->maintenanceSchedules()
            ->where('status', 'due_soon')
            ->exists();

        if ($overdue) {

            $status = 'grounded';

        } elseif ($dueSoon) {

            $status = 'due_soon';

        } else {

            $status = 'serviceable';

        }

        $aircraft->update([

            'maintenance_status' => $status,

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
        // Capture status before recalculation
        $previousStatus = $schedule->status;

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

        // Save the calculated status
        $schedule->update([
            'status' => $status,
        ]);

        /*
    |--------------------------------------------------------------------------
    | MAINTENANCE NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

        // Notify only when the status actually changes
        if (
            $previousStatus !== $status &&
            in_array($status, ['due_soon', 'overdue'], true)
        ) {
            $aircraftName =
            $aircraft->registration_number ?? $aircraft->aircraft_model ?? 'Unknown Aircraft';

            $maintenanceName =
            $schedule->maintenanceType?->name ?? $schedule->description ?? 'Scheduled Maintenance';

            $details = [];

            if ($schedule->next_due_tach !== null) {
                $details[] = 'Next due tach: '
                . $schedule->next_due_tach;
            }

            if ($schedule->next_due_date) {
                $details[] = 'Next due date: '
                . $schedule->next_due_date->format('d M Y');
            }

            $detailsMessage = implode(' | ', $details);

            // Admins and instructors only
            User::whereIn('role', ['admin', 'instructor'])
                ->get()
                ->each(function ($recipient) use (
                    $aircraftName,
                    $maintenanceName,
                    $status,
                    $detailsMessage
                ) {
                    $recipient->notify(
                        new MaintenanceAlert(
                            $aircraftName,
                            $maintenanceName,
                            $status,
                            $detailsMessage
                        )
                    );
                });
        }
    }
}
