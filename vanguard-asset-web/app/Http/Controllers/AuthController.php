<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SecurityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function showLogin(Request $request)
    {
        $lockoutSeconds = SecurityLogService::getLockoutRemainingSeconds($request->ip());
        return view('auth.login', ['lockoutSeconds' => $lockoutSeconds]);
    }

    /**
     * Handle an incoming authentication request with rate limiting.
     */
    public function login(Request $request)
    {
        // Check if IP is locked out (brute force protection)
        if (SecurityLogService::isLockedOut($request->ip())) {
            $remaining = SecurityLogService::getLockoutRemainingSeconds($request->ip());
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login gagal. Akun dikunci selama " . ceil($remaining / 60) . " menit. Silakan coba lagi nanti.",
            ])->onlyInput('email')->with('lockout_seconds', $remaining);
        }

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Log successful login
            SecurityLogService::logLogin(Auth::id(), $request);

            return redirect()->intended('/dashboard');
        }

        // Log failed login attempt
        SecurityLogService::logFailedLogin($request->input('email'), $request);

        // Check if this triggers a lockout
        if (SecurityLogService::isLockedOut($request->ip())) {
            SecurityLogService::logAccountLocked($request->input('email'), $request);
            $remaining = SecurityLogService::getLockoutRemainingSeconds($request->ip());
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login gagal. Akun dikunci selama " . ceil($remaining / 60) . " menit.",
            ])->onlyInput('email')->with('lockout_seconds', $remaining);
        }

        $attemptsLeft = 5 - SecurityLogService::countRecentFailedLogins($request->ip());

        return back()->withErrors([
            'email' => "Kredensial tidak cocok. Sisa percobaan: {$attemptsLeft}.",
        ])->onlyInput('email');
    }

    /**
     * Display the registration view.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * SECURITY FIX: Role is always 'staff' — no self-assignment allowed.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#+\-_]).+$/',
            ],
        ], [
            'password.regex' => 'Kata sandi harus mengandung huruf besar, huruf kecil, angka, dan simbol.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff', // SECURITY: always staff, admin assigns roles
        ]);

        Auth::login($user);

        // Log the registration as a login event
        SecurityLogService::logLogin($user->id, $request);

        return redirect('/dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request)
    {
        SecurityLogService::logLogout(Auth::id(), $request);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
