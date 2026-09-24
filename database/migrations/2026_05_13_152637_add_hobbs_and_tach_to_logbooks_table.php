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
        Schema::table('logbooks', function (Blueprint $table) {

            // 🔹 HOBBS
            $table->decimal('hobbs_start', 8, 1)
                  ->nullable()
                  ->after('route');

            $table->decimal('hobbs_end', 8, 1)
                  ->nullable()
                  ->after('hobbs_start');

            // 🔹 TACH
            $table->decimal('tach_start', 8, 1)
                  ->nullable()
                  ->after('hobbs_end');

            $table->decimal('tach_end', 8, 1)
                  ->nullable()
                  ->after('tach_start');

            // 🔹 Optional calculated tach hours
            $table->decimal('tach_hours', 5, 2)
                  ->nullable()
                  ->after('hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('logbooks', function (Blueprint $table) {

            $table->dropColumn([
                'hobbs_start',
                'hobbs_end',
                'tach_start',
                'tach_end',
                'tach_hours',
            ]);
        });
    }
};