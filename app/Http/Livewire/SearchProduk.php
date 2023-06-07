<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;

class SearchProduk extends Component
{
    public $search = '';

    public function tambahProduk($sku)
    {
        $produk = Produk::where('sku', $sku)->first();

        $transaksi = Transaksi::where('status', 'proses')->first();

        if (!$transaksi) {
            $transaksi = Transaksi::create([
                'user_id' => Auth::user()->id,
                'status' => 'proses',
            ]);
        }

        $transaksiDetail = TransaksiDetail::where('transaksi_id', $transaksi->id)
            ->where('produk_id', $produk->id)
            ->first();

        if ($transaksiDetail) {
            $transaksiDetail->update([
                'jumlah' => $transaksiDetail->jumlah + 1,
            ]);
        } else {
            TransaksiDetail::create([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $produk->id,
                'jumlah' => 1,
            ]);
        }

        // $this->search = '';
        // $this->emit('focusInput');
        $this->emit('added');
    }

    public function render()
    {
        return view('livewire.search-produk', [
            'produks' => $this->search === '' ? [] : Produk::where('nama', 'like', '%' . $this->search . '%')
                ->orWhere('sku', 'like', '%' . $this->search . '%')
                ->get()
        ]);
    }
}
