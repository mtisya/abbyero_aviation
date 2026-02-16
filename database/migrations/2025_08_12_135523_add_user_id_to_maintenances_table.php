<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('maintenances', function (Blueprint $table) {
            // Step 1: Add as nullable
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
        });

        // Step 2: Set all existing maintenances to belong to user ID 1 (adjust if needed)
        DB::table('maintenances')->whereNull('user_id')->update(['user_id' => 5]);

        Schema::table('maintenances', function (Blueprint $table) {
            // Step 3: Make non-nullable
            $table->unsignedBigInteger('user_id')->nullable(false)->change();

            // Step 4: Add foreign key constraint
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
