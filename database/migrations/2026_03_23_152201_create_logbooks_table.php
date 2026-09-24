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
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('flight_id')->nullable()->constrained()->nullOnDelete();

            $table->date('flight_date');
            $table->string('aircraft');
            $table->string('route')->nullable();

            $table->decimal('hours', 5, 2); // e.g. 1.5 hrs
            $table->enum('type', ['dual', 'solo']);

            $table->boolean('approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users');

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logbooks');
    }
};
