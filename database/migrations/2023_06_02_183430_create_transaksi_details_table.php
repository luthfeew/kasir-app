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
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();
            $table->integer('jumlah');
            $table->decimal('harga', 15, 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('transaksi_details', function (Blueprint $table) {
            $table->foreignId('produk_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transaksi_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_details');
    }
};
