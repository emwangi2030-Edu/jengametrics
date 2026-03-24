<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keeps users.project_id in sync with their first assigned/owned project so module routes
 * (/boq, /boms, etc.) work even when login redirects to a non-dashboard URL (intended URL).
 */
class EnsureUserActiveProject
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            bootstrap_user_active_project_if_missing();
        }

        return $next($request);
    }
}
