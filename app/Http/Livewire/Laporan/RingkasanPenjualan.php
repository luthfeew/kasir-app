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
                return $query->whereBetween('waktu_transaksi', [$customTanggalAwal . ' 00:00:00', $customTanggalAkhir . ' 23:59:59']);
            }, function ($query) {
                return $query->whereBetween('waktu_transaksi', [$this->tanggalAwal . ' 00:00:00', $this->tanggalAkhir . ' 23:59:59']);
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

        if ($this->rentang == 3) {
            // buat list tanggal 7 hari terakhir dari tanggalAwal dengan format Y-m-d
            $listTanggal = [];
            for ($i = 0; $i < 7; $i++) {
                array_push($listTanggal, Carbon::parse($this->tanggalAwal)->addDays($i)->format('Y-m-d'));
            }
            // get data from getData() method
            $listData = [];
            foreach ($listTanggal as $tanggal) {
                $x = $this->getData($tanggal, $tanggal);
                array_push($listData, [
                    'tanggal' => Carbon::parse($tanggal)->format('d-m-Y'),
                    'totalPenjualan' => $x['totalPenjualan'],
                    'labaKotor' => $x['labaKotor'],
                    'terimaPembayaran' => $x['terimaPembayaran'],
                    'sumRefund' => $x['sumRefund'],
                    'sumHutang' => $x['sumHutang'],
                    'rataTransaksi' => $x['rataTransaksi'],
                    'totalTransaksi' => $x['totalTransaksi'],
                    'totalProdukTerjual' => $x['totalProdukTerjual'],
                ]);
            }
        } elseif ($this->rentang == 4 || $this->rentang == 5 || $this->rentang == 6) {
            // buat list tanggalAwal dan tanggalAkhir perminggu dari tanggalAwal dengan format Y-m-d
            $listTanggal = [];
            for ($i = 0; $i < 5; $i++) {
                array_push($listTanggal, [
                    'tanggalAwal' => Carbon::parse($this->tanggalAwal)->addWeeks($i)->format('Y-m-d'),
                    'tanggalAkhir' => Carbon::parse($this->tanggalAwal)->addWeeks($i)->addDays(6)->format('Y-m-d')
                ]);
            }

            // get data from getData() method
            $listData = [];
            foreach ($listTanggal as $tanggal) {
                $x = $this->getData($tanggal['tanggalAwal'], $tanggal['tanggalAkhir']);
                array_push($listData, [
                    'tanggal' => Carbon::parse($tanggal['tanggalAwal'])->format('d-m-Y') . ' - ' . Carbon::parse($tanggal['tanggalAkhir'])->format('d-m-Y'),
                    'totalPenjualan' => $x['totalPenjualan'],
                    'labaKotor' => $x['labaKotor'],
                    'terimaPembayaran' => $x['terimaPembayaran'],
                    'sumRefund' => $x['sumRefund'],
                    'sumHutang' => $x['sumHutang'],
                    'rataTransaksi' => $x['rataTransaksi'],
                    'totalTransaksi' => $x['totalTransaksi'],
                    'totalProdukTerjual' => $x['totalProdukTerjual'],
                ]);
            }
        } elseif ($this->rentang == 7) {
            // buat list tanggalAwal dan tanggalAkhir perbulan dari tanggalAwal dengan format Y-m-d
            $listTanggal = [];
            for ($i = 0; $i < 12; $i++) {
                array_push($listTanggal, [
                    'tanggalAwal' => Carbon::parse($this->tanggalAwal)->addMonths($i)->format('Y-m-d'),
                    'tanggalAkhir' => Carbon::parse($this->tanggalAwal)->addMonths($i)->endOfMonth()->format('Y-m-d')
                ]);
            }

            // get data from getData() method
            $listData = [];
            foreach ($listTanggal as $tanggal) {
                $x = $this->getData($tanggal['tanggalAwal'], $tanggal['tanggalAkhir']);
                array_push($listData, [
                    'tanggal' => Carbon::parse($tanggal['tanggalAwal'])->format('F Y'),
                    'totalPenjualan' => $x['totalPenjualan'],
                    'labaKotor' => $x['labaKotor'],
                    'terimaPembayaran' => $x['terimaPembayaran'],
                    'sumRefund' => $x['sumRefund'],
                    'sumHutang' => $x['sumHutang'],
                    'rataTransaksi' => $x['rataTransaksi'],
                    'totalTransaksi' => $x['totalTransaksi'],
                    'totalProdukTerjual' => $x['totalProdukTerjual'],
                ]);
            }
        } elseif ($this->rentang == 0) {
            // cek selisih tanggalAwal dan tanggalAkhir
            $selisih = Carbon::parse($this->tanggalAwal)->diffInDays(Carbon::parse($this->tanggalAkhir));

            // jika selisih kurang dari sama dengan 15 hari tampilkan harian. jika antara 15 dan 60 tampilkan mingguan. jika lebih dari 60 tampilkan bulanan
            if ($selisih <= 15) {
                // buat list tanggal dari tanggalAwal sampai tanggalAkhir dengan format Y-m-d
                $listTanggal = [];
                for ($i = 0; $i <= $selisih; $i++) {
                    array_push($listTanggal, Carbon::parse($this->tanggalAwal)->addDays($i)->format('Y-m-d'));
                }
            } elseif ($selisih > 15 && $selisih <= 60) {
                // buat list tanggalAwal dan tanggalAkhir perminggu dari tanggalAwal dengan format Y-m-d
                $listTanggal = [];
                $minggu = Carbon::parse($this->tanggalAwal)->diffInWeeks(Carbon::parse($this->tanggalAkhir));
                for ($i = 0; $i <= $minggu; $i++) {
                    array_push($listTanggal, [
                        'tanggalAwal' => Carbon::parse($this->tanggalAwal)->addWeeks($i)->format('Y-m-d'),
                        'tanggalAkhir' => Carbon::parse($this->tanggalAwal)->addWeeks($i)->addDays(6)->format('Y-m-d')
                    ]);
                }
            } elseif ($selisih > 60) {
                // buat list tanggalAwal dan tanggalAkhir perbulan dari tanggalAwal dengan format Y-m-d
                $listTanggal = [];
                $bulan = Carbon::parse($this->tanggalAwal)->diffInMonths(Carbon::parse($this->tanggalAkhir));
                for ($i = 0; $i <= $bulan; $i++) {
                    array_push($listTanggal, [
                        'tanggalAwal' => Carbon::parse($this->tanggalAwal)->addMonths($i)->format('Y-m-d'),
                        'tanggalAkhir' => Carbon::parse($this->tanggalAwal)->addMonths($i)->endOfMonth()->format('Y-m-d')
                    ]);
                }
            }

            // get data from getData() method
            $listData = [];
            foreach ($listTanggal as $tanggal) {
                if (is_array($tanggal)) {
                    $x = $this->getData($tanggal['tanggalAwal'], $tanggal['tanggalAkhir']);
                } else {
                    $x = $this->getData($tanggal, $tanggal);
                }
                array_push($listData, [
                    // use selisih to determine format date
                    'tanggal' => $selisih <= 15 ? Carbon::parse($tanggal)->format('d-m-Y') : (is_array($tanggal) ? Carbon::parse($tanggal['tanggalAwal'])->format('d-m-Y') . ' - ' . Carbon::parse($tanggal['tanggalAkhir'])->format('d-m-Y') : Carbon::parse($tanggal)->format('F Y')),
                    'totalPenjualan' => $x['totalPenjualan'],
                    'labaKotor' => $x['labaKotor'],
                    'terimaPembayaran' => $x['terimaPembayaran'],
                    'sumRefund' => $x['sumRefund'],
                    'sumHutang' => $x['sumHutang'],
                    'rataTransaksi' => $x['rataTransaksi'],
                    'totalTransaksi' => $x['totalTransaksi'],
                    'totalProdukTerjual' => $x['totalProdukTerjual'],
                ]);
            }
        } else {
            $listData = [];
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
            'listData' => $listData ?? []
        ]);
    }
}
