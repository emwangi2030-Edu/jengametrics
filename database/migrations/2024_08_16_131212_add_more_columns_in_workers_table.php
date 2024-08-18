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
            $table->string('full_name');
            $table->string('id_number')->unique();
            $table->enum('job_category', [
                'Mason', 'Site Manager', 'Quantity Surveyor', 'Carpenter', 
                'Plumber', 'Helper/Casual', 'Painter', 'Sub Contractor', 
                'Electrician', 'Supervisor', 'Assistant Supervisor'
            ]);
            $table->enum('work_type', ['Under Contract', 'Casual']);
            $table->string('phone')->unique();
            $table->string('email')->unique();
            $table->string('details')->default('>');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workers', function (Blueprint $table) {
            $table->dropColumn('full_name');
            $table->dropColumn('id_number');
            $table->dropColumn('job_category');
            $table->dropColumn('work_type');
            $table->dropColumn('phone');
            $table->dropColumn('email');
            $table->dropColumn('details');
        });
    }
};
