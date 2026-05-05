<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Make columns nullable before cleanup
        Schema::table('bom_labours', function (Blueprint $table) {
            if (Schema::hasColumn('bom_labours', 'item_id')) {
                $table->unsignedBigInteger('item_id')->nullable()->change();
            }
            if (Schema::hasColumn('bom_labours', 'bq_section_id')) {
                $table->unsignedBigInteger('bq_section_id')->nullable()->change();
            }
            if (Schema::hasColumn('bom_labours', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable()->change();
            }
        });

        // Cleanup orphan references (PostgreSQL-compatible; avoid MySQL UPDATE ... LEFT JOIN).
        DB::statement('UPDATE bom_labours SET item_id = NULL WHERE item_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM items i WHERE i.id = bom_labours.item_id)');
        DB::statement('UPDATE bom_labours SET bq_section_id = NULL WHERE bq_section_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM bq_sections bs WHERE bs.id = bom_labours.bq_section_id)');
        DB::statement('UPDATE bom_labours SET section_id = NULL WHERE section_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM sections s WHERE s.id = bom_labours.section_id)');

        // Indexes
        Schema::table('bom_labours', function (Blueprint $table) {
            if (!self::hasIndex('bom_labours', 'bom_labours_section_id_index')) {
                $table->index('section_id');
            }
            if (!self::hasIndex('bom_labours', 'bom_labours_project_id_index')) {
                $table->index('project_id');
            }
            if (Schema::hasColumn('bom_labours', 'item_id') && !self::hasIndex('bom_labours', 'bom_labours_item_id_index')) {
                $table->index('item_id');
            }
            if (Schema::hasColumn('bom_labours', 'bq_section_id') && !self::hasIndex('bom_labours', 'bom_labours_bq_section_id_index')) {
                $table->index('bq_section_id');
            }
        });

        // Foreign Keys
        Schema::table('bom_labours', function (Blueprint $table) {
            if (Schema::hasColumn('bom_labours', 'item_id') && !self::hasForeign('bom_labours', 'bom_labours_item_id_foreign')) {
                $table->foreign('item_id', 'bom_labours_item_id_foreign')
                    ->references('id')->on('items')
                    ->onDelete('set null');
            }
            if (Schema::hasColumn('bom_labours', 'section_id') && !self::hasForeign('bom_labours', 'bom_labours_section_id_foreign')) {
                $table->foreign('section_id', 'bom_labours_section_id_foreign')
                    ->references('id')->on('sections')
                    ->onDelete('cascade');
            }
            if (Schema::hasColumn('bom_labours', 'bq_section_id') && !self::hasForeign('bom_labours', 'bom_labours_bq_section_id_foreign')) {
                $table->foreign('bq_section_id', 'bom_labours_bq_section_id_foreign')
                    ->references('id')->on('bq_sections')
                    ->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bom_labours', function (Blueprint $table) {
            if (self::hasForeign('bom_labours', 'bom_labours_bq_section_id_foreign')) {
                $table->dropForeign('bom_labours_bq_section_id_foreign');
            }
            if (self::hasForeign('bom_labours', 'bom_labours_section_id_foreign')) {
                $table->dropForeign('bom_labours_section_id_foreign');
            }
            if (self::hasForeign('bom_labours', 'bom_labours_item_id_foreign')) {
                $table->dropForeign('bom_labours_item_id_foreign');
            }

            if (self::hasIndex('bom_labours', 'bom_labours_bq_section_id_index')) {
                $table->dropIndex('bom_labours_bq_section_id_index');
            }
            if (self::hasIndex('bom_labours', 'bom_labours_item_id_index')) {
                $table->dropIndex('bom_labours_item_id_index');
            }
            if (self::hasIndex('bom_labours', 'bom_labours_project_id_index')) {
                $table->dropIndex('bom_labours_project_id_index');
            }
            if (self::hasIndex('bom_labours', 'bom_labours_section_id_index')) {
                $table->dropIndex('bom_labours_section_id_index');
            }
        });
    }

    private static function hasIndex(string $table, string $indexName): bool
    {
        $connection = Schema::getConnection();
        if ($connection->getDriverName() === 'pgsql') {
            return DB::selectOne(
                'select 1 as x from pg_indexes where schemaname = any (current_schemas(false)) and tablename = ? and indexname = ? limit 1',
                [$table, $indexName]
            ) !== null;
        }

        $schema = config('database.connections.'.config('database.default').'.database');

        return DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', $schema)
            ->where('TABLE_NAME', $table)
            ->where('INDEX_NAME', $indexName)
            ->exists();
    }

    private static function hasForeign(string $table, string $fkName): bool
    {
        $connection = Schema::getConnection();
        if ($connection->getDriverName() === 'pgsql') {
            return DB::selectOne(
                'select 1 as x from information_schema.table_constraints where table_schema = any (current_schemas(false)) and table_name = ? and constraint_type = ? and constraint_name = ? limit 1',
                [$table, 'FOREIGN KEY', $fkName]
            ) !== null;
        }

        $schema = config('database.connections.'.config('database.default').'.database');

        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', $schema)
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->where('CONSTRAINT_NAME', $fkName)
            ->exists();
    }
};

