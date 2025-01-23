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
        Schema::table('elements', function (Blueprint $table) {
            // Drop the existing foreign key
            $table->dropForeign(['section_id']);

            // Recreate the foreign key with ON DELETE CASCADE
            $table->foreign('section_id')
                  ->references('id')
                  ->on('sections')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elements', function (Blueprint $table) {
            // Drop the cascading foreign key
            $table->dropForeign(['section_id']);

            // Recreate the original foreign key (without cascade)
            $table->foreign('section_id')
                  ->references('id')
                  ->on('sections');
        });
    }
};
