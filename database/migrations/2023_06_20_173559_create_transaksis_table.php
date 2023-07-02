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
            $table->foreignId('parent_id')->nullable()->constrained('transaksis')->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pelanggan_id')->nullable()->constrained()->nullOnDelete();
            $table->string('kode');
            $table->enum('status', ['proses', 'selesai', 'pending', 'hutang', 'refund'])->default('proses');
            $table->string('nama_pembeli')->nullable();
            $table->boolean('is_counted')->default(false);
            $table->boolean('is_melunasi')->default(false);
            $table->boolean('is_refunded')->default(false);
            $table->timestamps();
            $table->softDeletes();
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
