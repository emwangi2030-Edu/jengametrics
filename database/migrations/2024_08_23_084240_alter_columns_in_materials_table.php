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
        Schema::table('materials', function (Blueprint $table) {
            // Drop the foreign key constraint before dropping the column
            $table->dropForeign(['supplier_id']);
        });

        Schema::table('materials', function (Blueprint $table) {
            // Temporarily drop the existing columns to reposition them
            $table->dropColumn(['supplier_id', 'supplier_contact']);
        });

        Schema::table('materials', function (Blueprint $table) {
            // Add the columns back in the desired positions
            $table->unsignedBigInteger('supplier_id')->nullable()->after('quantity_in_stock');
            $table->string('supplier_contact')->nullable()->after('supplier_id');

            // Add foreign key constraint back
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            // Drop the foreign key constraint before dropping the column
            $table->dropForeign(['supplier_id']);
        });

        Schema::table('materials', function (Blueprint $table) {
            // Drop the columns in the current position
            $table->dropColumn(['supplier_id', 'supplier_contact']);
        });

        Schema::table('materials', function (Blueprint $table) {
            // Add the columns back in the original positions
            $table->unsignedBigInteger('supplier_id')->nullable()->after('unit_of_measure');
            $table->string('supplier_contact')->nullable()->after('supplier_id');

            // Re-add foreign key constraint
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });
    }
};
