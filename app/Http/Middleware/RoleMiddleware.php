<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated'], 401)
                : redirect()->route('login');
        }

        $userRole = $user->role->slug ?? '';

        $roleMap = [
            'admin'      => ['super-admin', 'admin', 'manager'],
            'super-admin'=> ['super-admin'],
            'agent'      => ['agent'],
            'customer'   => ['customer'],
        ];

        $allowed = collect($roles)
            ->flatMap(fn ($r) => $roleMap[$r] ?? [$r])
            ->unique()
            ->all();

        if (!in_array($userRole, $allowed, true)) {
            abort(403, 'You do not have permission to access this area.');
        }

        return $next($request);
    }
}
