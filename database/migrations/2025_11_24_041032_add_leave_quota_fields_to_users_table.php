<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek dulu apakah kolom sudah ada
            if (!Schema::hasColumn('users', 'annual_leave_quota')) {
                $table->integer('annual_leave_quota')->default(12);
            }
            
            if (!Schema::hasColumn('users', 'sick_leave_quota')) {
                $table->integer('sick_leave_quota')->default(12);
            }
            
            if (!Schema::hasColumn('users', 'personal_leave_quota')) {
                $table->integer('personal_leave_quota')->default(12);
            }
            
            if (!Schema::hasColumn('users', 'important_leave_quota')) {
                $table->integer('important_leave_quota')->default(30);
            }
            
            if (!Schema::hasColumn('users', 'big_leave_quota')) {
                $table->integer('big_leave_quota')->default(90);
            }
            
            if (!Schema::hasColumn('users', 'non_active_leave_quota')) {
                $table->integer('non_active_leave_quota')->default(365);
            }
            
            if (!Schema::hasColumn('users', 'maternity_leave_quota')) {
                $table->integer('maternity_leave_quota')->default(90);
            }
            
            if (!Schema::hasColumn('users', 'paternity_leave_quota')) {
                $table->integer('paternity_leave_quota')->default(14);
            }
            
            if (!Schema::hasColumn('users', 'marriage_leave_quota')) {
                $table->integer('marriage_leave_quota')->default(3);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom jika ada
            $columns = [
                'annual_leave_quota',
                'sick_leave_quota',
                'personal_leave_quota',
                'important_leave_quota',
                'big_leave_quota',
                'non_active_leave_quota',
                'maternity_leave_quota',
                'paternity_leave_quota',
                'marriage_leave_quota'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};