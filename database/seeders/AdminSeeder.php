<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Data array untuk semua user
        $users = [
            // ADMIN
            [
                'name'               => 'Administrator',
                'email'              => 'admin@gmail.com',
                'employee_id'        => 'ADM001',
                'password'           => 'admin123',
                'role'               => 'admin',
                'login_type'         => 'email',
                'position'           => 'HR Manager',
                'department'         => 'Human Resources',
                'gender'             => 'male',
                'hire_date'          => now()->subYears(6),
                'annual_leave_quota' => 20,
            ],
            
            // PEGAWAI 1
            [
                'name'               => 'Ahmad Rizki',
                'email'              => '19850101@pegawai.local',
                'employee_id'        => '19850101',
                'password'           => '19850101',
                'role'               => 'employee',
                'login_type'         => 'employee_id',
                'position'           => 'Staff IT',
                'department'         => 'Information Technology',
                'gender'             => 'male',
                'hire_date'          => now()->subYears(3),
                'annual_leave_quota' => 12,
                'used_leave_days'    => 3,
                'sick_leave_used_days' => 2,
            ],
            
            // PEGAWAI 2 (User dari seeder lama)
            [
                'name'               => 'User Employee',
                'email'              => 'USR001@pegawai.local',
                'employee_id'        => 'USR001',
                'password'           => 'USR001',
                'role'               => 'employee',
                'login_type'         => 'employee_id',
                'position'           => 'Staff Intelligence',
                'department'         => 'Intelligence',
                'gender'             => 'female',
                'hire_date'          => now()->subYears(7),
                'annual_leave_quota' => 20,
                'used_leave_days'    => 8,
                'important_leave_used_days' => 2,
                'sick_leave_used_days' => 1,
            ],
            
            // PEGAWAI 3
            [
                'name'               => 'Siti Nurhaliza',
                'email'              => '2023001@pegawai.local',
                'employee_id'        => '2023001',
                'password'           => '2023001',
                'role'               => 'employee',
                'login_type'         => 'employee_id',
                'position'           => 'HR Staff',
                'department'         => 'Human Resources',
                'gender'             => 'female',
                'hire_date'          => now()->subMonths(6),
                'annual_leave_quota' => 12,
            ],
            
            // PEGAWAI 4
            [
                'name'               => 'Budi Santoso',
                'email'              => '2023002@pegawai.local',
                'employee_id'        => '2023002',
                'password'           => '2023002',
                'role'               => 'employee',
                'login_type'         => 'employee_id',
                'position'           => 'Sales Manager',
                'department'         => 'Sales',
                'gender'             => 'male',
                'hire_date'          => now()->subMonths(4),
                'annual_leave_quota' => 12,
                'used_leave_days'    => 2,
            ],
        ];

        // Default values untuk field yang sama semua
        $defaults = [
            'important_leave_used_days' => 0,
            'big_leave_used_days' => 0,
            'big_leave_last_used_at' => null,
            'non_active_leave_used_days' => 0,
            'maternity_leave_used_count' => 0,
            'avatar' => null,
        ];

        foreach ($users as $userData) {
            // Gabungkan dengan defaults
            $data = array_merge($defaults, $userData);
            
            // Update atau create berdasarkan email untuk admin, employee_id untuk pegawai
            if ($data['role'] === 'admin') {
                User::updateOrCreate(['email' => $data['email']], $data);
            } else {
                User::updateOrCreate(['employee_id' => $data['employee_id']], $data);
            }
        }

        $this->command->info('Seeder berhasil dijalankan!');
        $this->command->info('==========================================');
        $this->command->info('DATA LOGIN TEST:');
        $this->command->info('==========================================');
        $this->command->info('ADMIN LOGIN (pilih LOGIN sebagai ADMIN):');
        $this->command->info('• Email: admin@gmail.com');
        $this->command->info('  Password: admin123');
        $this->command->info('');
        $this->command->info('PEGAWAI LOGIN (pilih LOGIN sebagai PEGAWAI):');
        $this->command->info('1. ID Pegawai: 19850101');
        $this->command->info('   Password: 19850101');
        $this->command->info('2. ID Pegawai: USR001');
        $this->command->info('   Password: USR001');
        $this->command->info('3. ID Pegawai: 2023001');
        $this->command->info('   Password: 2023001');
        $this->command->info('4. ID Pegawai: 2023002');
        $this->command->info('   Password: 2023002');
        $this->command->info('==========================================');
        $this->command->info('CATATAN: Password semua user maksimal 8 karakter');
        $this->command->info('==========================================');
    }
}