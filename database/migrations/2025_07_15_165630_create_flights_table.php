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
        if (!Schema::hasTable('flights')) {

            Schema::create('flights', function (Blueprint $table) {
                $table->id();
                $table->string('aircraft_model');
                $table->string('registration_number')->unique();
                $table->dateTime('departure_time');
                $table->dateTime('arrival_time');
                $table->string('departure_location');
                $table->string('arrival_location');
                $table->decimal('price', 10, 2);
                $table->string('status')->default('available'); // available, booked, completed
                $table->timestamps();
            });
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
