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
        Schema::table('products', function (Blueprint $table) {
            $drops = [];
            if (Schema::hasColumn('products', 'rate')) {
                $drops[] = 'rate';
            }
            if (Schema::hasColumn('products', 'description')) {
                $drops[] = 'description';
            }

            if ($drops !== []) {
                $table->dropColumn($drops);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'rate')) {
                $table->string('rate')->nullable();
            }
            if (!Schema::hasColumn('products', 'description')) {
                $table->string('description')->nullable();
            }
        });
    }
};
