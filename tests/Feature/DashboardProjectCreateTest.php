<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardProjectCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_create_project_via_post_json(): void
    {
        $user = User::factory()->create([
            'user_type' => 'user',
            'project_id' => null,
            'has_project' => 0,
            'parent_user_id' => null,
        ]);

        $this->actingAs($user);

        $payload = [
            'project_uid' => 'E2E-001',
            'name' => 'E2E Test Project',
            'description' => 'From automated test',
            'project_duration' => 45,
            'address' => 'Nyahururu',
            'budget' => '500000',
            'project_type' => 'Residential',
            'priority' => 'High',
        ];

        $response = $this->postJson('/dashboard/projects', $payload);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'E2E Test Project')
            ->assertJsonPath('data.project_uid', 'E2E-001');

        $id = (int) $response->json('data.id');
        $this->assertGreaterThan(0, $id);

        $this->assertDatabaseHas('projects', [
            'id' => $id,
            'project_uid' => 'E2E-001',
            'name' => 'E2E Test Project',
            'user_id' => $user->id,
        ]);

        $user->refresh();
        $this->assertSame($id, (int) $user->project_id);
        $this->assertSame(1, (int) $user->has_project);
    }

    public function test_subaccount_cannot_create_project(): void
    {
        $parent = User::factory()->create(['user_type' => 'user']);
        $sub = User::factory()->create([
            'user_type' => 'user',
            'parent_user_id' => $parent->id,
        ]);

        $this->actingAs($sub);

        $response = $this->postJson('/dashboard/projects', [
            'project_uid' => 'SUB-001',
            'name' => 'Blocked',
            'description' => 'Should not create',
            'project_duration' => 10,
            'address' => 'Somewhere',
            'budget' => '1',
        ]);

        $response->assertForbidden();
    }

    public function test_validation_errors_return_422(): void
    {
        $user = User::factory()->create([
            'user_type' => 'user',
            'parent_user_id' => null,
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/dashboard/projects', [
            'project_uid' => 'bad space',
            'name' => '',
            'description' => '',
            'project_duration' => 0,
            'address' => '',
            'budget' => '',
        ]);

        $response->assertUnprocessable();
    }
}
