<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

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
    Route::get('/', function () {
        return view('test');
    });

    Route::get('/penjualan', function () {
        return view('penjualan.index');
    });

    Route::get('/laporan/ringkasan_penjualan', function () {
        return view('laporan.ringkasan_penjualan');
    });

    Route::get('/gudang/produk', function () {
        return view('gudang.produk');
    });

    Route::get('/gudang/kategori', function () {
        return view('gudang.kategori');
    });
});
