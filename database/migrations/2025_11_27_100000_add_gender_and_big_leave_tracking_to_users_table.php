<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female'])->nullable()->after('department');
            }

            if (! Schema::hasColumn('users', 'big_leave_last_used_at')) {
                $table->timestamp('big_leave_last_used_at')->nullable()->after('big_leave_used_days');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'gender')) {
                $table->dropColumn('gender');
            }

            if (Schema::hasColumn('users', 'big_leave_last_used_at')) {
                $table->dropColumn('big_leave_last_used_at');
            }
        });
    }
};
