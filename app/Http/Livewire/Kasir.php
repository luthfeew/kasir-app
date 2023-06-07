<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;

class Kasir extends Component
{
    protected $listeners = [
        'added' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.kasir', [
            'transaksi' => Transaksi::where('status', 'proses')->first(),
        ]);
    }
}
