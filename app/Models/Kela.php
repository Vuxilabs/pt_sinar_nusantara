<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kela extends Model
{
    protected $fillable = [
        'nama_kelas',
        'deskripsi',
    ];
}