<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBomLaboursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bom_labours', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity');
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('item_id');
            $table->decimal('rate', 15, 2);
            $table->unsignedBigInteger('bq_section_id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('project_id');
            $table->timestamps();

            // Foreign key constraints (if needed)
            // $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            // $table->foreign('bq_section_id')->references('id')->on('bq_sections')->onDelete('cascade');
            // $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            // $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bom_labours');
    }
}
