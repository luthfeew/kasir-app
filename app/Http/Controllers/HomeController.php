<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PrintController;
use App\Models\Sesi;
use App\Models\Kas;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Bayar;

class HomeController extends Controller
{
    public static function cekSesi()
    {
        // cek apakah user memiliki sesi 'mulai' hari ini
        $sesi = Sesi::where('user_id', Auth::user()->id)
            ->where('status', 'mulai')
            ->whereDate('waktu_mulai', now())
            ->first();
        return $sesi;
    }

    public function index()
    {
        // panggil fungsi cekSesi()
        $sesi = $this->cekSesi();
        return view('home.index', compact('sesi'));
    }

    public function bukaKasir()
    {
        return view('home.buka-kasir');
    }

    public function bukaKasirStore(Request $request)
    {
        $request->validate([
            'saldo_awal' => 'required|numeric',
        ]);

        // panggil fungsi cekSesi()
        $sesi = $this->cekSesi();
        if (!$sesi) {
            Sesi::create([
                'user_id' => Auth::user()->id,
                'status' => 'mulai',
                'waktu_mulai' => now(),
                'waktu_selesai' => now()->endOfDay(),
                'saldo_awal' => $request->saldo_awal,
            ]);
        } else {
            return redirect()->route('dashboard')->with('error', 'Anda sudah membuka kasir.');
        }

        return redirect()->route('dashboard')->with('success', 'Berhasil membuka kasir.');
    }

    public function tutupKasir()
    {
        $sesi = $this->cekSesi();
        $waktuMulai = Carbon::parse($sesi->waktu_mulai);
        $waktuSelesai = Carbon::parse($sesi->waktu_selesai);

        $terimaPembayaran = $this->getTerimaPembayaran($waktuMulai, $waktuSelesai);

        $kasKasir = Kas::where('user_id', Auth::user()->id)
            ->whereBetween('created_at', [$waktuMulai, $waktuSelesai])
            ->get();
        $kasMasuk = $kasKasir->where('jenis', 'masuk')->sum('nominal') + $sesi->saldo_awal;
        $kasKeluar = $kasKasir->where('jenis', 'keluar')->sum('nominal');

        $saldoAkhir = $terimaPembayaran + $kasMasuk - $kasKeluar;

        return view('home.tutup-kasir', compact('saldoAkhir'));
    }

    public function tutupKasirStore(Request $request)
    {
        $request->validate([
            'saldo_akhir' => 'required|numeric',
        ]);

        // panggil fungsi cekSesi()
        $sesi = $this->cekSesi();
        if ($sesi) {
            $sesi->update([
                'status' => 'selesai',
                'waktu_selesai' => now(),
                'saldo_akhir' => $request->saldo_akhir,
            ]);

            // PRINT_HERE
            // PrintController::printTutupKasir($sesi->id);
            try {
                PrintController::printTutupKasir($sesi->id);
            } catch (\Throwable $th) {
                //throw $th;
            }

        } else {
            return redirect()->route('dashboard')->with('error', 'Anda belum membuka kasir.');
        }

        return redirect()->route('dashboard')->with('success', 'Berhasil menutup kasir.');
    }

    public function getTerimaPembayaran($waktuMulai, $waktuSelesai)
    {
        $transaksiAll = Transaksi::where('status', 'selesai')
            ->whereBetween('waktu_transaksi', [$waktuMulai, $waktuSelesai])
            ->get();

        // totalPenjualan = sum harga_total from transaksi detail table
        $totalPenjualan = TransaksiDetail::whereIn('transaksi_id', $transaksiAll->where('is_refund', false)->pluck('id'))->sum('harga_total');

        $sumRefund = TransaksiDetail::whereIn('transaksi_id', $transaksiAll->where('is_refund', true)->pluck('id'))->sum('harga_total');
        $sumHutang = Bayar::whereIn('transaksi_id', $transaksiAll->where('is_hutang', true)->pluck('id'))->sum('hutang');
        $terimaPembayaran = $totalPenjualan - $sumRefund - $sumHutang;

        return $terimaPembayaran;
    }
}
