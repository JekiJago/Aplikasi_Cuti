<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('quota_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->integer('year');
            $table->integer('old_quota');
            $table->integer('new_quota');
            $table->text('reason');
            $table->timestamps();
            
            $table->index(['user_id', 'year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('quota_adjustments');
    }
};