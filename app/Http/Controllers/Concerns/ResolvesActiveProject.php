<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Project;
use App\Support\ActiveProjectResolver;
use Illuminate\Support\Facades\Auth;

/**
 * Shared active-project resolution for web controllers (matches API {@see ResolveActiveProject}).
 */
trait ResolvesActiveProject
{
    protected function resolveActiveProject(): ?Project
    {
        $user = Auth::user();
        if (! $user) {
            return null;
        }

        return app(ActiveProjectResolver::class)->resolve($user);
    }
}
