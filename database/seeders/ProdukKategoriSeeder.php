<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('produk_kategoris')->insert([
            [
                'nama' => 'Makanan',
                'urutan' => 1,
            ],
            [
                'nama' => 'Minuman',
                'urutan' => 2,
            ],
            [
                'nama' => 'Snack',
                'urutan' => 3,
            ],
            [
                'nama' => 'Perlengkapan',
                'urutan' => 4,
            ],
            [
                'nama' => 'Lainnya',
                'urutan' => 5,
            ],
        ]);
    }
}
