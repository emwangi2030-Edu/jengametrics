<?php

namespace Tests\Feature\Api\V1;

use App\Models\Project;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class Sprint4BomBoqReportingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_boq_document_and_bom_then_fetch_reports(): void
    {
        $user = User::factory()->create(['user_type' => 'user']);
        $project = Project::create(['name' => 'Sprint4 Project', 'user_id' => $user->id]);
        $user->update(['project_id' => $project->id, 'has_project' => 1]);
        $user->projects()->syncWithoutDetaching([$project->id]);
        Sanctum::actingAs($user);

        $boq = $this->postJson('/api/v1/boq/documents', [
            'title' => 'Sprint4 BOQ',
            'description' => 'API generated',
            'units' => 1,
        ]);
        $boq->assertStatus(201);
        $boqId = (int) $boq->json('data.id');

        $section = Section::query()->first() ?? Section::create(['name' => 'S4 Section']);

        $bom = $this->postJson('/api/v1/boms', [
            'bq_document_id' => $boqId,
            'bom_name' => 'Sprint4 BOM',
            'items' => [
                [
                    'description' => 'Generic Item',
                    'quantity' => 10,
                    'unit' => 'pcs',
                    'section_id' => $section->id,
                    'item_id' => null,
                    'item_material_id' => null,
                    'rate' => 120,
                ],
            ],
        ]);
        $bom->assertStatus(201);

        $this->getJson('/api/v1/reports/summary')->assertOk();
        $this->getJson('/api/v1/reports/wages')->assertOk();
        $this->getJson('/api/v1/reports/purchases')->assertOk();
    }
}

