<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeSubElementIdNullableInItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('items', 'sub_element_id')) {
            Schema::table('items', function (Blueprint $table) {
                $table->unsignedBigInteger('sub_element_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('items', 'sub_element_id')) {
            Schema::table('items', function (Blueprint $table) {
                $table->unsignedBigInteger('sub_element_id')->nullable(false)->change();
            });
        }
    }
}
