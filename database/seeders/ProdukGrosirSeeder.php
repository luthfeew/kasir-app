<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;

class ProdukGrosirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Produk::all() as $produk) {
            DB::table('produk_grosirs')->insert([
                'produk_id' => $produk->id,
                'minimal' => 10,
                'harga_grosir' => $produk->harga_jual - ($produk->harga_jual * 0.02),
            ]);
        }
    }
}
