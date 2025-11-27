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
                'nip'                => null,
                'position'           => 'HR Manager',
                'email_verified_at'  => null,

                'role'               => 'admin',

                'department'         => 'HR',
                'hire_date'          => null,
                'annual_leave_quota' => 20,
                'used_leave_days'    => 0,
                'important_leave_used_days' => 0,
                'big_leave_used_days' => 0,
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
                'nip'                => null,
                'email_verified_at'  => null,
                'password'           => 'password',
                'role'               => 'employee',
                'position'           => 'Staff',
                'department'         => 'Intel',
                'hire_date'          => null,
                'annual_leave_quota' => 20,
                'used_leave_days'    => 0,
                'important_leave_used_days' => 0,
                'big_leave_used_days' => 0,
                'non_active_leave_used_days' => 0,
                'sick_leave_used_days' => 0,
                'maternity_leave_used_count' => 0,
                'avatar'             => null,
            ]
        );
    }
}
