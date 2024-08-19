<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBqItemsTable extends Migration
{
    public function up()
    {
        Schema::create('bq_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bq_document_id')->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('bq_section_id')->constrained()->onDelete('cascade');
            $table->string('item_description');
            $table->integer('quantity');
            $table->string('unit');
            $table->decimal('rate', 15, 2);
            $table->decimal('amount', 15, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bq_items');
    }
}
