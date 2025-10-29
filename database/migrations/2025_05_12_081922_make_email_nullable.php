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
        if (Schema::hasColumn('workers', 'email')) {
            Schema::table('workers', function (Blueprint $table) {
                try {
                    $table->dropUnique('workers_email_unique');
                } catch (\Throwable $e) {
                    // Index already removed or does not exist.
                }
            });

            Schema::table('workers', function (Blueprint $table) {
                $table->string('email')->nullable()->change();
                $table->unique('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('workers', 'email')) {
            Schema::table('workers', function (Blueprint $table) {
                try {
                    $table->dropUnique('workers_email_unique');
                } catch (\Throwable $e) {
                    // Index already removed or does not exist.
                }
            });

            Schema::table('workers', function (Blueprint $table) {
                $table->string('email')->nullable(false)->change();
                $table->unique('email');
            });
        }
    }
};
