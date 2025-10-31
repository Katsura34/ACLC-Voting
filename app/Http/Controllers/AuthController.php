<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login attempt
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'usn' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('usn', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect based on user type
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/student/dashboard');
        }

        throw ValidationException::withMessages([
            'usn' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Show registration form (for admin use)
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Handle user registration (for admin use)
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'usn' => 'required|string|unique:users,usn',
            'password' => 'required|string|min:6|confirmed',
            'user_type' => 'required|in:student,admin',
        ]);

        $user = User::create([
            'usn' => $request->usn,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
        ]);

        return redirect()->route('login')->with('success', 'User registered successfully!');
    }
}
