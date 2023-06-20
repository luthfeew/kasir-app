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
        Schema::create('produk_grosirs', function (Blueprint $table) {
            $table->id();
            $table->integer('kelipatan');
            $table->decimal('harga', 15, 0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('produk_grosirs', function (Blueprint $table) {
            $table->foreignId('produk_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_grosirs');
    }
};
