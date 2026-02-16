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
    Schema::create('gliders', function (Blueprint $table) {
        $table->id();
        $table->string('model');                 // Glider model (e.g., DG-1000)
        $table->string('registration')->unique(); // Registration number
        $table->integer('capacity')->default(1); // Number of seats
        $table->decimal('rental_price', 10, 2); // Rental price per hour
        $table->string('status')->default('available'); // available, rented, maintenance
        $table->text('description')->nullable(); 
        $table->string('image')->nullable(); // path to glider image
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gliders');
    }
};
