<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'nama' => 'Test Admin',
            'username' => 'admin',
            'password' => Hash::make('123456'),
            'role' => 'admin',
        ]);

        // DEBUGGING ONLY
        \App\Models\User::factory(9)->create();
        // $this->call(ProdukKategoriSeeder::class);
        // \App\Models\Produk::factory(100)->create();
        // $this->call(ProdukGrosirSeeder::class);
    }
}
