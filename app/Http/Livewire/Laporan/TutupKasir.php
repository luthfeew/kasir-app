<?php

namespace App\Http\Livewire\Laporan;

use Livewire\Component;
use Illuminate\Support\Carbon;
use App\Models\Sesi;
use App\Models\Kas;
use Illuminate\Support\Facades\Auth;

class TutupKasir extends Component
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
        // get all sesi where user_id = auth user and group by date per day
        $data = Sesi::where('user_id', Auth::user()->id)
            ->whereBetween('created_at', [$this->tanggalAwal . ' 00:00:00', $this->tanggalAkhir . ' 23:59:59'])
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            });

        return view('livewire.laporan.tutup-kasir', [
            'data' => $data
        ]);
    }
}
