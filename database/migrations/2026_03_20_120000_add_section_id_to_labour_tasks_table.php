<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('labour_tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('labour_tasks', 'section_id')) {
                $table->foreignId('section_id')
                    ->nullable()
                    ->after('description')
                    ->constrained('sections')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('labour_tasks', function (Blueprint $table) {
            if (Schema::hasColumn('labour_tasks', 'section_id')) {
                $table->dropConstrainedForeignId('section_id');
            }
        });
    }
};

