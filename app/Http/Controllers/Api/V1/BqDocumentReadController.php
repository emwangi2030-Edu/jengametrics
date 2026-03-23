<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BqDocument;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class BqDocumentReadController extends Controller
{
    public function index(Request $request)
    {
        $project = $request->attributes->get('active_project');

        $documents = BqDocument::query()
            ->where('project_id', $project->id)
            ->whereNotNull('parent_id')
            ->orderByDesc('created_at')
            ->get()
            ->map(function (BqDocument $doc) {
                return [
                    'id' => $doc->id,
                    'title' => $doc->title,
                    'description' => $doc->description,
                    'units' => (int) ($doc->units ?? 1),
                    'created_at' => optional($doc->created_at)?->toISOString(),
                ];
            })
            ->values();

        return ApiResponse::success([
            'items' => $documents,
            'count' => $documents->count(),
        ]);
    }
}

