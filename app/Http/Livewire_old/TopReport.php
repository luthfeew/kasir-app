<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Produk;
use App\Models\ProdukGrosir;

class TopReport extends Component
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
        $data = TransaksiDetail::selectRaw('produk_id, sum(jumlah) as total_qty')
            // where between tanggal_awal 00:00:00 dan tanggal_akhir 23:59:59
            ->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($this->tanggal_awal)), date('Y-m-d 23:59:59', strtotime($this->tanggal_akhir))])
            ->groupBy('produk_id')
            ->orderBy('total_qty', 'desc')
            ->limit(10)
            ->get();

        // cek jika jumlah produk sudah bisa grosir, maka tampilkan harga grosir
        foreach ($data as $item) {
            $produk = Produk::find($item->produk_id);
            $produkGrosir = ProdukGrosir::where('produk_id', $produk->id)->get();
            foreach ($produkGrosir as $grosir) {
                if (abs($item->total_qty) >= $grosir->kelipatan) {
                    $item->produk->harga_jual = $grosir->harga;
                }
            }
        }

        return view('livewire.top-report', [
            'data' => $data,
        ]);
    }
}
