<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuti', function (Blueprint $table) {
            $table->id('id_cuti'); // primary key custom
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete()->cascadeOnUpdate();

            $table->integer('kuota_tahunan')->default(12);
            $table->integer('cuti_dipakai')->default(0);

            $table->timestamps();

            // supaya 1 pegawai cuma punya 1 row kuota cuti
            $table->unique('pegawai_id', 'uq_cuti_pegawai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuti');
    }
};
