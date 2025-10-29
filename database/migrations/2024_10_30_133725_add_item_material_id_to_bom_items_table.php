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
            if (!Schema::hasColumn('bom_items', 'item_material_id')) {
                $table->unsignedBigInteger('item_material_id')->after('item_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bom_items', function (Blueprint $table) {
            if (Schema::hasColumn('bom_items', 'item_material_id')) {
                $table->dropColumn('item_material_id');
            }
        });
    }
};
