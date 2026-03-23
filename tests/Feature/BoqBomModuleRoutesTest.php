<?php

namespace Tests\Feature;

use App\Models\BqDocument;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Blade module routes for Bills of Quantities and Bills of Materials (not the Vite dashboard bundle).
 */
class BoqBomModuleRoutesTest extends TestCase
{
    use RefreshDatabase;

    private function actingClientWithProject(): User
    {
        $user = User::factory()->create([
            'user_type' => 'user',
            'project_id' => null,
            'has_project' => 0,
            'parent_user_id' => null,
        ]);

        $project = Project::create([
            'name' => 'BoQ BoM Test Project',
            'user_id' => $user->id,
        ]);

        $user->update([
            'project_id' => $project->id,
            'has_project' => 1,
        ]);

        $user->projects()->syncWithoutDetaching([$project->id]);

        return $user->fresh();
    }

    public function test_boq_returns_200_for_client_with_active_project(): void
    {
        $user = $this->actingClientWithProject();

        $this->actingAs($user);

        $response = $this->get('/boq');

        $response->assertOk();
        $response->assertDontSee('No project is selected. Please choose a project first.', false);
        $response->assertSee('Bill of Quantities', false);
        $response->assertSee('BoQ BoM Test Project', false);
    }

    public function test_boms_returns_200_for_client_with_active_project(): void
    {
        $user = $this->actingClientWithProject();

        $this->actingAs($user);

        $response = $this->get('/boms');

        $response->assertOk();
        $response->assertDontSee('No project is selected. Please choose a project first.', false);
        $response->assertSee('Bill of Materials', false);
        $response->assertSee('BoQ BoM Test Project', false);
    }

    public function test_boq_redirects_to_dashboard_when_no_project_available(): void
    {
        $user = User::factory()->create([
            'user_type' => 'user',
            'project_id' => null,
            'has_project' => 0,
            'parent_user_id' => null,
        ]);

        $this->actingAs($user);

        $response = $this->get('/boq');

        $response->assertRedirect(route('dashboard'));
    }

    public function test_boms_redirects_to_dashboard_when_no_project_available(): void
    {
        $user = User::factory()->create([
            'user_type' => 'user',
            'project_id' => null,
            'has_project' => 0,
            'parent_user_id' => null,
        ]);

        $this->actingAs($user);

        $response = $this->get('/boms');

        $response->assertRedirect(route('dashboard'));
    }

    public function test_boq_and_boms_require_authentication(): void
    {
        $this->get('/boq')->assertRedirect(route('login'));
        $this->get('/boms')->assertRedirect(route('login'));
    }

    public function test_sub_boq_document_show_returns_200(): void
    {
        $user = $this->actingClientWithProject();
        $project = Project::query()->findOrFail($user->project_id);

        $master = BqDocument::create([
            'title' => 'Master BoQ',
            'description' => null,
            'user_id' => $user->id,
            'project_id' => $project->id,
            'parent_id' => null,
            'units' => 1,
        ]);

        $sub = BqDocument::create([
            'title' => 'Sub BoQ under test',
            'description' => 'Test document',
            'user_id' => $user->id,
            'project_id' => $project->id,
            'parent_id' => $master->id,
            'units' => 1,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('bq_documents.show', $sub));

        $response->assertOk();
        $response->assertSee('Sub BoQ under test', false);
    }
}
