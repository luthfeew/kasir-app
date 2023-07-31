<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PrintController;
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
        $transaksiPending = Transaksi::where('user_id', Auth::user()->id)->where('status', 'pending')->get();
        $transaksiHutang = Transaksi::where('is_hutang', true)->where('is_lunas', false)->get();

        return view('kasir.index', [
            'transaksiId' => $id,
            'transaksiPending' => $transaksiPending,
            'transaksiHutang' => $transaksiHutang,
        ]);
    }

    public function getTransaksi($id)
    {
        if ($id) {
            $transaksi = Transaksi::findOrFail($id);
        } else {
            $transaksi = Transaksi::where('user_id', Auth::user()->id)->where('status', 'proses')->first();
        }

        return $transaksi;
    }

    public function bayar(Request $request, string $id = null)
    {
        // dd($id);
        // dd($request->all());

        $request->validate([
            'bayar' => 'required|numeric|min:1',
            'tagihan' => 'required|numeric|min:1',
            'kembalian' => 'required|numeric|min:0',
            'hutang' => 'required|numeric|min:0',
        ]);

        $transaksi = $this->getTransaksi($id);

        // cek transaksi where id = $parent_id
        $parent = Transaksi::find($transaksi->parent_id);
        if ($parent) {
            $parent->is_lunas = true;
            $parent->save();
        }

        $this->hitungStokLogic($transaksi);
        $transaksi->is_lunas = $request->bayar >= $request->tagihan;
        $transaksi->is_hutang = $request->bayar < $request->tagihan;
        $transaksi->status = 'selesai';
        $transaksi->waktu_transaksi = now();
        $transaksi->save();

        Bayar::create([
            'transaksi_id' => $transaksi->id,
            'bayar' => $request->bayar,
            'harga_total' => $request->tagihan,
            'kembalian' => $request->kembalian,
            'hutang' => $request->hutang,
        ]);

        // PRINT_HERE
        // PrintController::printPesanan($transaksi->id);
        try {
            PrintController::printPesanan($transaksi->id);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return redirect()->route('kasir')->with('success', 'Transaksi berhasil dibayar.');
    }

    public function simpan(string $id = null)
    {
        $transaksi = $this->getTransaksi($id);

        $this->hitungStokLogic($transaksi);
        $transaksi->status = 'pending';
        $transaksi->save();

        return redirect()->route('kasir')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function hapus(string $id = null)
    {
        $transaksi = $this->getTransaksi($id);
        $transaksi->forceDelete();

        return redirect()->route('kasir')->with('success', 'Transaksi berhasil dihapus.');
    }

    public function bayarHutang(string $id)
    {
        // cek apakah ada transaksi dengan status proses dan memiliki transaksi detail, jika ada ubah statusnya menjadi pending
        $old = Transaksi::where('user_id', Auth::user()->id)->where('status', 'proses')->first();
        if ($old) {
            if (TransaksiDetail::where('transaksi_id', $old->id)->count() > 0) {
                $old->status = 'pending';
                $old->save();
            } else {
                $old->forceDelete();
            }
        }

        // cek apakah ada transaksi dengan parent_id = $id dan status != selesai, jika ada maka redirect ke halaman kasir dengan transaksi tersebut
        $cek = Transaksi::where('parent_id', $id)->where('status', '!=', 'selesai')->first();
        // dd($cek);
        if ($cek) {
            return redirect()->route('kasir', $cek->id);
        }

        // buat transaksi baru dengan status proses
        $transaksi = Transaksi::findOrFail($id);
        $new = Transaksi::create([
            'parent_id' => $id,
            'user_id' => Auth::user()->id,
            'pelanggan_id' => $transaksi->pelanggan_id,
            'kode' => 'TRX' . date('ymd') . Str::padLeft(Transaksi::where('user_id', Auth::id())->whereDate('waktu_transaksi', date('Y-m-d'))->count() + 1, 4, '0'),
            'status' => 'proses',
            'nama_pembeli' => $transaksi->nama_pembeli,
            'waktu_transaksi' => now(),
        ]);

        // redirect ke halaman kasir dengan transaksi baru
        return redirect()->route('kasir', $new->id);
    }

    public static function hitungStokLogic($transaksi, $refund = false)
    {
        if ($transaksi->is_counted) {
            return;
        }

        $transaksiDetail = TransaksiDetail::where('transaksi_id', $transaksi->id)->get();
        foreach ($transaksiDetail as $item) {
            // create new record in inventaris table
            Inventaris::create([
                'produk_id' => $item->produk_id,
                'transaksi_id' => $item->transaksi_id,
                'stok' => $refund ? $item->jumlah_beli : -$item->jumlah_beli,
            ]);
        }

        $transaksi->is_counted = true;
    }
}
