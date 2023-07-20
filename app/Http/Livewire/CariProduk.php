<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Produk;

class CariProduk extends Component
{
    public $cari = '';

    protected $listeners = ['enter'];

    public function render()
    {
        return view('livewire.cari-produk', [
            'produks' => $this->cari === '' ? [] : Produk::whereRelation('inventaris', 'stok', '>', 0)
                ->where(function ($query) {
                    $query->where('nama', 'like', '%' . $this->cari . '%')
                        ->orWhere('sku', 'like', '%' . $this->cari . '%');
                })
                ->get()
        ]);
    }

    public function tambah($id)
    {
        $this->emit('passTambah', $id);
        $this->cari = '';
    }

    public function enter($nama)
    {
        $this->emit('passEnter', $nama);
        $this->cari = '';
    }
}
