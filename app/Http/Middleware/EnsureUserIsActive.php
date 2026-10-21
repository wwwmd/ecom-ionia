<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->status !== 'active') {
            $status = auth()->user()->status;
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account is '.$status.'.']);
        }
        return $next($request);
    }
}
