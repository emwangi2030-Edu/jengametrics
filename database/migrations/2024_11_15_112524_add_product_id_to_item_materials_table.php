<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductIdToItemMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_materials', function (Blueprint $table) {
            // Adding the product_id column (assuming it is an unsigned integer and references the id of a product)
            $table->unsignedBigInteger('product_id')->nullable()->after('id'); // Adjust the position using 'after()' as needed

            // If you want to add a foreign key constraint, uncomment the line below and adjust 'products' to your table name
            // $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_materials', function (Blueprint $table) {
            // Dropping the product_id column
            $table->dropColumn('product_id');

            // If you added a foreign key constraint, you can also drop it here
            // $table->dropForeign(['product_id']);
        });
    }
}

