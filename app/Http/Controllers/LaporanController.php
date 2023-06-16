<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\ProdukGrosir;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Kas;
use App\Models\Sesi;

class LaporanController extends Controller
{
    public function ringkasan_penjualan()
    {
        return view('laporan.ringkasan_penjualan');
    }

    public function top_report()
    {
        $data = TransaksiDetail::selectRaw('produk_id, sum(jumlah) as total_qty')
            ->groupBy('produk_id')
            ->orderBy('total_qty', 'desc')
            ->limit(10)
            ->get();

        return view('laporan.top_report', compact('data'));
    }

    public function tutup_kasir()
    {
        return view('laporan.tutup_kasir');
    }

    public function kas_kasir()
    {
        // get sesi today where status = mulai and user_id = auth user
        $sesi = Sesi::where('status', 'mulai')
            ->where('user_id', Auth::user()->id)
            ->whereDate('created_at', date('Y-m-d'))
            ->first();

        // get data kas where date between sesi->created_at and now
        $data = Kas::whereBetween('created_at', [$sesi->created_at, date('Y-m-d H:i:s')])
            ->where('user_id', Auth::user()->id)
            ->get();

        // masuk = sum kas where jenis = masuk + saldo awal in sesi
        $masuk = $data->where('jenis', 'masuk')->sum('nominal') + $sesi->saldo_awal;

        // keluar = sum kas where jenis = keluar
        $keluar = $data->where('jenis', 'keluar')->sum('nominal');

        // TODO: REFUND, TOTAL KAS KASIR, PENJUALAN

        return view('laporan.kas_kasir', compact('data', 'masuk', 'keluar'));
    }

    public function kas_kasir_create()
    {
        return view('laporan.kas_kasir-tambah');
    }

    public function kas_kasir_store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_transaksi' => 'required',
            'nominal' => 'required|numeric',
            'jenis' => 'required',
        ]);

        Kas::create([
            'nama_transaksi' => $request->nama_transaksi,
            'nominal' => $request->nominal,
            'jenis' => $request->jenis,
            'catatan' => $request->catatan,
            'user_id' => Auth::user()->id,
        ]);

        return redirect()->route('laporan.kas_kasir')->with('success', 'Data berhasil disimpan');
    }
}
