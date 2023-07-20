<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'parent_id',
        'user_id',
        'pelanggan_id',
        'kode',
        'status',
        'nama_pembeli',
        'is_counted',
        'is_lunas',
        'is_hutang',
        'is_refund',
        'alasan_refund',
        'waktu_transaksi'
    ];

    // transaksi punya satu parent
    public function parent()
    {
        return $this->belongsTo(Transaksi::class);
    }

    // transaksi punya satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // transaksi punya satu pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    // transaksi punya banyak transaksi detail
    public function transaksi_detail()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    // transaksi punya satu bayar
    public function bayar()
    {
        return $this->hasOne(Bayar::class);
    }

    // transaksi punya satu refund
    public function refund()
    {
        return $this->hasOne(Refund::class);
    }
}
