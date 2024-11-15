<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductIdToMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            // Adding the product_id column as an unsigned big integer and making it nullable (adjust as needed)
            $table->unsignedBigInteger('product_id')->nullable()->after('id');

            // Optional: Add a foreign key constraint if you want to reference the 'products' table
            // Uncomment the line below if 'products' table exists and you want a foreign key reference
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
        Schema::table('materials', function (Blueprint $table) {
            // Dropping the product_id column
            $table->dropColumn('product_id');

            // If you added a foreign key constraint, also drop it
            // $table->dropForeign(['product_id']);
        });
    }
}
