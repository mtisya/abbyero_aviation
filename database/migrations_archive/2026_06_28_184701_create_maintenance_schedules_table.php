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
        Schema::create('maintenance_schedules', function (Blueprint $table) {

        $table->id();

        $table->foreignId('aircraft_id')
            ->constrained('flights')
            ->cascadeOnDelete();

        $table->foreignId('maintenance_type_id')
            ->constrained('maintenance_types')
            ->cascadeOnDelete();

        $table->string('description')->nullable();

        $table->decimal('interval_hours',8,1)->nullable();

        $table->integer('interval_days')->nullable();

        $table->decimal('last_done_tach',8,1)->nullable();

        $table->date('last_done_date')->nullable();

        $table->decimal('next_due_tach',8,1)->nullable();

        $table->date('next_due_date')->nullable();

        $table->enum('status',[
        'pending',
        'due_soon',
        'overdue',
        'completed'
        ])->default('pending');

        $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};
