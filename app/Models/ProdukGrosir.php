<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdukGrosir extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'produk_id',
        'minimal',
        'harga_grosir',
    ];

    // produk_grosir punya satu produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
