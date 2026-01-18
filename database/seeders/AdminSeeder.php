<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AnnualQuota;
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
                'gender'             => 'male',
                'hire_date'          => now()->subYears(6),
                // Kuota cuti lifetime
                'sick_leave_quota' => 12,
                'personal_leave_quota' => 12,
                'important_leave_quota' => 30,
                'big_leave_quota' => 90,
                'non_active_leave_quota' => 365,
                'maternity_leave_quota' => 90,
                'paternity_leave_quota' => 14,
                'marriage_leave_quota' => 3,
            ],
            
            // PEGAWAI 1
            [
                'name'               => 'Ahmad Rizki',
                'email'              => '19850101@pegawai.local',
                'employee_id'        => '19850101',//(nip)
                'password'           => '19850101',//(nrp)
                'role'               => 'employee',
                'login_type'         => 'employee_id',
                'gender'             => 'male',
                'hire_date'          => now()->subYears(3),
                // Kuota cuti lifetime
                'sick_leave_quota' => 12,
                'personal_leave_quota' => 12,
                'important_leave_quota' => 30,
                'big_leave_quota' => 90,
                'non_active_leave_quota' => 365,
                'maternity_leave_quota' => 90,
                'paternity_leave_quota' => 14,
                'marriage_leave_quota' => 3,
                // Tracking penggunaan
                'sick_leave_used_days' => 2,
                'important_leave_used_days' => 1,
            ],
            
            // PEGAWAI 2
            [
                'name'               => 'User Employee',
                'email'              => 'USR001@pegawai.local',
                'employee_id'        => 'USR001',
                'password'           => 'USR001',
                'role'               => 'employee',
                'login_type'         => 'employee_id',
                'gender'             => 'female',
                'hire_date'          => now()->subYears(7),
                // Kuota cuti lifetime
                'sick_leave_quota' => 12,
                'personal_leave_quota' => 12,
                'important_leave_quota' => 30,
                'big_leave_quota' => 90,
                'non_active_leave_quota' => 365,
                'maternity_leave_quota' => 90,
                'paternity_leave_quota' => 14,
                'marriage_leave_quota' => 3,
                // Tracking penggunaan
                'important_leave_used_days' => 2,
                'sick_leave_used_days' => 1,
                'maternity_leave_used_count' => 1,
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
            $data['password'] = Hash::make($data['password']);
            
            // Update atau create berdasarkan email untuk admin, employee_id untuk pegawai
            if ($data['role'] === 'admin') {
                $user = User::updateOrCreate(['email' => $data['email']], $data);
            } else {
                $user = User::updateOrCreate(['employee_id' => $data['employee_id']], $data);
            }
            
            // Generate yearly quotas untuk user
            if ($user->role === 'employee') {
                $user->generateYearlyQuotas();
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
        $this->command->info('==========================================');
        $this->command->info('CATATAN: Password semua user maksimal 8 karakter');
        $this->command->info('==========================================');
    }
}