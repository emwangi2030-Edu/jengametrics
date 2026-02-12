<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['project_id', 'user_id']);
        });

        $projects = DB::table('projects')->select('id', 'user_id')->whereNotNull('user_id')->get();
        foreach ($projects as $project) {
            DB::table('project_user')->insertOrIgnore([
                'project_id' => $project->id,
                'user_id' => $project->user_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $subUsers = DB::table('users')->select('id', 'parent_user_id')->whereNotNull('parent_user_id')->get();
        foreach ($subUsers as $subUser) {
            $parentProjectIds = DB::table('projects')
                ->where('user_id', $subUser->parent_user_id)
                ->pluck('id');

            foreach ($parentProjectIds as $projectId) {
                DB::table('project_user')->insertOrIgnore([
                    'project_id' => $projectId,
                    'user_id' => $subUser->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('project_user');
    }
};
