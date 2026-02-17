<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'address')) {
                $table->string('address')->nullable()->after('name');
            }
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'address')) {
                $table->dropColumn('address');
            }
        });
    }
};
