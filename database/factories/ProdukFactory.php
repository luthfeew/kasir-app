<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $harga = $this->faker->numberBetween(1000, 100000);
        return [
            'produk_kategori_id' => $this->faker->numberBetween(1, 5), // 'produk_kategori_id' => 'factory|App\Models\ProdukKategori
            'nama' => $this->faker->unique()->word(),
            'sku' => $this->faker->unique()->numberBetween(1000000000, 9999999999),
            'harga_beli' => $harga,
            'harga_jual' => $harga + ($harga * 0.1),
            'harga_pelanggan' => $harga + ($harga * 0.05),
            'satuan' => 'pcs',
        ];
    }
}
