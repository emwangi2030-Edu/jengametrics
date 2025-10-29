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
            if (!Schema::hasColumn('bq_sections', 'quantity')) {
                $table->integer('quantity')->default(0)->after('rate');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bq_sections', function (Blueprint $table) {
            if (Schema::hasColumn('bq_sections', 'quantity')) {
                $table->dropColumn('quantity');
            }
        });
    }
};
