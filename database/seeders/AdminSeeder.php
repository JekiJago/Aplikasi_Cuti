<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'               => 'Admin',
                'employee_id'        => 'ADM001',
                'position'           => 'HR Manager',
                'department'         => 'HR',
                'role'               => 'admin',
                'password'           => 'password',
                'annual_leave_quota' => 20,
                'used_leave_days'    => 0,
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name'               => 'User',
                'employee_id'        => 'USR001',
                'position'           => 'Staff',
                'department'         => 'Intel',
                'role'               => 'employee',
                'password'           => 'password',
                'annual_leave_quota' => 20,
                'used_leave_days'    => 0,
            ]
        );
    }
}
