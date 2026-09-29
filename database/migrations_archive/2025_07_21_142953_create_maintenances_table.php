<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {

        if (!Schema::hasTable('maintenances')) {
            Schema::create('maintenances', function (Blueprint $table) {
                $table->id();
                $table->string('aircraft_model');
                $table->string('registration_number');
                $table->string('manufacturer')->nullable();
                $table->string('serial_number')->nullable();
                $table->string('engine_type')->nullable();
                $table->decimal('last_maintenance_hours', 8, 2)->nullable();
                $table->date('maintenance_date');
                $table->date('next_due_date')->nullable();
                $table->text('issue_description');
                $table->text('remarks')->nullable();
                $table->string('status')->default('Pending');
                $table->timestamps();
            });
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
