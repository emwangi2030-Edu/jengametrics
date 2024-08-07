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
// Create a migration for BOM items
Schema::create('bom_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('bom_id')->constrained()->onDelete('cascade');
    $table->foreignId('bq_section_id')->constrained('bq_sections')->onDelete('cascade');
    $table->foreignId('bq_item_id')->constrained('bq_items')->onDelete('cascade');
    $table->string('item_description');
    $table->float('quantity');
    $table->string('unit');
    $table->float('rate');
    $table->float('amount');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bom_items');
    }
};
