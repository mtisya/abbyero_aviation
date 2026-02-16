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
        if (!Schema::hasTable('aircraft_parts')) {

            Schema::create('aircraft_parts', function (Blueprint $table) {
                $table->id();
                $table->string('part_number')->unique();
                $table->string('name');
                $table->string('category')->nullable(); // e.g. Engine, Avionics, Landing Gear
                $table->text('description')->nullable();
                $table->integer('quantity')->default(0);
                $table->decimal('price', 10, 2)->default(0);
                $table->string('status')->default('available'); // available, reserved, installed
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aircraft_parts');
    }
};
