<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dispatches', function (Blueprint $table) {

            $table->id();

            $table->foreignId('schedule_id')->nullable();
            $table->foreignId('aircraft_id')->nullable();
            $table->foreignId('pilot_id')->nullable();

            $table->string('dispatch_no')->unique();

            $table->decimal('hobbs_out', 10, 1)->nullable();
            $table->decimal('tach_out', 10, 1)->nullable();

            $table->timestamp('dispatch_time');

            $table->string('status')->default('Dispatched');

            $table->text('remarks')->nullable();

            $table->foreignId('dispatched_by');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatches');
    }
};
