<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'login' => ['required', 'string'],
            'password' => ['required', 'string', 'max:8'],
            'login_type' => ['required', 'in:email,employee_id'],
        ];

        // Tambahkan validasi khusus berdasarkan login_type
        if ($this->input('login_type') === 'email') {
            $rules['login'][] = 'email';
        }

        return $rules;
    }

    /**
     * Get the validation attributes that apply to the request.
     */
    public function attributes(): array
    {
        $loginType = $this->input('login_type');
        
        return [
            'login' => $loginType === 'email' ? 'Email' : 'ID Pegawai',
            'password' => 'Password',
            'login_type' => 'Jenis Login',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     */
    public function messages(): array
    {
        return [
            'login.required' => ':attribute harus diisi',
            'login.email' => 'Format email tidak valid',
            'password.required' => 'Password harus diisi',
            'password.max' => 'Password maksimal 8 karakter',
            'login_type.required' => 'Jenis login harus dipilih',
            'login_type.in' => 'Jenis login tidak valid',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Cari user berdasarkan login_type
        $loginType = $this->input('login_type');
        $login = $this->input('login');
        $password = $this->input('password');

        // Query user berdasarkan login_type
        if ($loginType === 'email') {
            $user = \App\Models\User::where('email', $login)->first();
        } else {
            // Cari user via relasi pegawai->nip
            $user = \App\Models\User::whereHas('pegawai', function($query) use ($login) {
                $query->where('nip', $login);
            })->first();
        }

        // Jika user tidak ditemukan atau password salah
        if (!$user || !\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey());
            
            throw ValidationException::withMessages([
                'login' => 'Email/NIP atau password salah',
            ]);
        }

        // Login user
        Auth::login($user, $this->boolean('remember'));
        
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        $loginType = $this->input('login_type');
        $login = $this->input('login');
        
        return Str::transliterate(Str::lower($loginType . '|' . $login) . '|' . $this->ip());
    }
}