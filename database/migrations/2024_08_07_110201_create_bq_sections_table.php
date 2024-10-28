<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBqSectionsTable extends Migration
{
    public function up()
    {
        Schema::create('bq_sections', function (Blueprint $table) {
            $table->id();
            $table->string('project_id')->nullable();
            $table->foreignId('bq_document_id')->constrained('bq_documents')->onDelete('cascade')->nullable();
            $table->string('section_name');
            $table->text(column: 'details')->nullable();
            $table->integer(column: 'item_id')->nullable();
            $table->integer('quantity')->nullable();
            $table->decimal('rate', 15, 2)->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bq_sections');
    }
}
