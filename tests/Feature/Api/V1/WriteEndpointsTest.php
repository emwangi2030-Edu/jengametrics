<?php

namespace Tests\Feature\Api\V1;

use App\Models\Project;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class WriteEndpointsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_worker_and_payment_with_active_project_scope(): void
    {
        $user = User::factory()->create(['user_type' => 'user']);
        $project = Project::create(['name' => 'Write API Project', 'user_id' => $user->id]);
        $user->update(['project_id' => $project->id, 'has_project' => 1]);
        $user->projects()->syncWithoutDetaching([$project->id]);
        Sanctum::actingAs($user);

        $workerResponse = $this->postJson('/api/v1/workers', [
            'full_name' => 'API Worker',
            'id_number' => 12345678,
            'job_category' => 'Mason',
            'work_type' => 'Contract',
            'phone' => '0711111111',
            'email' => 'api.worker@example.test',
            'payment_amount' => 1200,
            'payment_frequency' => 'per day',
            'mode_of_payment' => 'Cash',
        ]);

        $workerResponse->assertStatus(201);
        $workerId = (int) $workerResponse->json('data.id');

        $paymentResponse = $this->postJson("/api/v1/workers/{$workerId}/payments", [
            'amount' => 500,
        ]);

        $paymentResponse->assertStatus(201)
            ->assertJsonPath('data.worker_id', $workerId);
    }

    public function test_can_create_supplier_material_and_adhoc_requisition(): void
    {
        $user = User::factory()->create(['user_type' => 'user']);
        $project = Project::create(['name' => 'Write API Project 2', 'user_id' => $user->id]);
        $user->update(['project_id' => $project->id, 'has_project' => 1]);
        $user->projects()->syncWithoutDetaching([$project->id]);
        Sanctum::actingAs($user);

        $supplierResponse = $this->postJson('/api/v1/suppliers', [
            'name' => 'API Supplier',
            'contact_info' => '0700000000',
        ]);
        $supplierResponse->assertOk();
        $supplierId = (int) $supplierResponse->json('data.id');

        $materialResponse = $this->postJson('/api/v1/materials/adhoc', [
            'adhoc_name' => 'API Cement',
            'adhoc_unit' => 'bags',
            'unit_price' => 250,
            'quantity_in_stock' => 20,
            'supplier_id' => $supplierId,
        ]);
        $materialResponse->assertStatus(201)
            ->assertJsonPath('data.project_id', $project->id);

        $section = Section::query()->first() ?? Section::create(['name' => 'API Section']);
        $reqResponse = $this->postJson('/api/v1/requisitions/adhoc', [
            'material_name' => 'API Timber',
            'unit_of_measurement' => 'pcs',
            'quantity_requested' => 10,
            'section' => $section->id,
        ]);

        $reqResponse->assertStatus(201)
            ->assertJsonPath('data.status', 'pending');
    }
}

