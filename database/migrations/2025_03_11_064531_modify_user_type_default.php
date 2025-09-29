<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure no NULLs exist before making the column NOT NULL
        DB::table('users')->whereNull('user_type')->update(['user_type' => 'user']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type', 255)->default('user')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type', 255)->nullable()->default(null)->change();
        });
    }
};
