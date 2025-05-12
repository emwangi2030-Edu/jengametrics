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
        Schema::table('workers', function (Blueprint $table) {
            // Drop the existing unique index
            $table->dropUnique('workers_email_unique');
        });

        Schema::table('workers', function (Blueprint $table) {
            // Make email nullable and re-add unique index
            $table->string('email')->nullable()->change();
            $table->unique('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            // Drop current unique index
            $table->dropUnique('workers_email_unique');
        });

        Schema::table('workers', function (Blueprint $table) {
            // Revert email to NOT NULL and re-add unique index
            $table->string('email')->nullable(false)->change();
            $table->unique('email');
        });
    }
};
