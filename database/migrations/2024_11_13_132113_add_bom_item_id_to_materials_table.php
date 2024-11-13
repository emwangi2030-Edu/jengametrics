<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBomItemIdToMaterialsTable extends Migration
{
    public function up()
    {
        Schema::table('materials', function (Blueprint $table) {
            // Add the bom_item_id column (assuming it's an unsigned integer and nullable)
            $table->unsignedBigInteger('bom_item_id')->nullable()->after('id'); // Adjust the position if necessary
        });
    }

    public function down()
    {
        Schema::table('materials', function (Blueprint $table) {
            // Drop the bom_item_id column if this migration is rolled back
            $table->dropColumn('bom_item_id');
        });
    }
}
