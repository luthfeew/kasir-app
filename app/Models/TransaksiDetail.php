<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'jumlah_beli',
        'harga_beli',
        'harga_total',
    ];

    // transaksi_detail punya satu transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    // transaksi_detail punya satu produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
