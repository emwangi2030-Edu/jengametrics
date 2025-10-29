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
        if (Schema::hasColumn('units_of_measurement', 'created_at') || Schema::hasColumn('units_of_measurement', 'updated_at')) {
            Schema::table('units_of_measurement', function (Blueprint $table) {
                $table->dropTimestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('units_of_measurement', 'created_at') && ! Schema::hasColumn('units_of_measurement', 'updated_at')) {
            Schema::table('units_of_measurement', function (Blueprint $table) {
                $table->timestamps();
            });
        }
    }
};
