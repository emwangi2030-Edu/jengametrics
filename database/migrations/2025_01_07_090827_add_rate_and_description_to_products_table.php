<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRateAndDescriptionToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'rate')) {
                $table->decimal('rate', 10, 2)->default(0.00)->after('name');
            }

            if (!Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable()->after('rate');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $drops = [];
            if (Schema::hasColumn('products', 'rate')) {
                $drops[] = 'rate';
            }
            if (Schema::hasColumn('products', 'description')) {
                $drops[] = 'description';
            }

            if ($drops !== []) {
                $table->dropColumn($drops);
            }
        });
    }
}
