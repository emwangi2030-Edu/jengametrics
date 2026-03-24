<?php

namespace Tests\Feature\Api\V1;

use App\Models\BqDocument;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReadEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_summary_returns_project_scoped_payload(): void
    {
        $user = User::factory()->create(['user_type' => 'user']);
        $project = Project::create([
            'name' => 'Summary Project',
            'user_id' => $user->id,
        ]);
        $user->project_id = $project->id;
        $user->has_project = 1;
        $user->save();
        $user->projects()->syncWithoutDetaching([$project->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/dashboard/summary');

        $response->assertOk()
            ->assertJsonPath('data.project.id', $project->id)
            ->assertJsonStructure([
                'data' => [
                    'project',
                    'kpis',
                    'progress',
                    'cost_breakdown',
                ],
            ]);
    }

    public function test_boq_documents_returns_only_documents_for_active_project(): void
    {
        $user = User::factory()->create(['user_type' => 'user']);
        $project = Project::create(['name' => 'BoQ API Project', 'user_id' => $user->id]);
        $otherProject = Project::create(['name' => 'Other API Project', 'user_id' => $user->id]);
        $user->project_id = $project->id;
        $user->has_project = 1;
        $user->save();
        $user->projects()->syncWithoutDetaching([$project->id, $otherProject->id]);

        $masterA = BqDocument::create(['title' => 'Master A', 'project_id' => $project->id, 'user_id' => $user->id]);
        BqDocument::create(['title' => 'Sub A', 'project_id' => $project->id, 'user_id' => $user->id, 'parent_id' => $masterA->id]);
        $masterB = BqDocument::create(['title' => 'Master B', 'project_id' => $otherProject->id, 'user_id' => $user->id]);
        BqDocument::create(['title' => 'Sub B', 'project_id' => $otherProject->id, 'user_id' => $user->id, 'parent_id' => $masterB->id]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/boq/documents');

        $response->assertOk();
        $items = $response->json('data.items');
        $this->assertCount(1, $items);
        $this->assertSame('Sub A', $items[0]['title']);
    }
}

