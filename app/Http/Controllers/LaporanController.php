<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Rawilk\Printing\Facades\Printing;
use Rawilk\Printing\Receipts\ReceiptPrinter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Kas;
use App\Models\Sesi;
use Illuminate\Support\Carbon;

class LaporanController extends Controller
{
    public function ringkasanPenjualan()
    {
        return view('laporan.ringkasan_penjualan');
    }

    public function topReport()
    {
        return view('laporan.top_report');
    }

    public function tutupKasir()
    {
        return view('laporan.tutup_kasir');
    }

    public function kasKasir()
    {
        // get data sesi where date is today
        $sesi = Sesi::whereDate('created_at', Carbon::today())->get();

        // if data sesi is empty, return dashboard
        if ($sesi->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'Tidak ada transaksi sesi kasir hari ini');
        }

        return view('laporan.kas_kasir');
    }

    public function kasKasirCreate()
    {
        return view('laporan.kas_kasir-tambah');
    }

    public function kasKasirStore(Request $request)
    {
        $request->validate([
            'nama_transaksi' => 'required',
            'nominal' => 'required|numeric',
            'jenis' => 'required',
        ]);

        Kas::create([
            'nama_transaksi' => $request->nama_transaksi,
            'catatan' => $request->catatan,
            'nominal' => $request->nominal,
            'jenis' => $request->jenis,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('laporan.kas_kasir')->with('success', 'Berhasil menambahkan transaksi kas kasir');
    }
}
