<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Refund;
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
        // transaksi where user id = auth user id, and status != proses, sort by created_at desc
        $transaksis = Transaksi::where('user_id', Auth::user()->id)
            ->where('status', '!=', 'proses')
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
        $transaksi = Transaksi::findOrFail($id);
        $transaksiDetail = TransaksiDetail::where('transaksi_id', $id)->get();

        return view('penjualan.show', compact('transaksi', 'transaksiDetail'));
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

        return view('penjualan.refund', compact('transaksi', 'transaksiDetail'));
    }

    public function refundStore(Request $request, string $id)
    {
        // dd($request->all());
        // dd(KasirController::test());

        $request->validate([
            'alasan' => 'required',
            'produk_id' => 'required|array',
            'jumlah_beli' => 'required|array',
            'harga_satuan' => 'required|array',
            'harga_total' => 'required|array',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        // change is_refunded to true
        $transaksi->is_refunded = true;
        $transaksi->save();

        Refund::create([
            'transaksi_id' => $transaksi->id,
            'alasan' => $request->alasan,
        ]);

        // ubah jumlah refund di transaksi detail sesuai dengan request
        foreach ($request->jumlah_beli as $key => $value) {
            $transaksiDetail = TransaksiDetail::findOrFail($key);
            $transaksiDetail->jumlah_refund = $value;
            $transaksiDetail->save();
        }

        Transaksi::create([
            'parent_id' => $transaksi->id,
            'user_id' => $transaksi->user_id,
            'pelanggan_id' => $transaksi->pelanggan_id,
            // kode transaksi replace TRX with RFN
            'kode' => str_replace('TRX', 'RFN', $transaksi->kode),
            'status' => 'refund',
            'nama_pembeli' => $transaksi->nama_pembeli,
            // 'is_counted' => false,
        ]);

        $newTransaksi = Transaksi::where('parent_id', $transaksi->id)->first();
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
        self::hitungStokLogic($newTransaksi);
        $newTransaksi->is_counted = true;
        $newTransaksi->save();

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

    public static function hitungStokLogic($transaksi)
    {
        // dd($transaksi);
        if (!$transaksi->is_counted) {
            $transaksiDetail = TransaksiDetail::where('transaksi_id', $transaksi->id)->get();
            foreach ($transaksiDetail as $item) {
                // create new record in inventaris
                Inventaris::create([
                    'produk_id' => $item->produk_id,
                    'transaksi_id' => $item->transaksi_id,
                    'stok' => $item->jumlah_beli,
                ]);
            }
        }
    }
}
