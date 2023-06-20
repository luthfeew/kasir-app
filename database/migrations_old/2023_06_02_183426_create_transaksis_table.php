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
            $table->enum('status', ['proses', 'pending', 'selesai', 'refund']);
            $table->decimal('harga_total', 15, 0)->nullable();
            $table->string('nama_pelanggan')->nullable();
            $table->boolean('stok_kurang')->default(false);
            $table->boolean('refunded')->default(false);
            $table->string('alasan_refund')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('transaksis', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('transaksis')->cascadeOnDelete();
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
