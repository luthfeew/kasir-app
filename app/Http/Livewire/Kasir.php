<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use Illuminate\Database\Eloquent\Collection;

class Kasir extends Component
{
    public $produk_id = [];
    public $qty = [];
    public $stok = [];

    public function mount()
    {
        $transaksi = Transaksi::where('user_id', Auth::user()->id)->where('status', 'proses')->first();
        if ($transaksi) {
            foreach ($transaksi->transaksiDetail as $item) {
                $this->produk_id[$item->id] = $item->produk_id;
                $this->qty[$item->id] = $item->jumlah;
                $this->stok[$item->id] = $item->produk->stok;
            }
        }
    }

    protected $rules = [
        'qty.*' => 'required|numeric|min:1',
    ];

    // protected $rules = [
    //     'qtys.*.qty' => 'required|numeric',
    // ];

    // protected $messages = [
    //     'qtys.*.qty.required' => 'The Quantity field is required',
    //     'qtys.*.qty.numeric' => 'The Quantity field must be a number'
    // ];

    protected $listeners = [
        'added' => '$refresh',
        'refreshQty' => 'refreshQty',
    ];

    public function refreshQty()
    {
        // update qty in array $qty
        $transaksi = Transaksi::where('user_id', Auth::user()->id)->where('status', 'proses')->first();
        if ($transaksi) {
            foreach ($transaksi->transaksiDetail as $item) {
                $this->qty[$item->id] = $item->jumlah;
            }
        }
    }

    public function render()
    {
        $transaksi = Transaksi::where('user_id', Auth::user()->id)->where('status', 'proses')->first();
        // if ($transaksi) {
        //     foreach ($transaksi->transaksiDetail as $item) {
        //         $this->qty[$item->id] = $item->jumlah;
        //     }
        // }

        return view('livewire.kasir', [
            'transaksi' => $transaksi,
            'user_id' => Auth::user()->id,
            // 'hargaTotal' => 0,
        ]);
    }

    public function delete($id)
    {
        $transaksiDetail = TransaksiDetail::find($id);
        $transaksiDetail->delete();
        // $this->emit('added');
    }

    public function save()
    {
        dd($this->qty);
    }

    public function update($id)
    {
        // $this->validate([
        //     'qty.' . $id => 'required|numeric|min:1|max:' . $this->stok[$id],
        // ]);

        // validate qty and show error message
        $this->validate([
            'qty.' . $id => 'required|numeric|min:1|max:' . $this->stok[$id],
        ], [
            'qty.' . $id . '.required' => 'The Quantity field is required',
            'qty.' . $id . '.numeric' => 'The Quantity field must be a number',
            'qty.' . $id . '.min' => 'The Quantity field must be at least 1',
            'qty.' . $id . '.max' => 'QTY maksimal ' . $this->stok[$id],
        ]);

        // dd($this->qty);
        $transaksiDetail = TransaksiDetail::find($id);
        $transaksiDetail->jumlah = $this->qty[$id];
        $transaksiDetail->save();
        $this->emit('refreshQty');
    }

}
