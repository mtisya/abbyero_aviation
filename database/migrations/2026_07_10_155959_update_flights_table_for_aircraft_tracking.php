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
        Schema::table('flights', function (Blueprint $table) {

            $table->dropColumn('next_100hr_due');

            $table->enum('maintenance_status', [
                'serviceable',
                'due_soon',
                'maintenance',
                'grounded'
            ])
            ->default('serviceable')
            ->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
