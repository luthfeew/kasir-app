<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventaris extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'produk_id',
        'transaksi_id',
        'stok',
    ];

    // inventaris punya satu produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    // inventaris punya satu transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
