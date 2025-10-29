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
            if (!Schema::hasColumn('bq_sections', 'rate')) {
                $table->decimal('rate', 10, 2)->nullable()->after('item_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bq_sections', function (Blueprint $table) {
            if (Schema::hasColumn('bq_sections', 'rate')) {
                $table->dropColumn('rate');
            }
        });
    }
};
