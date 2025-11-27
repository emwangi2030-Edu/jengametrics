<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bq_documents', function (Blueprint $table) {
            if (! Schema::hasColumn('bq_documents', 'units')) {
                $table->unsignedInteger('units')
                    ->default(1)
                    ->after('parent_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bq_documents', function (Blueprint $table) {
            if (Schema::hasColumn('bq_documents', 'units')) {
                $table->dropColumn('units');
            }
        });
    }
};
