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
        Schema::table('item_unit_of_measurements', function (Blueprint $table) {
            if (!Schema::hasColumn('item_unit_of_measurements', 'abbrev')) {
                $table->string('abbrev')->nullable()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item_unit_of_measurements', function (Blueprint $table) {
            if (Schema::hasColumn('item_unit_of_measurements', 'abbrev')) {
                $table->dropColumn('abbrev');
            }
        });
    }
};
