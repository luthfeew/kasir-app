<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'produk_kategori_id',
        'nama',
        'sku',
        'harga_beli',
        'harga_jual',
        'satuan',
    ];

    // produk punya satu kategori
    public function produkKategori()
    {
        return $this->belongsTo(ProdukKategori::class);
    }

    // produk punya banyak inventaris
    public function inventaris()
    {
        return $this->hasMany(Inventaris::class);
    }

    // produk punya banyak produk grosir
    public function produkGrosir()
    {
        return $this->hasMany(ProdukGrosir::class);
    }

    // produk punya banyak transaksi detail
    public function transaksiDetail()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
