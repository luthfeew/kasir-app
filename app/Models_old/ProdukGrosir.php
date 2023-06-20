<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukGrosir extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelipatan',
        'harga',
        'produk_id',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
