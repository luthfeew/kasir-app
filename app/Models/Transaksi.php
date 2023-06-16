<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'harga_total',
        'nama_pelanggan',
        'stok_kurang',
        'refunded',
        'alasan_refund',
        'user_id',
        'parent_id',
    ];

    public function transaksiDetail()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
