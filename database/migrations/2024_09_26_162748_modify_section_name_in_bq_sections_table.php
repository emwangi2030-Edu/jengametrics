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
            // Allow null values for section_name
            $table->string('section_name')->nullable()->change();
            // Alternatively, set a default value:
            // $table->string('section_name')->default('Default Section Name')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
            Schema::table('bq_sections', function (Blueprint $table) {
            // Restore it to NOT NULL if necessary
            $table->string('section_name')->nullable(false)->change();
        });
    }
};
