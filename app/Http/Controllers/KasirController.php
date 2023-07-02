<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Bayar;
use App\Models\Inventaris;

class KasirController extends Controller
{
    public function __construct()
    {
        // call function cekSesi() from HomeController, if it returns null, then redirect to dashboard
        $this->middleware(function ($request, $next) {
            $sesi = HomeController::cekSesi();
            if (!$sesi) {
                return redirect()->route('dashboard')->with('error', 'Anda belum membuka kasir.');
            }
            return $next($request);
        });
    }

    public function index($id = null)
    {
        // get all transaksi where status = pending
        $transaksi_pending = Transaksi::where('status', 'pending')->get();
        // get all transaksi where status = hutang and user_id = auth user id
        $transaksi_hutang = Transaksi::where('status', 'hutang')->get();

        // check if $id status is hutang, if hutang then create new transaksi
        // if ($id) {
        //     if (Transaksi::find($id)->status == 'hutang') {
        //         $transaksi = Transaksi::find($id);
        //         $transaksi->status = 'proses';
        //         $transaksi->save();
        //     }
        // }

        return view('kasir.index', [
            'id' => $id,
            'transaksi_pending' => $transaksi_pending,
            'transaksi_hutang' => $transaksi_hutang,
        ]);
    }

    public function bayar(Request $request, $id = null)
    {
        // dd($request->all());
        $request->validate([
            'bayar' => 'required|numeric',
        ]);

        if ($id) {
            $transaksi = Transaksi::find($id);
        } else {
            $transaksi = Transaksi::where('user_id', Auth::user()->id)->where('status', 'proses')->first();
        }

        self::hitungStokLogic($transaksi);
        // $transaksi->status = 'selesai';
        $transaksi->is_counted = true;
        if ($request->hutang > 0) {
            $transaksi->status = 'hutang';
        } else {
            $transaksi->status = 'selesai';
        }
        $transaksi->save();

        Bayar::create([
            'transaksi_id' => $transaksi->id,
            'bayar' => $request->bayar,
            'harga_total' => $request->tagihan,
            'kembalian' => $request->kembalian,
            'hutang' => $request->hutang,
        ]);

        return redirect()->route('kasir')->with('success', 'Transaksi berhasil dibayar.');
    }

    public static function hitungStokLogic($transaksi)
    {
        if (!$transaksi->is_counted) {
            $transaksiDetail = TransaksiDetail::where('transaksi_id', $transaksi->id)->get();
            foreach ($transaksiDetail as $item) {
                // create new record in inventaris
                Inventaris::create([
                    'produk_id' => $item->produk_id,
                    'transaksi_id' => $item->transaksi_id,
                    'stok' => -$item->jumlah_beli,
                ]);
            }
        }
    }
}
