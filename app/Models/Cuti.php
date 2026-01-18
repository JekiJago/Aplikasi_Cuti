<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    protected $table = 'cuti';

    protected $primaryKey = 'id_cuti';

    protected $fillable = [
        'pegawai_id',
        'kuota_tahunan',
        'cuti_dipakai',
    ];

    /**
     * Relasi ke Pegawai
     */
    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    /**
     * Get remaining leave days
     */
    public function getRemainingDaysAttribute(): int
    {
        return max($this->kuota_tahunan - $this->cuti_dipakai, 0);
    }

    /**
     * Increment used leave days
     */
    public function incrementUsedDays(int $days): void
    {
        $this->increment('cuti_dipakai', $days);
    }

    /**
     * Decrement used leave days
     */
    public function decrementUsedDays(int $days): void
    {
        $this->decrement('cuti_dipakai', $days);
    }
}
