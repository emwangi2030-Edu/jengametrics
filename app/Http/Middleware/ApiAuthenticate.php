<?php

namespace App\Http\Middleware;

use App\Support\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('sanctum')->user();

        if (! $user) {
            $user = Auth::guard('web')->user();
        }

        if (! $user) {
            return ApiResponse::error(
                code: 'UNAUTHENTICATED',
                message: 'Authentication is required.',
                status: 401
            );
        }

        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}

