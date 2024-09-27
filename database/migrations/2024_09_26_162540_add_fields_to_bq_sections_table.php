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
            $table->unsignedBigInteger('section_id')->after('id')->nullable();
            $table->unsignedBigInteger('element_id')->after('section_id')->nullable();
            $table->unsignedBigInteger('sub_element_id')->after('element_id')->nullable();

            // Optionally, you can add foreign keys if the related tables exist
            // $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            // $table->foreign('element_id')->references('id')->on('elements')->onDelete('cascade');
            // $table->foreign('sub_element_id')->references('id')->on('sub_elements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bq_sections', function (Blueprint $table) {
            //
        });
    }
};
