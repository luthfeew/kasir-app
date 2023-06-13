<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\ProdukGrosir;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get all transaksi where status = selesai, order by created_at desc
        $transaksis = Transaksi::where('status', 'selesai')->orderBy('created_at', 'desc')->get();
        
        return view('penjualan.index', compact('transaksis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaksi = Transaksi::where('id', $id)->first();
        $transaksiDetail = TransaksiDetail::where('transaksi_id', $id)->get();

        // cek jika jumlah produk sudah bisa grosir, maka tampilkan harga grosir
        foreach ($transaksiDetail as $item) {
            $produk = Produk::find($item->produk_id);
            $produkGrosir = ProdukGrosir::where('produk_id', $produk->id)->get();
            foreach ($produkGrosir as $grosir) {
                if ($item->jumlah >= $grosir->kelipatan) {
                    $item->produk->harga_jual_grosir = $grosir->harga;
                }
            }
        }

        return view('penjualan.detail', compact('transaksi', 'transaksiDetail'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
