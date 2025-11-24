<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // bigint unsigned auto increment
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('employee_id')->unique();
            $table->string('position');
            $table->string('department')->nullable();
            $table->enum('role', ['employee', 'admin'])->default('employee');
            $table->string('avatar')->nullable();
            $table->integer('annual_leave_quota')->default(12);
            $table->integer('used_leave_days')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
