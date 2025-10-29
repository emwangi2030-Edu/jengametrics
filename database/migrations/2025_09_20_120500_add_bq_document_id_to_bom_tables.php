<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bom_items', function (Blueprint $table) {
            if (!Schema::hasColumn('bom_items', 'bq_document_id')) {
                $table->foreignId('bq_document_id')
                    ->nullable()
                    ->after('project_id')
                    ->constrained('bq_documents')
                    ->nullOnDelete();
            }
        });

        Schema::table('bom_labours', function (Blueprint $table) {
            if (!Schema::hasColumn('bom_labours', 'bq_document_id')) {
                $table->foreignId('bq_document_id')
                    ->nullable()
                    ->after('bq_section_id')
                    ->constrained('bq_documents')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('bom_items', function (Blueprint $table) {
            if (Schema::hasColumn('bom_items', 'bq_document_id')) {
                $table->dropConstrainedForeignId('bq_document_id');
            }
        });

        Schema::table('bom_labours', function (Blueprint $table) {
            if (Schema::hasColumn('bom_labours', 'bq_document_id')) {
                $table->dropConstrainedForeignId('bq_document_id');
            }
        });
    }
};
