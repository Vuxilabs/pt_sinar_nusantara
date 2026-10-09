<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Siswa extends Model
{
    protected $fillable = [
        'nama',
        'nis',
        'kelas_id',
    ];


    public function kela(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Kela::class, 'kelas_id', 'id');
    }
}