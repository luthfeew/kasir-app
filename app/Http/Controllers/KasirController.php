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
        $transaksi_pending = Transaksi::where('status', 'pending')->get();
        $transaksi_hutang = Transaksi::where('status', 'hutang')->where('is_melunasi', false)->get();

        if ($id) {
            // cek apakah transaksi hutang atau tidak
            $transaksi = Transaksi::find($id);
            if ($transaksi->status == 'hutang') {
                // cek apakah ada transaksi yang statusnya proses dengan parent_id = $id
                $ada = Transaksi::where('parent_id', $id)->where('status', 'proses')->first();
                if ($ada) {
                    // redirect ke transaksi tersebut
                    return redirect()->route('kasir', $ada->id);
                } else {
                    // buat transaksi baru
                    $transaksi = Transaksi::create([
                        'parent_id' => $id,
                        'user_id' => Auth::user()->id,
                        'pelanggan_id' => $transaksi->pelanggan_id,
                        'kode' => 'TRX' . date('YmdHis'),
                        'status' => 'proses',
                        'nama_pembeli' => $transaksi->nama_pembeli,
                        'is_melunasi' => true,
                    ]);

                    // redirect to kasir with new transaksi id
                    return redirect()->route('kasir', $transaksi->id);
                }
            }
        }

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

        // if transaksi has parent_id, then update parent_id is_melunasi to true
        if ($transaksi->parent_id) {
            $parent = Transaksi::find($transaksi->parent_id);
            $parent->is_melunasi = true;
            $parent->save();
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
