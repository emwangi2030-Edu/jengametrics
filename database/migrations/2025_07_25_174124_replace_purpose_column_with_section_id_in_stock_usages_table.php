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
        Schema::table('stock_usages', function (Blueprint $table) { 
            // Add section_id column
            if (!Schema::hasColumn('stock_usages', 'section_id')) {
                $table->unsignedBigInteger('section_id')->after('quantity_used');

                // Set up foreign key to sections table
                $table->foreign('section_id')
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
        Schema::table('stock_usages', function (Blueprint $table) {
            // Drop the foreign key and section_id
            if (Schema::hasColumn('stock_usages', 'section_id')) {
                $table->dropForeign(['section_id']);
                $table->dropColumn('section_id');
            }
        });
    }
};
