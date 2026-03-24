<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProfileEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_show_returns_authenticated_user_payload(): void
    {
        $user = User::factory()->create([
            'name' => 'Profile Tester',
            'email' => 'profile.tester@example.test',
            'user_type' => 'user',
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/profile');

        $response->assertOk()
            ->assertJsonPath('data.name', 'Profile Tester')
            ->assertJsonPath('data.email', 'profile.tester@example.test');
    }

    public function test_profile_update_changes_name_and_email(): void
    {
        $user = User::factory()->create([
            'name' => 'Before Name',
            'email' => 'before@example.test',
            'user_type' => 'user',
        ]);

        Sanctum::actingAs($user);

        $response = $this->patchJson('/api/v1/profile', [
            'name' => 'After Name',
            'email' => 'after@example.test',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.name', 'After Name')
            ->assertJsonPath('data.email', 'after@example.test');

        $this->assertSame('After Name', $user->fresh()->name);
        $this->assertSame('after@example.test', $user->fresh()->email);
    }
}

