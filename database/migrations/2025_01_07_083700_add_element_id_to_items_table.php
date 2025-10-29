<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddElementIdToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            if (!Schema::hasColumn('items', 'element_id')) {
                $table->unsignedBigInteger('element_id')->nullable()->after('id');
            }

            // Optional: Add foreign key constraint if element_id references another table
            // $table->foreign('element_id')->references('id')->on('elements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'element_id')) {
                $table->dropColumn('element_id');
            }
        });
    }
}
