<?php

namespace Tests\Feature\Api\V1;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthAndProjectContextTest extends TestCase
{
    use RefreshDatabase;

    public function test_v1_login_returns_access_token_and_user_payload(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('secret123'),
            'user_type' => 'user',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'token_type',
                    'user' => ['id', 'name', 'email', 'user_type', 'project_id'],
                ],
                'message',
            ]);
    }

    public function test_v1_me_requires_authentication(): void
    {
        $response = $this->getJson('/api/v1/auth/me');

        $response->assertStatus(401)
            ->assertJsonPath('error.code', 'UNAUTHENTICATED');
    }

    public function test_active_project_endpoint_resolves_owned_project_for_user(): void
    {
        $user = User::factory()->create([
            'user_type' => 'user',
            'project_id' => null,
            'has_project' => 0,
        ]);

        $project = Project::create([
            'name' => 'API Owned Project',
            'user_id' => $user->id,
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/projects/active');

        $response->assertOk()
            ->assertJsonPath('data.id', $project->id)
            ->assertJsonPath('data.name', 'API Owned Project');

        $this->assertSame($project->id, $user->fresh()->project_id);
    }

    public function test_active_project_switch_rejects_inaccessible_project(): void
    {
        $ownerA = User::factory()->create(['user_type' => 'user']);
        $ownerB = User::factory()->create(['user_type' => 'user']);

        $allowedProject = Project::create(['name' => 'Allowed', 'user_id' => $ownerA->id]);
        $forbiddenProject = Project::create(['name' => 'Forbidden', 'user_id' => $ownerB->id]);

        $ownerA->projects()->syncWithoutDetaching([$allowedProject->id]);
        Sanctum::actingAs($ownerA);

        $response = $this->postJson('/api/v1/projects/active', [
            'project_id' => $forbiddenProject->id,
        ]);

        $response->assertStatus(403)
            ->assertJsonPath('error.code', 'PROJECT_FORBIDDEN');
    }
}

