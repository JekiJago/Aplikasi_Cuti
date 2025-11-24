<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'    => ['required', 'string', 'min:6', 'confirmed'],
            'employee_id' => ['required', 'string', 'max:50', 'unique:users,employee_id'],
            'position'    => ['required', 'string', 'max:100'],
            'department'  => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::create([
            'name'               => $data['name'],
            'email'              => $data['email'],
            'password'           => $data['password'], // sudah auto di-hash di accessor User
            'employee_id'        => $data['employee_id'],
            'position'           => $data['position'],
            'department'         => $data['department'] ?? null,
            'role'               => 'employee',   // default karyawan
            'annual_leave_quota' => 12,
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
