<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {

            $table->id();

            $table->string('name');
            $table->string('email')->index();
            $table->string('phone');

            $table->string('program')->nullable();

            $table->date('preferred_start_date')->nullable();

            $table->text('message')->nullable();

            $table->enum('status', [
                'submitted',
                'onboarding_sent',
                'onboarding_started',
                'onboarding_completed',
                'reviewed',
                'approved',
                'rejected',
            ])->default('submitted');

            $table->timestamp('onboarding_completed_at')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};