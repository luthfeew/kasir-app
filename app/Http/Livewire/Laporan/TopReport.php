<?php

namespace App\Http\Livewire\Laporan;

use Livewire\Component;
use Illuminate\Support\Carbon;
use App\Models\Inventaris;
use App\Models\TransaksiDetail;

class TopReport extends Component
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

    public function render()
    {
        // $inventaris = select all from inventaris where transaksi_id is not null and stok is negative, between tanggalAwal and tanggalAkhir
        $inventaris = Inventaris::whereNotNull('transaksi_id')
            ->where('stok', '<', 0)
            ->whereBetween('created_at', [$this->tanggalAwal . ' 00:00:00', $this->tanggalAkhir . ' 23:59:59'])
            // ->groupBy('produk_id')
            // ->selectRaw('produk_id, sum(stok) as stok')
            ->get();

        // get harga from transaksi_detail where transaksi_id = inventaris->transaksi_id and produk_id = inventaris->produk_id
        foreach ($inventaris as $item) {
            $item->harga_total = TransaksiDetail::where('transaksi_id', $item->transaksi_id)
                ->where('produk_id', $item->produk_id)
                ->first()->harga_total;
        }

        // group by produk_id. sum stok and harga_total
        $inventaris = $inventaris->groupBy('produk_id')->map(function ($item) {
            return [
                'nama_produk' => $item->first()->produk->nama,
                'produk_terjual' => $item->sum('stok'),
                'harga_total' => $item->sum('harga_total'),
            ];
        });

        $totalProdukTerjual = $inventaris->sum('produk_terjual');
        $totalHargaTotal = $inventaris->sum('harga_total');

        return view('livewire.laporan.top-report', [
            'inventaris' => $inventaris,
            'totalProdukTerjual' => $totalProdukTerjual,
            'totalHargaTotal' => $totalHargaTotal,
        ]);
    }
}
