<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('parent_user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->boolean('can_manage_boq')->default(false)->after('has_project');
            $table->boolean('can_manage_materials')->default(false)->after('can_manage_boq');
            $table->boolean('can_manage_labour')->default(false)->after('can_manage_materials');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('parent_user_id');
            $table->dropColumn(['can_manage_boq', 'can_manage_materials', 'can_manage_labour']);
        });
    }
};
