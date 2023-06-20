<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_transaksi',
        'nominal',
        'jenis',
        'catatan',
        'user_id',
    ];
}
