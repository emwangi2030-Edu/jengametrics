<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'parent_user_id')) {
                $table->dropConstrainedForeignId('parent_user_id');
                $table->foreignId('parent_user_id')
                    ->nullable()
                    ->constrained('users')
                    ->cascadeOnDelete();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'parent_user_id')) {
                $table->dropConstrainedForeignId('parent_user_id');
                $table->foreignId('parent_user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }
};
