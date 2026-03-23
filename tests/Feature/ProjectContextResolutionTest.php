<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectContextResolutionTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_project_accepts_owned_project_even_without_pivot_assignment(): void
    {
        $owner = User::factory()->create([
            'user_type' => 'user',
            'project_id' => null,
            'has_project' => 0,
        ]);

        $project = Project::create([
            'name' => 'Owned Project',
            'user_id' => $owner->id,
        ]);

        $owner->update([
            'project_id' => $project->id,
            'has_project' => 1,
        ]);

        // Simulate legacy data drift: owner has a project_id but missing project_user row.
        $owner->projects()->detach($project->id);

        $this->actingAs($owner);

        $resolved = get_project();

        $this->assertNotNull($resolved);
        $this->assertSame($project->id, $resolved->id);
    }

    public function test_get_project_resolves_sub_account_against_parent_scope(): void
    {
        $parent = User::factory()->create([
            'user_type' => 'user',
            'project_id' => null,
            'has_project' => 0,
        ]);

        $subAccount = User::factory()->create([
            'user_type' => 'user',
            'parent_user_id' => $parent->id,
            'project_id' => null,
            'has_project' => 0,
        ]);

        $project = Project::create([
            'name' => 'Parent Owned Project',
            'user_id' => $parent->id,
        ]);

        $subAccount->update([
            'project_id' => $project->id,
            'has_project' => 1,
        ]);

        // Ensure no direct sub-account assignment exists in pivot.
        $subAccount->projects()->detach($project->id);

        $this->actingAs($subAccount);

        $resolved = get_project();

        $this->assertNotNull($resolved);
        $this->assertSame($project->id, $resolved->id);
    }

    public function test_core_module_pages_do_not_bounce_back_to_dashboard_for_owned_project_context(): void
    {
        $user = User::factory()->create([
            'user_type' => 'user',
            'project_id' => null,
            'has_project' => 0,
        ]);

        $project = Project::create([
            'name' => 'Routing Smoke Project',
            'user_id' => $user->id,
        ]);

        $user->update([
            'project_id' => $project->id,
            'has_project' => 1,
        ]);

        // Keep the legacy mismatch condition to validate resolver hardening.
        $user->projects()->detach($project->id);

        $this->actingAs($user);

        $paths = [
            '/boq',
            '/boms',
            '/requisitions',
            '/materials',
            '/materials/delivered',
            '/materials/inventory',
            '/materials/usage',
            '/workers',
            '/cost-tracking',
            '/reports',
        ];

        foreach ($paths as $path) {
            $response = $this->get($path);
            $response->assertStatus(200, "Expected 200 for {$path}");
            $response->assertDontSee('No project is selected. Please choose a project first.', false);
        }
    }
}

