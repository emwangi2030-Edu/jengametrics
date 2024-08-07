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
            $table->foreignId('bq_document_id')->constrained('bq_documents')->onDelete('cascade');
            $table->string('section_name');
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bq_sections');
    }
}
