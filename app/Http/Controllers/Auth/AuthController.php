<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) return redirect()->route(Auth::user()->dashboard_route);
        return view('auth.login');
    }

    /**
     * SINGLE LOGIN — auto-detects role and redirects.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = strtolower($credentials['email']).'|'.$request->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return back()->withErrors([
                'email' => 'Too many attempts. Try again in '.RateLimiter::availableIn($throttleKey).' seconds.',
            ]);
        }

        $remember = $request->boolean('remember');

        if (!Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $remember)) {
            RateLimiter::hit($throttleKey, 60);
            return back()->withInput()->withErrors(['email' => 'Invalid email or password.']);
        }

        RateLimiter::clear($throttleKey);

        $user = Auth::user();

        if ($user->status !== 'active') {
            Auth::logout();
            return back()->withErrors(['email' => 'Your account is '.$user->status.'.']);
        }

        $user->forceFill([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ])->save();

        $request->session()->regenerate();

        ActivityLog::record('auth', 'login', "User {$user->email} logged in");

        return redirect()->intended(route($user->dashboard_route))
            ->with('success', 'Welcome back, '.$user->name.'!');
    }

    public function showRegister()
    {
        if (Auth::check()) return redirect()->route(Auth::user()->dashboard_route);
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:191', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:25'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $customerRole = Role::where('slug', 'customer')->firstOrFail();

        $user = DB::transaction(function () use ($validated, $customerRole) {
            $user = User::create([
                'role_id' => $customerRole->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'password' => Hash::make($validated['password']),
                'status' => 'active',
            ]);
            $user->customerProfile()->create([]);
            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        ActivityLog::record('auth', 'register', "New customer registered: {$user->email}");

        return redirect()->route('customer.dashboard')
            ->with('success', 'Welcome to store Store!');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) ActivityLog::record('auth', 'logout', "User {$user->email} logged out");

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out.');
    }
}
