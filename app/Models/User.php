<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'employee_id',
        'password',
        'role',
        'login_type',
        'position',
        'department',
        'gender',
        'hire_date',
        'annual_leave_quota',
        'used_leave_days',
        'important_leave_used_days',
        'sick_leave_used_days',
        'big_leave_used_days',
        'big_leave_last_used_at',
        'non_active_leave_used_days',
        'maternity_leave_used_count',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'big_leave_last_used_at' => 'date',
        'email_verified_at' => 'datetime',
    ];

    /**
     * RELASI KE TABEL leave_requests
     * Memperbaiki error: Call to undefined method User::leaveRequests()
     */
    public function leaveRequests()
    {
        return $this->hasMany(\App\Models\LeaveRequest::class, 'user_id');
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah pegawai biasa
     */
    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }
}
