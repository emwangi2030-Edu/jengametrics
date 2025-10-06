<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bq_documents', function (Blueprint $table) {
            if (!Schema::hasColumn('bq_documents', 'project_id')) {
                $table->foreignId('project_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->cascadeOnDelete();
            }

            if (!Schema::hasColumn('bq_documents', 'parent_id')) {
                $table->foreignId('parent_id')
                    ->nullable()
                    ->after('project_id')
                    ->constrained('bq_documents')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('bq_documents', function (Blueprint $table) {
            if (Schema::hasColumn('bq_documents', 'parent_id')) {
                $table->dropConstrainedForeignId('parent_id');
            }

            if (Schema::hasColumn('bq_documents', 'project_id')) {
                $table->dropConstrainedForeignId('project_id');
            }
        });
    }
};
