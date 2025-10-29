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
        if (Schema::hasColumn('requisitions', 'bom_item_id')) {
            Schema::table('requisitions', function (Blueprint $table) {
                $table->unsignedBigInteger('bom_item_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('requisitions', 'bom_item_id')) {
            Schema::table('requisitions', function (Blueprint $table) {
                $table->unsignedBigInteger('bom_item_id')->nullable(false)->change();
            });
        }
    }
};
