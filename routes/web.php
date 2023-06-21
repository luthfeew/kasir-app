<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProdukKategoriController;
use App\Http\Controllers\ProdukController;
// use App\Http\Controllers\KasirController;
// use App\Http\Controllers\PenjualanController;
// use App\Http\Controllers\KaryawanController;
// use App\Http\Controllers\LaporanController;

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

    Route::get('/buka_kasir', [HomeController::class, 'bukaKasir'])->name('buka_kasir');
    Route::post('/buka_kasir', [HomeController::class, 'bukaKasirStore'])->name('buka_kasir.store');
    Route::get('/tutup_kasir', [HomeController::class, 'tutupKasir'])->name('tutup_kasir');
    Route::post('/tutup_kasir', [HomeController::class, 'tutupKasirStore'])->name('tutup_kasir.store');

    Route::get('/kasir/{id?}', [KasirController::class, 'index'])->name('kasir');
    Route::post('/kasir/bayar/{id?}', [KasirController::class, 'bayar'])->name('kasir.bayar');
    Route::post('/kasir/simpan/{id?}', [KasirController::class, 'simpan'])->name('kasir.simpan');
    Route::post('/kasir/hapus/{id?}', [KasirController::class, 'hapus'])->name('kasir.hapus');

    Route::resource('penjualan', PenjualanController::class);
    Route::get('/penjualan/{id}/refund', [PenjualanController::class, 'refund'])->name('penjualan.refund');
    Route::post('/penjualan/{id}/refund', [PenjualanController::class, 'refundStore'])->name('penjualan.refund.store');

    Route::get('/laporan/ringkasan_penjualan', [LaporanController::class, 'ringkasanPenjualan'])->name('laporan.ringkasan_penjualan');
    Route::get('/laporan/top_report', [LaporanController::class, 'topReport'])->name('laporan.top_report');
    Route::get('/laporan/tutup_kasir', [LaporanController::class, 'tutupKasir'])->name('laporan.tutup_kasir');
    Route::get('/laporan/kas_kasir', [LaporanController::class, 'kasKasir'])->name('laporan.kas_kasir');
    Route::get('/laporan/kas_kasir/create', [LaporanController::class, 'kasKasirCreate'])->name('laporan.kas_kasir.create');
    Route::post('/laporan/kas_kasir/create', [LaporanController::class, 'kasKasirStore'])->name('laporan.kas_kasir.store');

    Route::resource('gudang/kategori', ProdukKategoriController::class);
    Route::resource('gudang/produk', ProdukController::class);

    Route::resource('karyawan', KaryawanController::class);

    Route::post('/test', [LaporanController::class, 'test'])->name('test');
});
