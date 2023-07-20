<?php

namespace App\Http\Livewire\Laporan;

use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Sesi;
use App\Models\Kas;

class KasKasir extends Component
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
        // get sesi today where status = mulai, and user_id = auth user id
        $sesi = Sesi::where('status', 'mulai')
            ->where('user_id', Auth::user()->id)
            ->whereDate('created_at', Carbon::now()->format('Y-m-d'))
            ->first();

        // get data kas where date between sesi->created_at and now
        $data = Kas::whereBetween('created_at', [$sesi->created_at, Carbon::now()->format('Y-m-d H:i:s')])
            ->where('user_id', Auth::user()->id)
            ->get();

        // masuk = sum kas where jenis = masuk + saldo awal in sesi
        $masuk = $data->where('jenis', 'masuk')->sum('nominal') + $sesi->saldo_awal;
        $keluar = $data->where('jenis', 'keluar')->sum('nominal');

        return view('livewire.laporan.kas-kasir', [
            'sesi' => $sesi,
            'data' => $data,
            'masuk' => $masuk,
            'keluar' => $keluar,
        ]);
    }
}
