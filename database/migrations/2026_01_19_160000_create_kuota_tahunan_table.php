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
        Schema::create('kuota_tahunan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->year('tahun');
            $table->integer('kuota')->default(12); // Default 12 hari
            $table->integer('dipakai')->default(0);
            $table->boolean('expired')->default(false);
            $table->timestamps();

            // Unique constraint: satu user hanya punya satu kuota per tahun
            $table->unique(['user_id', 'tahun']);
            $table->index(['user_id', 'tahun']);
            $table->index(['tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuota_tahunan');
    }
};
