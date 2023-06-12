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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['prioritas', 'proses', 'pending', 'selesai', 'batal']);
            $table->decimal('harga_total', 15, 0)->nullable();
            $table->string('nama_pelanggan')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
