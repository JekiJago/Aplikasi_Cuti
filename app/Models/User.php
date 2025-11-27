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
        'employee_id',
        'password',
        'role',
        'position',
        'department',
        'gender',
        'hire_date',
        'annual_leave_quota',
        'used_leave_days',
        'important_leave_used_days',
        'big_leave_used_days',
        'big_leave_last_used_at',
        'non_active_leave_used_days',
        'sick_leave_used_days',
        'maternity_leave_used_count',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'annual_leave_quota'         => 'integer',
        'used_leave_days'            => 'integer',
        'hire_date'                  => 'date',
        'important_leave_used_days'  => 'integer',
        'big_leave_used_days'        => 'integer',
        'big_leave_last_used_at'     => 'datetime',
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
        $summary = app(\App\Services\LeaveBalanceService::class)->getAnnualLeaveSummary($this);

        return $summary['total_available'];
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
