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
    $table->unsignedBigInteger('section_id');
    $table->unsignedBigInteger('item_id');
    $table->unsignedBigInteger('item_material_id');
    $table->decimal('quantity', 10, 2);
    $table->decimal('rate', 10, 2);
    $table->decimal('amount', 10, 2);
    $table->unsignedBigInteger('project_id');
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
