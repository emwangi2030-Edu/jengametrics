<?php

namespace App\Support;

use App\Models\Project;
use App\Models\User;

class ActiveProjectResolver
{
    public function resolve(User $user): ?Project
    {
        $projectId = (int) ($user->project_id ?? 0);
        if ($projectId > 0) {
            $project = $this->findAccessibleProject($user, $projectId);
            if ($project) {
                return $project;
            }
        }

        $fallbackProject = $this->findFallbackProject($user);
        if (! $fallbackProject) {
            // Unassignable project_id with no projects to fall back to — clear stale pointer.
            if ($projectId > 0) {
                $user->forceFill([
                    'project_id' => null,
                    'has_project' => 0,
                ])->saveQuietly();
            }

            return null;
        }

        $user->project_id = (int) $fallbackProject->id;
        $user->has_project = 1;
        $user->save();

        return $fallbackProject;
    }

    public function canAccess(User $user, int $projectId): bool
    {
        return (bool) $this->findAccessibleProject($user, $projectId);
    }

    private function findAccessibleProject(User $user, int $projectId): ?Project
    {
        $effectiveUser = $this->effectiveUser($user);

        $assigned = $effectiveUser->projects()
            ->where('projects.id', $projectId)
            ->exists();

        if ($assigned) {
            return Project::find($projectId);
        }

        $owned = $effectiveUser->ownedProjects()
            ->where('id', $projectId)
            ->exists();

        if ($owned) {
            return Project::find($projectId);
        }

        return null;
    }

    private function findFallbackProject(User $user): ?Project
    {
        $effectiveUser = $this->effectiveUser($user);

        $assignedId = $effectiveUser->projects()->value('projects.id');
        if ($assignedId) {
            return Project::find((int) $assignedId);
        }

        $ownedId = $effectiveUser->ownedProjects()->value('id');
        if ($ownedId) {
            return Project::find((int) $ownedId);
        }

        return null;
    }

    private function effectiveUser(User $user): User
    {
        return ($user->isSubAccount() && $user->parentUser)
            ? $user->parentUser
            : $user;
    }
}

