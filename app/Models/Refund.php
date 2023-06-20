<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refund extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'transaksi_id',
        'alasan',
    ];

    // refund punya satu transaksi
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
