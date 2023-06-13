<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdukKategoriController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\PenjualanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.auth');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    Route::get('/buka_kasir', [HomeController::class, 'buka_kasir'])->name('buka_kasir');
    Route::post('/buka_kasir', [HomeController::class, 'buka_kasir_store'])->name('buka_kasir.store');
    Route::get('/tutup_kasir', [HomeController::class, 'tutup_kasir'])->name('tutup_kasir');
    Route::post('/tutup_kasir', [HomeController::class, 'tutup_kasir_store'])->name('tutup_kasir.store');

    Route::get('/kasir/{id?}', [KasirController::class, 'index'])->name('kasir');
    Route::post('/kasir/bayar/{id?}', [KasirController::class, 'bayar'])->name('kasir.bayar');
    Route::post('/kasir/simpan/{id?}', [KasirController::class, 'simpan'])->name('kasir.simpan');
    Route::post('/kasir/hapus/{id?}', [KasirController::class, 'hapus'])->name('kasir.hapus');

    Route::resource('penjualan', PenjualanController::class);

    Route::get('/laporan/ringkasan_penjualan', function () {
        return view('laporan.ringkasan_penjualan');
    });

    Route::resource('gudang/kategori', ProdukKategoriController::class);
    Route::resource('gudang/produk', ProdukController::class);
});
