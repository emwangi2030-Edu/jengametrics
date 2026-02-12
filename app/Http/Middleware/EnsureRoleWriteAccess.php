<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRoleWriteAccess
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasRoleAccess($role)) {
            return $next($request);
        }

        $message = match ($role) {
            'boq' => 'You do not have access to manage BoQ and BoM.',
            'materials' => 'You do not have access to manage materials.',
            'labour' => 'You do not have access to manage labour.',
            default => 'You do not have access to perform this action.',
        };

        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 403);
        }

        return redirect()->back()->with('warning', $message);
    }
}
