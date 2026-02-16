<?php

// database/migrations/xxxx_xx_xx_create_skydivings_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('skydivings', function (Blueprint $table) {
            $table->id();
            $table->string('title');              // e.g. Tandem Jump, Solo Jump
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);      // booking price
            $table->string('location');           // where skydiving happens
            $table->date('date');                 // event date
            $table->time('time');                 // event time
            $table->integer('available_slots');   // number of available seats
            $table->string('image')->nullable();  // event image/banner
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('skydivings');
    }
};
