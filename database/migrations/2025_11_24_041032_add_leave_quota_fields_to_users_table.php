<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // tanggal mulai kerja (buat syarat 5 tahun)
            $table->date('hire_date')->nullable()->after('department');

            // tracking pemakaian jenis cuti (lifetime)
            $table->integer('important_leave_used_days')->default(0);   // urusan penting, max 30
            $table->integer('big_leave_used_days')->default(0);         // cuti besar, max 90
            $table->integer('non_active_leave_used_days')->default(0);  // cuti non aktif, max 365/730
            $table->integer('sick_leave_used_days')->default(0);        // cuti sakit, max 540

            // tracking cuti bersalin (berapa kali sudah)
            $table->unsignedTinyInteger('maternity_leave_used_count')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
