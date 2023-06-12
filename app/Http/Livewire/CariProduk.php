<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Produk;

class CariProduk extends Component
{
    public $cari = '';

    public function render()
    {
        return view('livewire.cari-produk', [
            'produks' => $this->cari === '' ? [] : Produk::where('stok', '>', 0)
                ->where(function ($query) {
                    $query->where('nama', 'like', '%' . $this->cari . '%')
                        ->orWhere('sku', 'like', '%' . $this->cari . '%');
                })
                ->get()
        ]);
    }

    public function tambahProduk($id)
    {
        $this->emit('tambahProduk', $id);
        $this->cari = '';
    }
}
