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
        Schema::create('maintenance_types', function (Blueprint $table) {
            $table->id();

            $table->string('name')->unique();

            $table->string('code')->unique()->nullable();
            // OIL, 50HR, 100HR, ANNUAL, ELT...

            $table->string('category')->nullable();
            // Inspection, Oil, Avionics, Engine...

            $table->decimal('default_interval_hours',8,1)->nullable();

            $table->integer('default_interval_days')->nullable();

            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_types');
    }
};