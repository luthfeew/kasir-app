<?php

namespace App\Http\Livewire\Laporan;

use Livewire\Component;
use Illuminate\Support\Carbon;
use App\Models\Inventaris;
use App\Models\TransaksiDetail;
use App\Models\ProdukKategori;

class TopReport extends Component
{
    public $tanggalAwal, $tanggalAkhir, $rentang, $kategori;

    public function mount()
    {
        $this->tanggalAwal = Carbon::now()->format('Y-m-d');
        $this->tanggalAkhir = Carbon::now()->format('Y-m-d');
        $this->rentang = 1;
    }

    protected $listeners = ['setTanggal', 'setKategori'];

    public function setTanggal($tanggalAwal, $tanggalAkhir, $rentang)
    {
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->rentang = $rentang;
    }

    public function setKategori($kategori)
    {
        $this->kategori = $kategori;
    }

    public function render()
    {
        // $inventaris = select all from inventaris where transaksi_id is not null and stok is negative, between tanggalAwal and tanggalAkhir
        $inventaris = Inventaris::whereNotNull('transaksi_id')
            ->where('stok', '<', 0)
            ->whereBetween('created_at', [$this->tanggalAwal . ' 00:00:00', $this->tanggalAkhir . ' 23:59:59'])
            // if kategori is not null, then filter by kategori
            ->when($this->kategori, function ($query, $kategori) {
                return $query->whereHas('produk', function ($query) use ($kategori) {
                    $query->where('produk_kategori_id', $kategori);
                });
            })
            ->get();

        // get harga from transaksi_detail where transaksi_id = inventaris->transaksi_id and produk_id = inventaris->produk_id
        foreach ($inventaris as $item) {
            $item->harga_total = TransaksiDetail::where('transaksi_id', $item->transaksi_id)
                ->where('produk_id', $item->produk_id)
                ->first()->harga_total;
        }

        // group by produk_id. sum stok and harga_total. order by produk_terjual ascending
        $inventaris = $inventaris->groupBy('produk_id')->map(function ($item) {
            return [
                'nama_produk' => $item->first()->produk->nama,
                'produk_terjual' => $item->sum('stok'),
                'harga_total' => $item->sum('harga_total'),
            ];
        })->sortBy('produk_terjual');

        $totalProdukTerjual = $inventaris->sum('produk_terjual');
        $totalHargaTotal = $inventaris->sum('harga_total');

        $kategoriProduk = ProdukKategori::all();

        return view('livewire.laporan.top-report', [
            'inventaris' => $inventaris,
            'totalProdukTerjual' => $totalProdukTerjual,
            'totalHargaTotal' => $totalHargaTotal,
            'kategoriProduk' => $kategoriProduk,
        ]);
    }
}
