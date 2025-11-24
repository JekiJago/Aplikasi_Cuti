<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // cuma tambahkan kalau BELUM ada

            if (! Schema::hasColumn('users', 'hire_date')) {
                $table->date('hire_date')->nullable()->after('department');
            }

            if (! Schema::hasColumn('users', 'important_leave_used_days')) {
                $table->integer('important_leave_used_days')->default(0);
            }

            if (! Schema::hasColumn('users', 'big_leave_used_days')) {
                $table->integer('big_leave_used_days')->default(0);
            }

            if (! Schema::hasColumn('users', 'non_active_leave_used_days')) {
                $table->integer('non_active_leave_used_days')->default(0);
            }

            if (! Schema::hasColumn('users', 'sick_leave_used_days')) {
                $table->integer('sick_leave_used_days')->default(0);
            }

            if (! Schema::hasColumn('users', 'maternity_leave_used_count')) {
                $table->unsignedTinyInteger('maternity_leave_used_count')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // di down() boleh drop tanpa cek, tapi biar aman kita cek juga

            if (Schema::hasColumn('users', 'hire_date')) {
                $table->dropColumn('hire_date');
            }
            if (Schema::hasColumn('users', 'important_leave_used_days')) {
                $table->dropColumn('important_leave_used_days');
            }
            if (Schema::hasColumn('users', 'big_leave_used_days')) {
                $table->dropColumn('big_leave_used_days');
            }
            if (Schema::hasColumn('users', 'non_active_leave_used_days')) {
                $table->dropColumn('non_active_leave_used_days');
            }
            if (Schema::hasColumn('users', 'sick_leave_used_days')) {
                $table->dropColumn('sick_leave_used_days');
            }
            if (Schema::hasColumn('users', 'maternity_leave_used_count')) {
                $table->dropColumn('maternity_leave_used_count');
            }
        });
    }
};
