<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BqDocument;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class BqDocumentWriteController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'units' => ['nullable', 'integer', 'min:1'],
        ]);

        $project = $request->attributes->get('active_project');
        $master = $this->ensureMasterDocument(
            (int) $project->id,
            (string) $project->name,
            (int) $request->user()->id
        );

        $document = BqDocument::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'user_id' => (int) $request->user()->id,
            'project_id' => (int) $project->id,
            'parent_id' => (int) $master->id,
            'units' => (int) ($validated['units'] ?? 1),
        ]);

        return ApiResponse::success([
            'id' => $document->id,
            'title' => $document->title,
            'project_id' => $document->project_id,
            'parent_id' => $document->parent_id,
        ], message: 'BoQ document created.', status: 201);
    }

    private function ensureMasterDocument(int $projectId, string $projectName, int $userId): BqDocument
    {
        return BqDocument::firstOrCreate(
            [
                'project_id' => $projectId,
                'parent_id' => null,
            ],
            [
                'title' => $projectName . ' Master BOQ',
                'description' => 'Auto-created master BoQ container.',
                'user_id' => $userId,
                'units' => 1,
            ]
        );
    }
}

