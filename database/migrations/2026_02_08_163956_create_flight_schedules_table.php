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
        Schema::create('flight_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('flight_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable(); // renter/customer

            $table->dateTime('start_time');
            $table->dateTime('end_time');

            $table->enum('status', ['scheduled','cancelled','completed'])
                ->default('scheduled');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_schedules');
    }
};
