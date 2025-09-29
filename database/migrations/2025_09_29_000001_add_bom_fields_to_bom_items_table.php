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
            if (!Schema::hasColumn('bom_items', 'bom_id')) {
                $table->unsignedBigInteger('bom_id')->nullable()->after('id');
                // Optional FK: $table->foreign('bom_id')->references('id')->on('boms')->onDelete('cascade');
            }
            if (!Schema::hasColumn('bom_items', 'item_description')) {
                $table->string('item_description')->nullable()->after('item_id');
            }
            if (!Schema::hasColumn('bom_items', 'unit')) {
                $table->string('unit')->nullable()->after('item_description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bom_items', function (Blueprint $table) {
            if (Schema::hasColumn('bom_items', 'unit')) {
                $table->dropColumn('unit');
            }
            if (Schema::hasColumn('bom_items', 'item_description')) {
                $table->dropColumn('item_description');
            }
            if (Schema::hasColumn('bom_items', 'bom_id')) {
                $table->dropColumn('bom_id');
            }
        });
    }
};

