<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bayar extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaksi_id',
        'harga_total',
        'bayar',
        'kembalian',
        'hutang',
        'is_refund',
    ];

    // bayar punya satu transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
