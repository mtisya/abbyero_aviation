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
        Schema::table('onboarding_details', function (Blueprint $table) {

            $table->string('identity_document_path')
                ->nullable()
                ->after('additional_information');

            $table->string('pilot_certificate_document_path')
                ->nullable()
                ->after('identity_document_path');

            $table->string('medical_certificate_document_path')
                ->nullable()
                ->after('pilot_certificate_document_path');

            $table->string('logbook_document_path')
                ->nullable()
                ->after('medical_certificate_document_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('onboarding_details', function (Blueprint $table) {

            $table->dropColumn([
                'identity_document_path',
                'pilot_certificate_document_path',
                'medical_certificate_document_path',
                'logbook_document_path',
            ]);
        });
    }
};