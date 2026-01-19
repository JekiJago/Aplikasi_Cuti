<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KuotaTahunan extends Model
{
    use HasFactory;

    protected $table = 'kuota_tahunan';

    protected $fillable = [
        'user_id',
        'tahun',
        'kuota',
        'dipakai',
        'expired',
    ];

    protected $casts = [
        'expired' => 'boolean',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor: Sisa kuota yang belum dipakai
     */
    public function getSisaAttribute(): int
    {
        return max($this->kuota - $this->dipakai, 0);
    }

    /**
     * Scope: Ambil kuota yang masih aktif (belum expired)
     */
    public function scopeAktif($query)
    {
        return $query->where('expired', false);
    }

    /**
     * Scope: Ambil kuota berdasarkan rentang tahun (FIFO)
     * Untuk tahun sekarang, bisa ambil tahun sekarang dan tahun lalu
     */
    public static function getKuotaAktif($userId, $tahunSekarang = null)
    {
        $tahunSekarang = $tahunSekarang ?? now()->year;
        $tahunLalu = $tahunSekarang - 1;

        return self::where('user_id', $userId)
            ->whereIn('tahun', [$tahunLalu, $tahunSekarang])
            ->where('expired', false)
            ->orderBy('tahun', 'asc') // FIFO: tahun lama dulu
            ->get();
    }

    /**
     * Hitung total sisa kuota aktif (FIFO)
     */
    public static function getTotalSisaKuota($userId, $tahunSekarang = null)
    {
        $kuotas = self::getKuotaAktif($userId, $tahunSekarang);
        return $kuotas->sum('sisa');
    }

    /**
     * Potong kuota dengan sistem FIFO
     * Potong tahun lama dulu, baru ke tahun baru
     */
    public static function potonganKuota($userId, $jumlahHari, $tahunSekarang = null)
    {
        $tahunSekarang = $tahunSekarang ?? now()->year;
        $kuotas = self::getKuotaAktif($userId, $tahunSekarang);

        $sisaPotongan = $jumlahHari;

        foreach ($kuotas as $kuota) {
            if ($sisaPotongan <= 0) break;

            $sisa = $kuota->sisa;

            if ($sisa >= $sisaPotongan) {
                // Kuota ini cukup untuk memenuhi kebutuhan
                $kuota->increment('dipakai', $sisaPotongan);
                $sisaPotongan = 0;
            } else {
                // Gunakan semua kuota tahun ini, lanjut ke tahun berikutnya
                $kuota->increment('dipakai', $sisa);
                $sisaPotongan -= $sisa;
            }
        }

        return $sisaPotongan; // Return 0 jika berhasil, >0 jika kuota kurang
    }

    /**
     * Kembalikan kuota (reverse potongan)
     * Kembalikan mulai dari tahun baru ke tahun lama (LIFO)
     */
    public static function kembalikanKuota($userId, $jumlahHari, $tahunSekarang = null)
    {
        $tahunSekarang = $tahunSekarang ?? now()->year;
        $kuotas = self::getKuotaAktif($userId, $tahunSekarang);

        $sisaPengembalian = $jumlahHari;

        // Reverse: tahun baru dulu
        foreach ($kuotas->reverse() as $kuota) {
            if ($sisaPengembalian <= 0) break;

            $dipakai = $kuota->dipakai;

            if ($dipakai >= $sisaPengembalian) {
                // Bisa mengembalikan semua
                $kuota->decrement('dipakai', $sisaPengembalian);
                $sisaPengembalian = 0;
            } else {
                // Kembalikan sebanyak yang dipakai, lanjut ke tahun sebelumnya
                $kuota->decrement('dipakai', $dipakai);
                $sisaPengembalian -= $dipakai;
            }
        }

        return $sisaPengembalian;
    }

    /**
     * Set expired untuk kuota tahun lalu
     * Dipanggil saat awal tahun baru
     */
    public static function markExpiredPreviousYear($tahunSekarang = null)
    {
        $tahunSekarang = $tahunSekarang ?? now()->year;
        $tahunLalu = $tahunSekarang - 1;

        self::where('tahun', $tahunLalu)
            ->where('expired', false)
            ->update(['expired' => true]);
    }
}
