<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Support\ActiveProjectResolver;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class ActiveProjectController extends Controller
{
    public function __construct(
        private readonly ActiveProjectResolver $resolver
    ) {
    }

    public function show(Request $request)
    {
        $project = $this->resolver->resolve($request->user());
        if (! $project) {
            return ApiResponse::error(
                code: 'PROJECT_REQUIRED',
                message: 'No active project is available for this account.',
                status: 422
            );
        }

        return ApiResponse::success($this->toResource($project));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
        ]);

        $user = $request->user();
        $projectId = (int) $validated['project_id'];
        if (! $this->resolver->canAccess($user, $projectId)) {
            return ApiResponse::error(
                code: 'PROJECT_FORBIDDEN',
                message: 'You do not have access to the selected project.',
                status: 403
            );
        }

        $user->project_id = $projectId;
        $user->has_project = 1;
        $user->save();

        $project = Project::findOrFail($projectId);

        return ApiResponse::success(
            data: $this->toResource($project),
            message: 'Active project updated.'
        );
    }

    private function toResource(Project $project): array
    {
        return [
            'id' => $project->id,
            'name' => $project->name,
            'status' => $project->status,
            'start_date' => $project->start_date,
            'end_date' => $project->end_date,
            'budget' => $project->budget,
        ];
    }
}

