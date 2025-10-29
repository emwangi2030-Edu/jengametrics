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
        Schema::table('workers', function (Blueprint $table) {
            if (!Schema::hasColumn('workers', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('mode_of_payment');
            }
            if (!Schema::hasColumn('workers', 'bank_account')) {
                $table->string('bank_account')->nullable()->after('bank_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            $drops = [];
            if (Schema::hasColumn('workers', 'bank_name')) {
                $drops[] = 'bank_name';
            }
            if (Schema::hasColumn('workers', 'bank_account')) {
                $drops[] = 'bank_account';
            }

            if ($drops !== []) {
                $table->dropColumn($drops);
            }
        });
    }
};
