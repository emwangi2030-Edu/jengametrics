<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('group_worker', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_group_id')->constrained('worker_groups')->cascadeOnDelete();
            $table->foreignId('worker_id')->constrained('workers')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['worker_group_id', 'worker_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('group_worker');
    }
};

