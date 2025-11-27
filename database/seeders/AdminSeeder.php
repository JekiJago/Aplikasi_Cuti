<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan field harus sesuai dengan urutan kolom di database
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'               => 'Admin',
                'email'              => 'admin@gmail.com',
                'password'           => 'password',
                'employee_id'        => 'ADM001',
                'position'           => 'HR Manager',
                'role'               => 'admin',
                'department'         => 'HR',
                'gender'             => 'male',
                'hire_date'          => now()->subYears(6),
                'annual_leave_quota' => 20,
                'used_leave_days'    => 0,
                'important_leave_used_days' => 0,
                'big_leave_used_days' => 0,
                'big_leave_last_used_at' => null,
                'non_active_leave_used_days' => 0,
                'sick_leave_used_days' => 0,
                'maternity_leave_used_count' => 0,
                'avatar'             => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name'               => 'User',
                'email'              => 'user@gmail.com',
                'employee_id'        => 'USR001',
                'password'           => 'password',
                'role'               => 'employee',
                'position'           => 'Staff',
                'department'         => 'Intel',
                'gender'             => 'female',
                'hire_date'          => now()->subYears(7),
                'annual_leave_quota' => 20,
                'used_leave_days'    => 0,
                'important_leave_used_days' => 0,
                'big_leave_used_days' => 0,
                'big_leave_last_used_at' => null,
                'non_active_leave_used_days' => 0,
                'sick_leave_used_days' => 0,
                'maternity_leave_used_count' => 0,
                'avatar'             => null,
            ]
        );
    }
}
