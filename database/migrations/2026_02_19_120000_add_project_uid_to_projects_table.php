<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (!Schema::hasColumn('projects', 'project_uid')) {
                $table->string('project_uid', 100)->nullable()->after('id');
                $table->unique('project_uid');
            }
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            if (Schema::hasColumn('projects', 'project_uid')) {
                $table->dropUnique('projects_project_uid_unique');
                $table->dropColumn('project_uid');
            }
        });
    }
};
