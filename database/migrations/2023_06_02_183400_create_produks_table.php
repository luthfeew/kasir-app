<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->string('sku')->unique();
            $table->integer('stok');
            $table->decimal('harga_beli', 15, 0);
            $table->decimal('harga_jual', 15, 0);
            $table->string('satuan');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('produks', function (Blueprint $table) {
            $table->foreignId('produk_kategori_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
