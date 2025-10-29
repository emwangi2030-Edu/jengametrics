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
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'project_id')) {
                $table->unsignedBigInteger('project_id')->nullable()->after('period_end');
                $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'project_id')) {
                $table->dropForeign(['project_id']);
                $table->dropColumn('project_id');
            }
        });
    }
};
