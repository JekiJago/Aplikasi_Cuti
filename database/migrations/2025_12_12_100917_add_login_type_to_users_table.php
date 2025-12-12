<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('login_type')->default('employee_id')->after('role');
        });
        
        // Update existing records
        User::where('role', 'admin')->update(['login_type' => 'email']);
        User::where('role', 'employee')->update(['login_type' => 'employee_id']);
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('login_type');
        });
    }
};