<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Produk;
use App\Models\ProdukGrosir;

class RingkasanPenjualan extends Component
{
    public $tanggal, $tanggal_awal, $tanggal_akhir;

    public function mount()
    {
        $this->tanggal = date('d/m/Y') . ' ~ ' . date('d/m/Y');
        $this->tanggal_awal = date('Y-m-d');
        $this->tanggal_akhir = date('Y-m-d');
    }

    protected $listeners = [
        'getTanggal' => 'setTanggal'
    ];

    public function setTanggal($tanggal)
    {
        $this->tanggal = $tanggal;
        $pecah = explode('~', $tanggal);
        $this->tanggal_awal = $pecah[0];
        $this->tanggal_akhir = $pecah[1];
    }

    public function render()
    {
        // TOTAL PENJUALAN
        $total_penjualan = Transaksi::where('status', 'selesai')
            ->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($this->tanggal_awal)), date('Y-m-d 23:59:59', strtotime($this->tanggal_akhir))])
            ->sum('harga_total');

        // LABA KOTOR
        $laba_kotor = 0;
        $transaksi = Transaksi::where('status', 'selesai')
            ->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($this->tanggal_awal)), date('Y-m-d 23:59:59', strtotime($this->tanggal_akhir))])
            ->get();
        foreach ($transaksi as $item) {
            $detail = TransaksiDetail::where('transaksi_id', $item->id)->get();
            foreach ($detail as $d) {
                $produk = Produk::find($d->produk_id);
                if ($produk->jenis == 'grosir') {
                    $produk_grosir = ProdukGrosir::where('produk_id', $produk->id)->where('jumlah_minimal', '<=', $d->jumlah)->orderBy('jumlah_minimal', 'desc')->first();
                    $laba_kotor += ($produk_grosir->harga_jual - $produk->harga_beli) * $d->jumlah;
                } else {
                    $laba_kotor += ($produk->harga_jual - $produk->harga_beli) * $d->jumlah;
                }
            }
        }

        // REFUND
        $refund = Transaksi::where('status', 'refund')
            ->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($this->tanggal_awal)), date('Y-m-d 23:59:59', strtotime($this->tanggal_akhir))])
            ->sum('harga_total');

        // RATA-RATA PENJUALAN PER HARI sum harga_total / count transaksi
        $rata_penjualan = Transaksi::where('status', 'selesai')
            ->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($this->tanggal_awal)), date('Y-m-d 23:59:59', strtotime($this->tanggal_akhir))])
            ->sum('harga_total') / Transaksi::where('status', 'selesai')->count();

        // TOTAL TRANSAKSI
        $total_transaksi = Transaksi::where('status', 'selesai')
            ->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($this->tanggal_awal)), date('Y-m-d 23:59:59', strtotime($this->tanggal_akhir))])
            ->count();

        // TOTAL PRODUK TERJUAL
        $transaksi = Transaksi::where('status', 'selesai')
            ->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($this->tanggal_awal)), date('Y-m-d 23:59:59', strtotime($this->tanggal_akhir))])
            ->get();
        $total_produk = 0;
        foreach ($transaksi as $item) {
            $detail = TransaksiDetail::where('transaksi_id', $item->id)->get();
            foreach ($detail as $d) {
                $total_produk += $d->jumlah;
            }
        }

        return view('livewire.ringkasan-penjualan', [
            'total_penjualan' => $total_penjualan,
            'laba_kotor' => $laba_kotor,
            'refund' => $refund,
            'rata_penjualan' => $rata_penjualan,
            'total_transaksi' => $total_transaksi,
            'total_produk' => $total_produk
        ]);
    }
}
