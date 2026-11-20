<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateFlightStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flights:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update expired scheduled flights to completed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \App\Models\FlightSchedule::where('status', 'scheduled')
            ->where('end_time', '<', now())
            ->update(['status' => 'completed']);
    }

    
}
