<?php

namespace App\Http\Livewire\Laporan;

use Livewire\Component;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Bayar;
use App\Models\Inventaris;
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

    public function render()
    {
        return view('livewire.laporan.ringkasan-penjualan');
    }

    public function setTanggal($tanggalAwal, $tanggalAkhir, $rentang)
    {
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->rentang = $rentang;
    }
}
