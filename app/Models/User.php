<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
        'position',
        'department',
        'role',
        'avatar',
        'annual_leave_quota',
        'used_leave_days',
        'hire_date',
        'important_leave_used_days',
        'big_leave_used_days',
        'non_active_leave_used_days',
        'sick_leave_used_days',
        'maternity_leave_used_count',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'          => 'datetime',
        'annual_leave_quota'         => 'integer',
        'used_leave_days'            => 'integer',
        'hire_date'                  => 'date',
        'important_leave_used_days'  => 'integer',
        'big_leave_used_days'        => 'integer',
        'non_active_leave_used_days' => 'integer',
        'sick_leave_used_days'       => 'integer',
        'maternity_leave_used_count' => 'integer',
    ];

    /** Relationships */
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(\App\Models\LeaveRequest::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /** Helpers */
    public function getRemainingLeaveDays(): int
    {
        return max(0, $this->annual_leave_quota - $this->used_leave_days);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    /** Optional: auto hash password ketika set attribute */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? Hash::make($value) : null,
        );
    }
}
