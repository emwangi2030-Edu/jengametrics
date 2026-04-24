<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('materials', 'requisition_id')) {
            Schema::table('materials', function (Blueprint $table) {
                $table->unsignedBigInteger('requisition_id')->nullable()->after('id');
            });
        }

        // Older schema briefly had a unique requisition_id, which blocks partial deliveries.
        if (self::hasIndex('materials', 'materials_requisition_id_unique')) {
            Schema::table('materials', function (Blueprint $table) {
                $table->dropUnique('materials_requisition_id_unique');
            });
        }

        Schema::table('materials', function (Blueprint $table) {
            if (!self::hasIndex('materials', 'materials_requisition_id_index')) {
                $table->index('requisition_id');
            }

            if (!self::hasForeign('materials', 'materials_requisition_id_foreign')) {
                $table->foreign('requisition_id', 'materials_requisition_id_foreign')
                    ->references('id')
                    ->on('requisitions')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (self::hasForeign('materials', 'materials_requisition_id_foreign')) {
                $table->dropForeign('materials_requisition_id_foreign');
            }

            if (self::hasIndex('materials', 'materials_requisition_id_index')) {
                $table->dropIndex('materials_requisition_id_index');
            }

            if (Schema::hasColumn('materials', 'requisition_id')) {
                $table->dropColumn('requisition_id');
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

        $schema = config('database.connections.' . config('database.default') . '.database');

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

        $schema = config('database.connections.' . config('database.default') . '.database');

        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', $schema)
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->where('CONSTRAINT_NAME', $fkName)
            ->exists();
    }
};
