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
        Schema::table('bom_items', function (Blueprint $table) {
            if (!Schema::hasColumn('bom_items', 'bq_section_id')) {
                $table->unsignedBigInteger('bq_section_id')->nullable()->after('project_id');
                // Optional FK for referential integrity:
                // $table->foreign('bq_section_id')->references('id')->on('bq_sections')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bom_items', function (Blueprint $table) {
            if (Schema::hasColumn('bom_items', 'bq_section_id')) {
                // $table->dropForeign(['bq_section_id']); // if FK was added
                $table->dropColumn('bq_section_id');
            }
        });
    }
};

