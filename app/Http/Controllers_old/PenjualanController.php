<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\ProdukGrosir;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KasirController;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get all transaksi where status = selesai, order by created_at desc, user_id = auth()->user()->id
        $transaksis = Transaksi::where('status', 'selesai')
        ->orWhere('status', 'refund')
        ->where('user_id', Auth::user()->id)
        ->orderBy('created_at', 'desc')
        ->get();

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
                if (abs($item->jumlah) >= $grosir->kelipatan) {
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

    public function refund(string $id)
    {
        $transaksi = Transaksi::where('id', $id)->first();
        $transaksiDetail = TransaksiDetail::where('transaksi_id', $id)->get();

        // cek jika jumlah produk sudah bisa grosir, maka tampilkan harga grosir
        foreach ($transaksiDetail as $item) {
            $produk = Produk::find($item->produk_id);
            $produkGrosir = ProdukGrosir::where('produk_id', $produk->id)->get();
            foreach ($produkGrosir as $grosir) {
                if ($item->jumlah >= $grosir->kelipatan) {
                    $item->produk->harga_jual = $grosir->harga;
                }
            }
        }

        return view('penjualan.refund', compact('transaksi', 'transaksiDetail'));
    }

    public function refund_store(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'refund' => 'required',
            'qty' => 'required',
        ]);

        $old = Transaksi::find($request->transaksi_id);

        // check if old transaksi is refunded
        if ($old->refunded) {
            return redirect()->back()->with('error', 'Transaksi ini sudah pernah direfund');
        }

        $refund = $request->refund;

        if ($refund) {
            $old->refunded = true;
            $old->save();

            $transaksi = Transaksi::create([
                'status' => 'refund',
                'nama_pelanggan' => $request->nama_pelanggan,
                'alasan_refund' => $request->alasan_refund,
                'user_id' => Auth::user()->id,
                'parent_id' => $request->transaksi_id,
                'harga_total' => $request->harga_total,
            ]);

            // create new transaksi detail
            foreach ($refund as $key => $value) {
                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $request->produk_id[$key],
                    'jumlah' => -$request->qty[$key],
                    'harga_satuan_refund' => $request->harga_satuan_refund[$key],
                ]);
            }

            // call function from KasirController called kurangiStokLogic
            KasirController::kurangiStokLogic($transaksi);
        }

        return redirect()->route('penjualan.index')->with('success', 'Transaksi berhasil direfund');
    }
}
