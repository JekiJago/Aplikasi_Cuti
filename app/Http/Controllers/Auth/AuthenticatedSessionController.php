<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        }

        $request->session()->regenerate();

        // Redirect berdasarkan role user
        $user = Auth::user();
        
        // Debug: cek role user
        \Log::info('Login successful', [
            'user_id' => $user->id,
            'name' => $user->name,
            'role' => $user->role,
            'isAdmin' => $user->isAdmin(),
        ]);
        
        if ($user->isAdmin()) {
            // Redirect ke dashboard admin
            return redirect()->route('admin.dashboard');
        }
        
        // Redirect ke dashboard pegawai
        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}