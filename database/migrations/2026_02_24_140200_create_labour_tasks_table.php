<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('labour_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('assignee_type', ['group', 'worker']);
            $table->foreignId('worker_group_id')->nullable()->constrained('worker_groups')->nullOnDelete();
            $table->foreignId('worker_id')->nullable()->constrained('workers')->nullOnDelete();
            $table->date('due_date')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'is_completed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labour_tasks');
    }
};

