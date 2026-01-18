<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidays = [
            // 2025
            ['date' => '2025-01-01', 'name' => 'Tahun Baru', 'description' => 'Hari Tahun Baru Masehi'],
            ['date' => '2025-03-29', 'name' => 'Isra dan Mi\'raj', 'description' => 'Hari Besar Islam'],
            ['date' => '2025-04-04', 'name' => 'Jumat Agung', 'description' => 'Hari Raya Kristen'],
            ['date' => '2025-04-05', 'name' => 'Sabtu Paskah', 'description' => 'Hari Raya Kristen'],
            ['date' => '2025-04-06', 'name' => 'Minggu Paskah', 'description' => 'Hari Raya Kristen'],
            ['date' => '2025-04-10', 'name' => 'Hari Raya Idul Fitri', 'description' => 'Hari Besar Islam'],
            ['date' => '2025-04-11', 'name' => 'Hari Raya Idul Fitri', 'description' => 'Hari Besar Islam'],
            ['date' => '2025-04-12', 'name' => 'Cuti Bersama Idul Fitri', 'description' => 'Hari Libur'],
            ['date' => '2025-04-13', 'name' => 'Cuti Bersama Idul Fitri', 'description' => 'Hari Libur'],
            ['date' => '2025-04-14', 'name' => 'Cuti Bersama Idul Fitri', 'description' => 'Hari Libur'],
            ['date' => '2025-05-01', 'name' => 'Hari Buruh Internasional', 'description' => 'Hari Nasional'],
            ['date' => '2025-05-16', 'name' => 'Hari Raya Idul Adha', 'description' => 'Hari Besar Islam'],
            ['date' => '2025-05-26', 'name' => 'Tahun Baru Islam', 'description' => 'Hari Besar Islam'],
            ['date' => '2025-06-04', 'name' => 'Hari Sukarno', 'description' => 'Hari Nasional'],
            ['date' => '2025-06-05', 'name' => 'Hari Pendidikan Nasional', 'description' => 'Hari Nasional'],
            ['date' => '2025-07-04', 'name' => 'Mawlid Nabi Muhammad', 'description' => 'Hari Besar Islam'],
            ['date' => '2025-08-17', 'name' => 'Hari Kemerdekaan Indonesia', 'description' => 'Hari Nasional'],
            ['date' => '2025-09-01', 'name' => 'Hari Raya Idul Adha (Lemburan)', 'description' => 'Cuti Bersama'],
            ['date' => '2025-09-02', 'name' => 'Tahun Baru Islam (Lemburan)', 'description' => 'Cuti Bersama'],
            ['date' => '2025-12-25', 'name' => 'Hari Raya Natal', 'description' => 'Hari Raya Kristen'],
            ['date' => '2025-12-26', 'name' => 'Cuti Bersama Natal', 'description' => 'Hari Libur'],
            ['date' => '2025-12-31', 'name' => 'Cuti Bersama Akhir Tahun', 'description' => 'Hari Libur'],

            // 2026
            ['date' => '2026-01-01', 'name' => 'Tahun Baru', 'description' => 'Hari Tahun Baru Masehi'],
            ['date' => '2026-03-18', 'name' => 'Isra dan Mi\'raj', 'description' => 'Hari Besar Islam'],
            ['date' => '2026-03-25', 'name' => 'Jumat Agung', 'description' => 'Hari Raya Kristen'],
            ['date' => '2026-03-26', 'name' => 'Sabtu Paskah', 'description' => 'Hari Raya Kristen'],
            ['date' => '2026-03-27', 'name' => 'Minggu Paskah', 'description' => 'Hari Raya Kristen'],
            ['date' => '2026-03-30', 'name' => 'Hari Raya Idul Fitri', 'description' => 'Hari Besar Islam'],
            ['date' => '2026-03-31', 'name' => 'Hari Raya Idul Fitri', 'description' => 'Hari Besar Islam'],
            ['date' => '2026-04-01', 'name' => 'Cuti Bersama Idul Fitri', 'description' => 'Hari Libur'],
            ['date' => '2026-04-02', 'name' => 'Cuti Bersama Idul Fitri', 'description' => 'Hari Libur'],
            ['date' => '2026-04-03', 'name' => 'Cuti Bersama Idul Fitri', 'description' => 'Hari Libur'],
            ['date' => '2026-05-01', 'name' => 'Hari Buruh Internasional', 'description' => 'Hari Nasional'],
            ['date' => '2026-05-05', 'name' => 'Hari Raya Idul Adha', 'description' => 'Hari Besar Islam'],
            ['date' => '2026-05-15', 'name' => 'Tahun Baru Islam', 'description' => 'Hari Besar Islam'],
            ['date' => '2026-06-01', 'name' => 'Hari Sukarno', 'description' => 'Hari Nasional'],
            ['date' => '2026-06-02', 'name' => 'Hari Pendidikan Nasional', 'description' => 'Hari Nasional'],
            ['date' => '2026-06-24', 'name' => 'Mawlid Nabi Muhammad', 'description' => 'Hari Besar Islam'],
            ['date' => '2026-08-17', 'name' => 'Hari Kemerdekaan Indonesia', 'description' => 'Hari Nasional'],
            ['date' => '2026-12-25', 'name' => 'Hari Raya Natal', 'description' => 'Hari Raya Kristen'],
            ['date' => '2026-12-26', 'name' => 'Cuti Bersama Natal', 'description' => 'Hari Libur'],
            ['date' => '2026-12-31', 'name' => 'Cuti Bersama Akhir Tahun', 'description' => 'Hari Libur'],
        ];

        foreach ($holidays as $holiday) {
            Holiday::updateOrCreate(
                ['date' => $holiday['date']],
                $holiday
            );
        }
    }
}
