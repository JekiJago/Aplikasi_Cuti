<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $table = 'libur';
    
    protected $fillable = ['date', 'name', 'keterangan'];

    protected $casts = [
        'date' => 'date',
    ];
}
