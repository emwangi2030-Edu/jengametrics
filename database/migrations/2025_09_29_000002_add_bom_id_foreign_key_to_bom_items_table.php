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
        Schema::table('bom_items', function (Blueprint $table) {
            // Ensure the column exists
            if (!Schema::hasColumn('bom_items', 'bom_id')) {
                $table->unsignedBigInteger('bom_id')->nullable()->after('id');
            }

            // Ensure an index exists to avoid auto-created index name collisions
            if (!self::hasIndex('bom_items', 'bom_items_bom_id_index')) {
                $table->index('bom_id');
            }
        });

        Schema::table('bom_items', function (Blueprint $table) {
            // Add the foreign key constraint if not already present (by any common name)
            if (!self::hasForeign('bom_items', 'bom_items_bom_id_foreign') && !self::hasForeign('bom_items', 'fk_bom_items_bom_id')) {
                $table->foreign('bom_id', 'fk_bom_items_bom_id')
                    ->references('id')->on('boms')
                    ->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bom_items', function (Blueprint $table) {
            // Drop FK by either possible name
            if (self::hasForeign('bom_items', 'fk_bom_items_bom_id')) {
                $table->dropForeign('fk_bom_items_bom_id');
            }
            if (self::hasForeign('bom_items', 'bom_items_bom_id_foreign')) {
                $table->dropForeign('bom_items_bom_id_foreign');
            }

            // Optionally drop index if we created it
            if (self::hasIndex('bom_items', 'bom_items_bom_id_index')) {
                $table->dropIndex('bom_items_bom_id_index');
            }
        });
    }

    private static function hasIndex(string $table, string $indexName): bool
    {
        $schema = config('database.connections.'.config('database.default').'.database');
        return DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', $schema)
            ->where('TABLE_NAME', $table)
            ->where('INDEX_NAME', $indexName)
            ->exists();
    }

    private static function hasForeign(string $table, string $fkName): bool
    {
        $schema = config('database.connections.'.config('database.default').'.database');
        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', $schema)
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_TYPE', 'FOREIGN KEY')
            ->where('CONSTRAINT_NAME', $fkName)
            ->exists();
    }
};
