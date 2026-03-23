<?php

namespace App\Http\Middleware;

use App\Support\ActiveProjectResolver;
use Closure;
use Illuminate\Http\Request;

class ResolveActiveProject
{
    public function __construct(
        private readonly ActiveProjectResolver $resolver
    ) {
    }

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json([
                'error' => [
                    'code' => 'UNAUTHENTICATED',
                    'message' => 'Authentication is required.',
                ],
            ], 401);
        }

        $project = $this->resolver->resolve($user);
        if (! $project) {
            return response()->json([
                'error' => [
                    'code' => 'PROJECT_REQUIRED',
                    'message' => 'No active project is available for this account.',
                ],
            ], 422);
        }

        $request->attributes->set('active_project', $project);

        return $next($request);
    }
}

