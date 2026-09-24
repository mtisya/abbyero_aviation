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
        Schema::create('maintenance_histories', function (Blueprint $table) {
            $table->id();

            // Aircraft
            $table->foreignId('aircraft_id')
                  ->constrained('flights')
                  ->cascadeOnDelete();

            // Related maintenance schedule (optional)
            $table->foreignId('maintenance_schedule_id')
                  ->nullable()
                  ->constrained('maintenance_schedules')
                  ->nullOnDelete();

            // Type of maintenance
            $table->foreignId('maintenance_type_id')
                ->constrained('maintenance_types')
                ->cascadeOnDelete();

            // When maintenance was carried out
            $table->date('performed_date');

            // Aircraft readings
            $table->decimal('performed_tach', 8, 1)->nullable();
            $table->decimal('performed_hobbs', 8, 1)->nullable();

            // Who performed the maintenance
            $table->string('performed_by')->nullable();

            // Optional engineer/AME licence
            $table->string('engineer_license')->nullable();

            // Cost
            $table->decimal('cost', 10, 2)->nullable();

            // Description of work performed
            $table->text('work_performed');

            // Parts replaced
            $table->text('parts_replaced')->nullable();

            // General remarks
            $table->text('remarks')->nullable();

            // User who recorded it
            $table->foreignId('recorded_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->timestamps();

            $table->index(['aircraft_id', 'maintenance_type_id']);
            $table->index('performed_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_histories');
    }
};