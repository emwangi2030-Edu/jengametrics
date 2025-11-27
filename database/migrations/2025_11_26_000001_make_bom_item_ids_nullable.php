<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bom_items', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->nullable()->change();
        });

        Schema::table('bom_labours', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // Revert to NOT NULL; uses 0 as fallback to avoid null violations on rollback
        Schema::table('bom_items', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->default(0)->change();
        });

        Schema::table('bom_labours', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->default(0)->change();
        });
    }
};
