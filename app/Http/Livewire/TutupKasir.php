<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Sesi;
use App\Models\Kas;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TutupKasir extends Component
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
        // get all sesi where user_id = auth user and group by date per day
        $data = Sesi::where('user_id', Auth::user()->id)
            // ->whereDate('created_at', '>=', Carbon::now()->subDays(7))
            ->whereBetween('created_at', [date('Y-m-d 00:00:00', strtotime($this->tanggal_awal)), date('Y-m-d 23:59:59', strtotime($this->tanggal_akhir))])
            ->get()
            ->groupBy(function ($val) {
                return Carbon::parse($val->created_at)->format('Y-m-d');
            });

        return view('livewire.tutup-kasir', [
            'data' => $data
        ]);
    }
}
