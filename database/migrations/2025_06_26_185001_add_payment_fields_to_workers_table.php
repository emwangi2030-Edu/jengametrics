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
            if (!Schema::hasColumn('workers', 'payment_amount')) {
                $table->decimal('payment_amount', 10, 2)->nullable()->after('email');
            }
            if (!Schema::hasColumn('workers', 'payment_frequency')) {
                $table->string('payment_frequency')->nullable()->after('payment_amount');
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
            if (Schema::hasColumn('workers', 'payment_amount')) {
                $drops[] = 'payment_amount';
            }
            if (Schema::hasColumn('workers', 'payment_frequency')) {
                $drops[] = 'payment_frequency';
            }

            if ($drops !== []) {
                $table->dropColumn($drops);
            }
        });
    }
};
