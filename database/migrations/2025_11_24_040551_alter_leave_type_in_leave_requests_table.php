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
    DB::statement("ALTER TABLE leave_requests 
        MODIFY leave_type ENUM(
            'tahunan',
            'urusan_penting',
            'cuti_besar',
            'cuti_non_aktif',
            'cuti_bersalin',
            'cuti_sakit'
        ) NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            //
        });
    }
};
