<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // 1) ADMIN
        // =========================
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'login_type' => 'email',
                'pegawai_id' => null,
            ]
        );

        // =========================
        // 2) INSERT PEGAWAI DULU
        // =========================
        DB::table('pegawai')->updateOrInsert(
            ['nip' => 'USR001'],
            [
                'nrp' => '123456',
                'nama' => 'User Pegawai',
                'jenis_kelamin' => 'male',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // =========================
        // 3) BARU INSERT USER PEGAWAI
        // =========================
        User::updateOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name' => 'User Pegawai',
                'password' => Hash::make('123456'),
                'role' => 'employee',
                'login_type' => 'nip',
                'pegawai_id' => 'USR001', // harus ada di pegawai.nip
            ]
        );
    }
}
