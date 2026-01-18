<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';

    protected $primaryKey = 'id';

    protected $fillable = [
        'nip',
        'nrp',
        'nama',
        'jenis_kelamin',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->hasOne(User::class, 'pegawai_id', 'nip');
    }

    /**
     * Relasi ke Cuti
     */
    public function cuti()
    {
        return $this->hasOne(Cuti::class, 'pegawai_id');
    }

    /**
     * Get pegawai info
     */
    public function getInfoAttribute(): string
    {
        return "{$this->nip} - {$this->nama}";
    }
}
