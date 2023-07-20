<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_transaksi',
        'nominal',
        'jenis',
        'catatan',
        'user_id',
    ];

    // kas punya satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
