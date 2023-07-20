<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProdukKategori extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'urutan',
    ];

    // produk kategori punya banyak produk
    public function produk()
    {
        return $this->hasMany(Produk::class);
    }
}
