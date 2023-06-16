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
            'transaksi_pending' => Transaksi::where('user_id', Auth::user()->id)->where('status', 'pending')->get(),
        ]);
    }

    public function bayar(Request $request, $id = null)
    {
        if ($id) {
            $transaksi = Transaksi::find($id);
        } else {
            $transaksi = Transaksi::where('user_id', Auth::user()->id)->where('status', 'proses')->first();
        }

        $this->kurangiStokLogic($transaksi);

        $transaksi->status = 'selesai';
        $transaksi->harga_total = $request->harga_total;
        $transaksi->stok_kurang = true;
        $transaksi->save();

        return redirect()->route('kasir');
    }

    public function simpan(Request $request, $id = null)
    {
        if ($id) {
            $transaksi = Transaksi::find($id);
        } else {
            $transaksi = Transaksi::where('user_id', Auth::user()->id)->where('status', 'proses')->first();
        }

        $this->kurangiStokLogic($transaksi);

        $transaksi->status = 'pending';
        $transaksi->harga_total = $request->harga_total;
        $transaksi->stok_kurang = true;
        $transaksi->save();

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

    public static function kurangiStokLogic($transaksi)
    {
        if (!$transaksi->stok_kurang) {
            $transaksiDetail = TransaksiDetail::where('transaksi_id', $transaksi->id)->get();
            foreach ($transaksiDetail as $item) {
                // $this->kurangiStok($item->produk_id, $item->jumlah);
                self::kurangiStok($item->produk_id, $item->jumlah);
            }
        }
    }

    public static function kurangiStok($id, $qty)
    {
        $produk = Produk::find($id);
        $produk->stok -= $qty;
        $produk->save();
    }
}
