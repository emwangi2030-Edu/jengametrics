<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bom_items') && Schema::hasColumn('bom_items', 'project_id')) {
            Schema::table('bom_items', function (Blueprint $table) {
                $table->foreign('project_id')
                    ->references('id')
                    ->on('projects')
                    ->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('bom_labours') && Schema::hasColumn('bom_labours', 'project_id')) {
            Schema::table('bom_labours', function (Blueprint $table) {
                $table->foreign('project_id')
                    ->references('id')
                    ->on('projects')
                    ->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('bq_levels') && Schema::hasColumn('bq_levels', 'project_id')) {
            Schema::table('bq_levels', function (Blueprint $table) {
                $table->dropForeign(['project_id']);
                $table->foreign('project_id')
                    ->references('id')
                    ->on('projects')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bom_items') && Schema::hasColumn('bom_items', 'project_id')) {
            Schema::table('bom_items', function (Blueprint $table) {
                $table->dropForeign(['project_id']);
            });
        }

        if (Schema::hasTable('bom_labours') && Schema::hasColumn('bom_labours', 'project_id')) {
            Schema::table('bom_labours', function (Blueprint $table) {
                $table->dropForeign(['project_id']);
            });
        }

        if (Schema::hasTable('bq_levels') && Schema::hasColumn('bq_levels', 'project_id')) {
            Schema::table('bq_levels', function (Blueprint $table) {
                $table->dropForeign(['project_id']);
                $table->foreign('project_id')
                    ->references('id')
                    ->on('projects')
                    ->nullOnDelete();
            });
        }
    }
};
