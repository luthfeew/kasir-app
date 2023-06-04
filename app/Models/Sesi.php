<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sesi extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'waktu_mulai',
        'waktu_selesai',
        'saldo_awal',
        'saldo_akhir',
        'user_id',
    ];
}
