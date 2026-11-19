<?php
namespace App\Console\Commands;

use App\Mail\DispatchCompletionReminder;
use App\Models\Dispatch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDispatchCompletionReminders extends Command
{
    protected $signature = 'flights:send-dispatch-reminders
                        {--dry-run : Preview reminders without sending emails}';
    protected $description = 'Send daily dispatch completion reminders at 9 AM in each recipient timezone';

    public function handle(): int
    {
        // Always use the actual current UTC time.
        $nowUtc = now('UTC');

        $sent    = 0;
        $skipped = 0;

        $validTimezones = timezone_identifiers_list(
            \DateTimeZone::ALL_WITH_BC
        );

        Dispatch::query()
            ->with([
                'schedule.user',
                'aircraft',
            ])
            ->whereIn('status', [
                'Dispatched',
                'dispatched',
                'in_flight',
                'In Flight',
            ])
            ->whereHas('schedule', function ($query) {
                $query->whereNotIn('status', [
                    'Completed',
                    'completed',
                    'Cancelled',
                    'cancelled',
                    'Canceled',
                    'canceled',
                ]);
            })
            ->chunkById(100, function ($dispatches) use (
                &$sent,
                &$skipped,
                $nowUtc,
                $validTimezones
            ) {
                foreach ($dispatches as $dispatch) {
                    $recipient = $dispatch->schedule?->user;

                    if (! $recipient || ! $recipient->email) {
                        $this->warn(
                            "Skipped dispatch {$dispatch->id}: no schedule owner email."
                        );

                        $skipped++;
                        continue;
                    }

                    $timezone = $recipient->timezone;

                    if (
                        ! $timezone ||
                        ! in_array($timezone, $validTimezones, true)
                    ) {
                        $this->warn(
                            "Skipped {$recipient->email}: "
                            . "no valid browser timezone is saved."
                        );

                        $skipped++;
                        continue;
                    }

                    $localNow = $nowUtc->copy()->setTimezone($timezone);

                    if ($localNow->format('H:i') !== '09:00') {
                        continue;
                    }

                    $localDayStartUtc = $localNow->copy()
                        ->startOfDay()
                        ->utc();

                    $lastReminder = $dispatch->last_reminder_sent_at;

                    if (
                        $lastReminder &&
                        $lastReminder->greaterThanOrEqualTo($localDayStartUtc)
                    ) {
                        $this->line(
                            "Already reminded today: {$dispatch->dispatch_no} "
                            . "({$timezone})."
                        );

                        continue;
                    }

                    if ($this->option('dry-run')) {
                        $this->info(
                            "Would send reminder to {$recipient->email} "
                            . "for dispatch {$dispatch->dispatch_no} "
                            . "at 09:00 {$timezone}."
                        );

                        $sent++;
                        continue;
                    }

                    try {
                        Mail::to($recipient->email)->send(
                            new DispatchCompletionReminder($dispatch)
                        );

                        $dispatch->forceFill([
                            'last_reminder_sent_at' => $nowUtc,
                        ])->save();

                        $this->info(
                            "Reminder sent to {$recipient->email} "
                            . "for dispatch {$dispatch->dispatch_no} "
                            . "at 09:00 {$timezone}."
                        );

                        $sent++;

                    } catch (\Throwable $exception) {
                        report($exception);

                        $this->error(
                            "Failed dispatch {$dispatch->dispatch_no}: "
                            . $exception->getMessage()
                        );

                        $skipped++;
                    }
                }
            });

        $this->newLine();
        $this->info("Reminders processed: {$sent}");
        $this->info("Skipped or failed: {$skipped}");

        return self::SUCCESS;
    }
}
