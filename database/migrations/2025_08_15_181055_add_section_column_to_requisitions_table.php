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
            if (!Schema::hasColumn('requisitions', 'section')) {
                $table->unsignedBigInteger('section')->after('quantity_requested');
                $table->foreign('section')
                    ->references('id')
                    ->on('sections')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            if (Schema::hasColumn('requisitions', 'section')) {
                $table->dropForeign(['section']);
                $table->dropColumn('section');
            }
        });
    }
};
