<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'sku',
        'stok',
        'harga_beli',
        'harga_jual',
        'satuan',
        'produk_kategori_id',
    ];
}
