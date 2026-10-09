<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Barang extends Model
{
    protected $fillable = [
        'sku',
        'nama',
        'category_id',
        'satuan',
        'harga_pokok',
        'harga_jual',
        'aktif',
    ];


    public function category(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id', 'id');
    }
}