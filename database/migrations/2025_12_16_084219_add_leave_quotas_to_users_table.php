<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('sick_leave_quota')->nullable()->after('hire_date');
            $table->integer('personal_leave_quota')->nullable();
            $table->integer('important_leave_quota')->nullable();
            $table->integer('big_leave_quota')->nullable();
            $table->integer('non_active_leave_quota')->nullable();
            $table->integer('maternity_leave_quota')->nullable();
            $table->integer('paternity_leave_quota')->nullable();
            $table->integer('marriage_leave_quota')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'sick_leave_quota',
                'personal_leave_quota',
                'important_leave_quota',
                'big_leave_quota',
                'non_active_leave_quota',
                'maternity_leave_quota',
                'paternity_leave_quota',
                'marriage_leave_quota',
            ]);
        });
    }
};
