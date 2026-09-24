<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * This migration establishes the current database schema as the
     * baseline for future migrations.
     *
     * The production database already contains the current schema,
     * so this migration intentionally does not create or modify tables.
     */
    public function up(): void
    {
        //
    }

    /**
     * The baseline must not attempt to remove the existing production schema.
     */
    public function down(): void
    {
        //
    }
};