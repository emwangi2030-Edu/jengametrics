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
        Schema::table('bq_sections', function (Blueprint $table) {
            $table->renameColumn('section_name', 'item_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bq_sections', function (Blueprint $table) {
            $table->renameColumn('section_name', 'item_name');
        });
    }
};
