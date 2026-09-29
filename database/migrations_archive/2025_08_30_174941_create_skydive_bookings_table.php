<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::create('skydivebooking', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('skydiving_id');
        $table->unsignedBigInteger('user_id')->nullable(); // if you want to track who booked
        $table->string('status')->default('pending');
        $table->timestamps();

        $table->foreign('skydiving_id')->references('id')->on('skydivings')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skydive_bookings');
    }
};
