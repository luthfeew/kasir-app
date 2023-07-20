<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Bayar;
use App\Models\Inventaris;
use App\Http\Controllers\KasirController;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // transaksi where user id = auth user id, and status != proses, sort by waktu_transaksi desc
        $transaksis = Transaksi::where('user_id', Auth::user()->id)
            ->where('status', '!=', 'proses')
            ->orderBy('waktu_transaksi', 'desc')
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
        $transaksi = Transaksi::findOrFail($id);
        $transaksiDetail = TransaksiDetail::where('transaksi_id', $id)->get();
        // is_refundable = true jika ada item di transaksi detail yang memiliki jumlah_beli > jumlah_refund
        $is_refundable = false;
        foreach ($transaksiDetail as $item) {
            if ($item->jumlah_beli > $item->jumlah_refund) {
                $is_refundable = true;
            }
        }

        return view('penjualan.show', compact('transaksi', 'transaksiDetail', 'is_refundable'));
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
        $transaksi = Transaksi::findOrFail($id);
        $transaksiDetail = TransaksiDetail::where('transaksi_id', $id)->get();
        // jumlah_beli - jumlah_refund
        foreach ($transaksiDetail as $item) {
            $item->jumlah_beli = $item->jumlah_beli - $item->jumlah_refund;
        }

        return view('penjualan.refund', compact('transaksi', 'transaksiDetail'));
    }

    public function refundStore(Request $request, string $id)
    {
        // dd($request->all());
        // dd(KasirController::test());

        $request->validate([
            'alasan_refund' => 'required',
            'produk_id' => 'required|array',
            'jumlah_beli' => 'required|array',
            'harga_satuan' => 'required|array',
            'harga_total' => 'required|array',
        ]);

        // $transaksi = Transaksi::findOrFail($id);
        // // change is_refunded to true
        // $transaksi->is_refunded = true;
        // $transaksi->save();

        // ubah jumlah refund di transaksi detail jumlah sekarang = jumlah sebelumnya + jumlah refund
        foreach ($request->jumlah_beli as $key => $value) {
            $transaksiDetail = TransaksiDetail::find($key);
            $transaksiDetail->jumlah_refund = $transaksiDetail->jumlah_refund + $value;
            $transaksiDetail->save();
        }

        $transaksi = Transaksi::find($id);
        Transaksi::create([
            'parent_id' => $transaksi->id,
            'user_id' => $transaksi->user_id,
            'pelanggan_id' => $transaksi->pelanggan_id,
            'kode' => Str::replaceFirst('TRX', 'RFN', $transaksi->kode),
            'status' => 'selesai',
            'nama_pembeli' => $transaksi->nama_pembeli,
            'is_refund' => true,
            'alasan_refund' => $request->alasan_refund,
            'waktu_transaksi' => now(),
        ]);

        // $newTransaksi = Transaksi::where('parent_id', $transaksi->id)->first();
        $newTransaksi = Transaksi::where('parent_id', $transaksi->id)->get()->last(); 
        // foreach jumlah_beli, harga_satuan, harga_total, create new transaksi detail
        foreach ($request->jumlah_beli as $key => $value) {
            TransaksiDetail::create([
                'transaksi_id' => $newTransaksi->id,
                // 'produk_id' => $key,
                'produk_id' => $request->produk_id[$key],
                'jumlah_beli' => $value,
                'harga_satuan' => $request->harga_satuan[$key],
                'harga_total' => $request->harga_total[$key],
            ]);
        }

        // self::hitungStokLogic($newTransaksi);
        // $newTransaksi->is_counted = true;
        // $newTransaksi->save();

        // call hitungStokLogic from KasirController
        KasirController::hitungStokLogic($newTransaksi, true);
        $transaksi->is_counted = true;
        $transaksi->save();

        Bayar::create([
            'transaksi_id' => $newTransaksi->id,
            'harga_total' => $request->total_refund,
            'bayar' => $request->total_refund,
            'kembalian' => 0,
            'hutang' => 0,
            'is_refund' => true,
        ]);

        return redirect()->route('penjualan.index')->with('success', 'Refund berhasil.');
    }
}
