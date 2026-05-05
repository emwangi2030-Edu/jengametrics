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
        // First, ensure columns are nullable before nullifying data
        Schema::table('bom_items', function (Blueprint $table) {
            if (Schema::hasColumn('bom_items', 'item_material_id')) {
                $table->unsignedBigInteger('item_material_id')->nullable()->change();
            }
            if (Schema::hasColumn('bom_items', 'product_id')) {
                $table->unsignedBigInteger('product_id')->nullable()->change();
            }
            if (Schema::hasColumn('bom_items', 'bq_section_id')) {
                $table->unsignedBigInteger('bq_section_id')->nullable()->change();
            }
            if (Schema::hasColumn('bom_items', 'section_id')) {
                $table->unsignedBigInteger('section_id')->nullable()->change();
            }
        });

        // Normalize data to satisfy new constraints (after columns are nullable)
        // Nullify orphaned item_material_id and product_id values
        DB::statement('UPDATE bom_items bi LEFT JOIN item_materials im ON im.id = bi.item_material_id SET bi.item_material_id = NULL WHERE bi.item_material_id IS NOT NULL AND im.id IS NULL');
        DB::statement('UPDATE bom_items bi LEFT JOIN products p ON p.id = bi.product_id SET bi.product_id = NULL WHERE bi.product_id IS NOT NULL AND p.id IS NULL');
        // Nullify orphaned section_id values (legacy data)
        DB::statement('UPDATE bom_items bi LEFT JOIN sections s ON s.id = bi.section_id SET bi.section_id = NULL WHERE bi.section_id IS NOT NULL AND s.id IS NULL');

        Schema::table('bom_items', function (Blueprint $table) {
            // Indexes for performance
            if (!self::hasIndex('bom_items', 'bom_items_section_id_index')) {
                $table->index('section_id');
            }
            if (!self::hasIndex('bom_items', 'bom_items_project_id_index')) {
                $table->index('project_id');
            }
            if (Schema::hasColumn('bom_items', 'product_id') && !self::hasIndex('bom_items', 'bom_items_product_id_index')) {
                $table->index('product_id');
            }
            if (Schema::hasColumn('bom_items', 'item_material_id') && !self::hasIndex('bom_items', 'bom_items_item_material_id_index')) {
                $table->index('item_material_id');
            }
            if (Schema::hasColumn('bom_items', 'bq_section_id') && !self::hasIndex('bom_items', 'bom_items_bq_section_id_index')) {
                $table->index('bq_section_id');
            }
        });

        // Foreign keys (added after indexes)
        Schema::table('bom_items', function (Blueprint $table) {
            // product_id -> products.id (nullable, SET NULL on delete)
            if (Schema::hasColumn('bom_items', 'product_id') && !self::hasForeign('bom_items', 'bom_items_product_id_foreign')) {
                $table->foreign('product_id', 'bom_items_product_id_foreign')
                    ->references('id')->on('products')
                    ->onDelete('set null');
            }
            // item_material_id -> item_materials.id (cascade)
            if (Schema::hasColumn('bom_items', 'item_material_id') && !self::hasForeign('bom_items', 'bom_items_item_material_id_foreign')) {
                $table->foreign('item_material_id', 'bom_items_item_material_id_foreign')
                    ->references('id')->on('item_materials')
                    ->onDelete('set null');
            }
            // section_id -> sections.id (cascade; nullable allowed)
            if (Schema::hasColumn('bom_items', 'section_id') && !self::hasForeign('bom_items', 'bom_items_section_id_foreign')) {
                $table->foreign('section_id', 'bom_items_section_id_foreign')
                    ->references('id')->on('sections')
                    ->onDelete('cascade');
            }
            // bq_section_id -> bq_sections.id (nullable, SET NULL on delete)
            if (Schema::hasColumn('bom_items', 'bq_section_id') && !self::hasForeign('bom_items', 'bom_items_bq_section_id_foreign')) {
                $table->foreign('bq_section_id', 'bom_items_bq_section_id_foreign')
                    ->references('id')->on('bq_sections')
                    ->onDelete('set null');
            }
            // Note: project_id left as indexed only to avoid accidental FK failures if projects are managed separately.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bom_items', function (Blueprint $table) {
            // Drop FKs if exist
            if (self::hasForeign('bom_items', 'bom_items_bq_section_id_foreign')) {
                $table->dropForeign('bom_items_bq_section_id_foreign');
            }
            if (self::hasForeign('bom_items', 'bom_items_section_id_foreign')) {
                $table->dropForeign('bom_items_section_id_foreign');
            }
            if (self::hasForeign('bom_items', 'bom_items_item_material_id_foreign')) {
                $table->dropForeign('bom_items_item_material_id_foreign');
            }
            if (self::hasForeign('bom_items', 'bom_items_product_id_foreign')) {
                $table->dropForeign('bom_items_product_id_foreign');
            }

            // Drop indexes
            if (self::hasIndex('bom_items', 'bom_items_bq_section_id_index')) {
                $table->dropIndex('bom_items_bq_section_id_index');
            }
            if (self::hasIndex('bom_items', 'bom_items_item_material_id_index')) {
                $table->dropIndex('bom_items_item_material_id_index');
            }
            if (self::hasIndex('bom_items', 'bom_items_product_id_index')) {
                $table->dropIndex('bom_items_product_id_index');
            }
            if (self::hasIndex('bom_items', 'bom_items_project_id_index')) {
                $table->dropIndex('bom_items_project_id_index');
            }
            if (self::hasIndex('bom_items', 'bom_items_section_id_index')) {
                $table->dropIndex('bom_items_section_id_index');
            }
        });
    }

    // Helpers to check existing keys/indexes
    private static function hasIndex(string $table, string $indexName): bool
    {
        $conn = Schema::getConnection();
        if ($conn->getDriverName() === 'pgsql') {
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
        $conn = Schema::getConnection();
        if ($conn->getDriverName() === 'pgsql') {
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
