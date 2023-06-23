<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Produk;
use App\Models\User;
use App\Models\Inventaris;

class CariProduk extends Component
{
    public $cari = '';

    protected $listeners = ['tambah'];

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
        $this->emit('tambahProduk', $id);
        $this->cari = '';
    }
}
