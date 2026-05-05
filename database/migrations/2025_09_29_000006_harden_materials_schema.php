<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Make quantity_in_stock decimal for fractional units
        // Use raw SQL to avoid doctrine/dbal requirement
        try {
            DB::statement('ALTER TABLE materials MODIFY quantity_in_stock DECIMAL(10,2) NOT NULL DEFAULT 0');
        } catch (Throwable $e) {
            // ignore if already decimal
        }

        Schema::table('materials', function (Blueprint $table) {
            // Indexes
            if (!self::hasIndex('materials', 'materials_product_id_index') && Schema::hasColumn('materials', 'product_id')) {
                $table->index('product_id');
            }
            if (!self::hasIndex('materials', 'materials_project_id_index') && Schema::hasColumn('materials', 'project_id')) {
                $table->index('project_id');
            }
            if (!self::hasIndex('materials', 'materials_supplier_id_index') && Schema::hasColumn('materials', 'supplier_id')) {
                $table->index('supplier_id');
            }
            if (Schema::hasColumn('materials', 'bom_item_id') && !self::hasIndex('materials', 'materials_bom_item_id_index')) {
                $table->index('bom_item_id');
            }
        });

        Schema::table('materials', function (Blueprint $table) {
            // FKs
            if (Schema::hasColumn('materials', 'product_id') && !self::hasForeign('materials', 'materials_product_id_foreign')) {
                $table->foreign('product_id', 'materials_product_id_foreign')->references('id')->on('products')->onDelete('set null');
            }
            if (Schema::hasColumn('materials', 'bom_item_id') && !self::hasForeign('materials', 'materials_bom_item_id_foreign')) {
                // Note: despite the name, this column stores ItemMaterial IDs
                $table->foreign('bom_item_id', 'materials_bom_item_id_foreign')->references('id')->on('item_materials')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('materials', function (Blueprint $table) {
            if (self::hasForeign('materials', 'materials_bom_item_id_foreign')) {
                $table->dropForeign('materials_bom_item_id_foreign');
            }
            if (self::hasForeign('materials', 'materials_product_id_foreign')) {
                $table->dropForeign('materials_product_id_foreign');
            }
            if (self::hasIndex('materials', 'materials_bom_item_id_index')) {
                $table->dropIndex('materials_bom_item_id_index');
            }
            if (self::hasIndex('materials', 'materials_supplier_id_index')) {
                $table->dropIndex('materials_supplier_id_index');
            }
            if (self::hasIndex('materials', 'materials_project_id_index')) {
                $table->dropIndex('materials_project_id_index');
            }
            if (self::hasIndex('materials', 'materials_product_id_index')) {
                $table->dropIndex('materials_product_id_index');
            }
        });

        // Optionally revert quantity_in_stock back to integer
        // DB::statement('ALTER TABLE materials MODIFY quantity_in_stock INT NOT NULL DEFAULT 0');
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
