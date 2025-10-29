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
        Schema::table('requisitions', function (Blueprint $table) {
            if (!Schema::hasColumn('requisitions', 'extra_material_name')) {
                $table->string('extra_material_name')->nullable()->after('bom_item_id');
            }

            if (!Schema::hasColumn('requisitions', 'extra_unit')) {
                $table->string('extra_unit')->nullable()->after('extra_material_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            if (Schema::hasColumn('requisitions', 'extra_material_name')) {
                $table->dropColumn('extra_material_name');
            }

            if (Schema::hasColumn('requisitions', 'extra_unit')) {
                $table->dropColumn('extra_unit');
            }
        });
    }
};
