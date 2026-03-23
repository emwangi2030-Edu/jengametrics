<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaterialWorkerTest extends TestCase
{
    use RefreshDatabase;

    public function test_materials_index_redirects_guests_to_login(): void
    {
        $response = $this->get(route('materials.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_workers_index_redirects_guests_to_login(): void
    {
        $response = $this->get(route('workers.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_reports_wages_redirects_guests_to_login(): void
    {
        $response = $this->get(route('reports.wages'));

        $response->assertRedirect(route('login'));
    }
}
