<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function index($id = null)
    {
        return view('kasir.index', [
            'id' => $id,
        ]);
    }

    public function bayar(Request $request, $id = null)
    {
        if ($id) {
            $transaksi = Transaksi::find($id);
            $transaksi->status = 'selesai';
            $transaksi->harga_total = $request->harga_total;
            $transaksi->save();

            // kurangi stok id dari produk_id di transaksi_detail, sebanyak jumlah di transaksi_detail
            $transaksiDetail = TransaksiDetail::where('transaksi_id', $id)->get();
            foreach ($transaksiDetail as $item) {
                $this->kurangiStok($item->produk_id, $item->jumlah);
            }
        } else {
            $transaksi = Transaksi::where('user_id', Auth::user()->id)->where('status', 'proses')->first();
            $transaksi->status = 'selesai';
            $transaksi->harga_total = $request->harga_total;
            $transaksi->save();

            // kurangi stok id dari produk_id di transaksi_detail, sebanyak jumlah di transaksi_detail
            $transaksiDetail = TransaksiDetail::where('transaksi_id', $transaksi->id)->get();
            foreach ($transaksiDetail as $item) {
                $this->kurangiStok($item->produk_id, $item->jumlah);
            }
        }

        return redirect()->route('kasir');
    }

    public function simpan($id = null)
    {
        if ($id) {
            $transaksi = Transaksi::find($id);
            $transaksi->status = 'pending';
            $transaksi->save();
        } else {
            $transaksi = Transaksi::where('user_id', Auth::user()->id)->where('status', 'proses')->first();
            $transaksi->status = 'pending';
            $transaksi->save();
        }

        return redirect()->route('kasir');
    }

    public function hapus($id = null)
    {
        if ($id) {
            TransaksiDetail::where('transaksi_id', $id)->delete();
        } else {
            $transaksi = Transaksi::where('user_id', Auth::user()->id)->where('status', 'proses')->first();
            TransaksiDetail::where('transaksi_id', $transaksi->id)->delete();
        }

        return redirect()->route('kasir');
    }

    function kurangiStok($id, $qty)
    {
        $produk = Produk::find($id);
        $produk->stok -= $qty;
        $produk->save();
    }
}
