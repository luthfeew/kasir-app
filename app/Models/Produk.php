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

    public function kategori()
    {
        return $this->belongsTo(ProdukKategori::class, 'produk_kategori_id');
    }

    public function grosir()
    {
        return $this->hasMany(ProdukGrosir::class, 'produk_id');
    }
}
