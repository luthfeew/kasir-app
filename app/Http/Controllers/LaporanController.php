<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\ProdukGrosir;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;

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
        return view('laporan.kas_kasir');
    }
}
