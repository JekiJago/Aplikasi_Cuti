<?php

namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;

class LeaveRequestPolicy
{
    public function view(User $user, LeaveRequest $leave): bool
    {
        return $user->id === $leave->user_id || $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isEmployee();
    }

    public function delete(User $user, LeaveRequest $leave): bool
    {
        // hanya pemilik & status pending
        return $user->id === $leave->user_id &&
            $leave->status === 'pending';
    }

    public function approve(User $user, LeaveRequest $leave): bool
    {
        return $user->isAdmin() &&
            $leave->status === 'pending';
    }
}
