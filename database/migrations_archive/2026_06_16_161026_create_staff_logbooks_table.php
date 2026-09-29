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
        Schema::create('staff_logbooks', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('schedule_id')
                ->nullable()
                ->constrained('flight_schedules')
                ->nullOnDelete();

            $table->date('flight_date');

            $table->string('aircraft');

            $table->string('route')->nullable();

            $table->decimal('hobbs_start', 8, 1);
            $table->decimal('hobbs_end', 8, 1);

            $table->decimal('tach_start', 8, 1);
            $table->decimal('tach_end', 8, 1);

            $table->decimal('hours', 5, 2)->nullable();
            $table->decimal('tach_hours', 5, 2)->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_logbooks');
    }
};
