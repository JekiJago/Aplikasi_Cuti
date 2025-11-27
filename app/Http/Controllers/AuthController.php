<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = trim($credentials['email']);
        if (! str_contains($login, '@')) {
            $login = sprintf('%s@pegawai.local', Str::slug($login));
        }
        $credentials['email'] = $login;

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

        // Insert langsung dengan DB::table untuk memastikan urutan kolom benar
        // Urutan kolom di database: id, name, email, employee_id, nip, email_verified_at, password, role, position, department, hire_date, annual_leave_quota, used_leave_days, ...
        $hashedPassword = Hash::make($data['password']);
        
        $userId = DB::table('users')->insertGetId([
            'name'                      => $data['name'],
            'email'                     => $data['email'],
            'employee_id'               => $data['employee_id'],
            'nip'                       => null,
            'email_verified_at'         => null,
            'password'                  => $hashedPassword,
            'role'                      => 'employee',
            'position'                  => $data['position'],
            'department'                => $data['department'] ?? null,
            'hire_date'                 => null,
            'annual_leave_quota'        => 12,
            'used_leave_days'           => 0,
            'important_leave_used_days' => 0,
            'big_leave_used_days'       => 0,
            'non_active_leave_used_days' => 0,
            'sick_leave_used_days'      => 0,
            'maternity_leave_used_count' => 0,
            'avatar'                    => null,
            'remember_token'            => null,
            'created_at'                => now(),
            'updated_at'                => now(),
        ]);

        $user = User::find($userId);
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
