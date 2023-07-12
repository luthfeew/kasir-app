<?php

namespace App\Http\Livewire\Laporan;

use Livewire\Component;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Bayar;
use App\Models\Inventaris;
use App\Models\Produk;
use Illuminate\Support\Carbon;

class RingkasanPenjualan extends Component
{
    public $tanggalAwal, $tanggalAkhir, $rentang;

    public function mount()
    {
        $this->tanggalAwal = Carbon::now()->format('Y-m-d');
        $this->tanggalAkhir = Carbon::now()->format('Y-m-d');
        $this->rentang = 1;
    }

    protected $listeners = ['setTanggal'];

    public function setTanggal($tanggalAwal, $tanggalAkhir, $rentang)
    {
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->rentang = $rentang;
    }

    public function getData($customTanggalAwal = null, $customTanggalAkhir = null)
    {
        $transaksiAll = Transaksi::where('status', 'selesai')
            ->when($customTanggalAwal && $customTanggalAkhir, function ($query) use ($customTanggalAwal, $customTanggalAkhir) {
                return $query->whereBetween('created_at', [$customTanggalAwal . ' 00:00:00', $customTanggalAkhir . ' 23:59:59']);
            }, function ($query) {
                return $query->whereBetween('created_at', [$this->tanggalAwal . ' 00:00:00', $this->tanggalAkhir . ' 23:59:59']);
            })
            ->get();

        // totalPenjualan = sum harga_total from transaksi detail table
        $totalPenjualan = TransaksiDetail::whereIn('transaksi_id', $transaksiAll->where('is_refund', false)->pluck('id'))->sum('harga_total');

        // labaKotor = totalPenjualan - hpp - sumHutang - sumRefund
        $totalHargaBeli = 0;
        foreach ($transaksiAll->where('is_refund', false) as $transaksi) {
            foreach ($transaksi->transaksi_detail as $detail) {
                $totalHargaBeli += $detail->produk->harga_beli * $detail->jumlah_beli;
            }
        }
        $totalHargaBeliRefund = 0;
        foreach ($transaksiAll->where('is_refund', true) as $transaksi) {
            foreach ($transaksi->transaksi_detail as $detail) {
                $totalHargaBeliRefund += $detail->produk->harga_beli * $detail->jumlah_beli;
            }
        }
        $sumRefund = TransaksiDetail::whereIn('transaksi_id', $transaksiAll->where('is_refund', true)->pluck('id'))->sum('harga_total');
        $sumHutang = Bayar::whereIn('transaksi_id', $transaksiAll->where('is_hutang', true)->pluck('id'))->sum('hutang');
        $labaKotor = $totalPenjualan - ($totalHargaBeli - $totalHargaBeliRefund) - $sumRefund - $sumHutang;

        // terimaPembayaran
        $terimaPembayaran = $totalPenjualan - $sumRefund - $sumHutang;

        // rata-rata transaksi = totalPenjualan / totalTransaksi
        $totalTransaksi = $transaksiAll->where('is_refund', false)->count();
        $rataTransaksi = $totalTransaksi == 0 ? 0 : $totalPenjualan / $totalTransaksi;

        // totalProduk terjual
        $totalProdukTerjual = 0;
        foreach ($transaksiAll->where('is_refund', false) as $transaksi) {
            foreach ($transaksi->transaksi_detail as $detail) {
                $totalProdukTerjual += $detail->jumlah_beli;
            }
        }

        return [
            'totalPenjualan' => $totalPenjualan,
            'labaKotor' => $labaKotor,
            'terimaPembayaran' => $terimaPembayaran,
            'sumRefund' => $sumRefund,
            'sumHutang' => $sumHutang,
            'rataTransaksi' => $rataTransaksi,
            'totalTransaksi' => $totalTransaksi,
            'totalProdukTerjual' => $totalProdukTerjual
        ];
    }

    public function render()
    {
        // get data from getData() method
        $data = $this->getData();

        // if rentang = 3, group transaksi by day
        if ($this->rentang == 3) {
            // get created_at column only from transaksi table, group by created_at column.
            $abc = Transaksi::where('status', 'selesai')
                ->whereBetween('created_at', [$this->tanggalAwal . ' 00:00:00', $this->tanggalAkhir . ' 23:59:59'])
                ->get(['created_at']);

            // get data from getData() method with custom tanggalAwal and tanggalAkhir from $abc
            $ayyy = [];
            foreach ($abc as $key => $value) {
                $ayyy[$key] = $this->getData($value->created_at->format('Y-m-d'), $value->created_at->format('Y-m-d'));
            }
        }

        return view('livewire.laporan.ringkasan-penjualan', [
            'totalPenjualan' => $data['totalPenjualan'],
            'labaKotor' => $data['labaKotor'],
            'terimaPembayaran' => $data['terimaPembayaran'],
            'sumRefund' => $data['sumRefund'],
            'sumHutang' => $data['sumHutang'],
            'rataTransaksi' => $data['rataTransaksi'],
            'totalTransaksi' => $data['totalTransaksi'],
            'totalProdukTerjual' => $data['totalProdukTerjual'],
            'ayyy' => $ayyy ?? null
        ]);
    }
}
