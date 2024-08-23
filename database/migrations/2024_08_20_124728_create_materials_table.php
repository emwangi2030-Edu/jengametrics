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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('unit_price', 8, 2);
            $table->string('unit_of_measure');
            $table->unsignedBigInteger('supplier_id'); // Ensure this matches the suppliers table
            $table->integer('quantity_in_stock')->default(0);
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
