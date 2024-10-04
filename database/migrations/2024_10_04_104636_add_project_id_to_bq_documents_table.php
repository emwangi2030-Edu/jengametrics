<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('bq_documents', function (Blueprint $table) {
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('bq_documents', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });
    }
};
