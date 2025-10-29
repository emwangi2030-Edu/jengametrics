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
        if (Schema::hasColumn('requisitions', 'section') && ! Schema::hasColumn('requisitions', 'section_id')) {
            Schema::table('requisitions', function (Blueprint $table) {
                $table->renameColumn('section', 'section_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('requisitions', 'section_id') && ! Schema::hasColumn('requisitions', 'section')) {
            Schema::table('requisitions', function (Blueprint $table) {
                $table->renameColumn('section_id', 'section');
            });
        }
    }
};
