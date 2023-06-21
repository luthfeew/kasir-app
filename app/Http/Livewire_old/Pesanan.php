<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use App\Models\ProdukGrosir;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;

class Pesanan extends Component
{
    public $transaksi_id;
    public $namaPelanggan;
    public $qty = [];

    public function mount()
    {
        if (!$this->transaksi_id) {
            $this->cekTransaksi();
        }
        
        $this->namaPelanggan = Transaksi::find($this->transaksi_id)->nama_pelanggan;
    }

    protected $rules = [
        'qty.*' => 'required|numeric|min:1',
    ];

    protected $listeners = [
        'tambahProduk' => 'tambahQty',
    ];

    public function render()
    {
        // cek apakah transaksi_id kosong, jika kosong maka jalankan fungsi cekTransaksi()
        // if (!$this->transaksi_id) {
        //     $this->cekTransaksi();
        // }

        $transaksiDetail = TransaksiDetail::where('transaksi_id', $this->transaksi_id)->get();
        foreach ($transaksiDetail as $item) {
            $this->qty[$item->id] = $item->jumlah;
        }

        // cek jika jumlah produk sudah bisa grosir, maka tampilkan harga grosir
        foreach ($transaksiDetail as $item) {
            $produk = Produk::find($item->produk_id);
            $produkGrosir = ProdukGrosir::where('produk_id', $produk->id)->get();
            foreach ($produkGrosir as $grosir) {
                if ($item->jumlah >= $grosir->kelipatan) {
                    $item->produk->harga_jual = $grosir->harga;
                }
            }
        }

        return view('livewire.pesanan', [
            'transaksiDetail' => $transaksiDetail,
            'kasir' => Auth::user()->name,
            'hargaTotal' => $this->hargaTotal(),
        ]);
    }

    function cekTransaksi()
    {
        // cek apakah user memiliki transaksi yang statusnya proses
        $transaksi = Transaksi::where('user_id', Auth::user()->id)
            ->where('status', 'proses')
            ->first();

        // jika transaksi ada, maka set transaksi_id dengan id transaksi. Jika tidak ada, maka buat transaksi baru
        if ($transaksi) {
            $this->transaksi_id = $transaksi->id;
        } else {
            $transaksi = Transaksi::create([
                'user_id' => Auth::user()->id,
                'status' => 'proses',
            ]);

            $this->transaksi_id = $transaksi->id;
        }
    }

    function hargaTotal()
    {
        $hargaTotal = 0;
        foreach (TransaksiDetail::where('transaksi_id', $this->transaksi_id)->get() as $item) {
            $produk = Produk::find($item->produk_id);
            $produkGrosir = ProdukGrosir::where('produk_id', $produk->id)->get();
            foreach ($produkGrosir as $grosir) {
                if ($item->jumlah >= $grosir->kelipatan) {
                    $item->produk->harga_jual = $grosir->harga;
                }
            }
            $hargaTotal += $item->produk->harga_jual * $item->jumlah;
        }
        return $hargaTotal;
    }

    public function tambahQty($id)
    {
        // cek apakah produk sudah ada di transaksi
        $transaksiDetail = TransaksiDetail::where('transaksi_id', $this->transaksi_id)
            ->where('produk_id', $id)
            ->first();

        // jika produk sudah ada di transaksi, maka update jumlahnya
        if ($transaksiDetail) {

            // cek apakah jumlah produk sudah melebihi stok
            if ($transaksiDetail->jumlah >= $transaksiDetail->produk->stok) {
                // $this->dispatchBrowserEvent('jumlahMelebihiStok');
                return;
            }

            $transaksiDetail->update([
                'jumlah' => $transaksiDetail->jumlah + 1,
            ]);
        } else {
            // jika produk belum ada di transaksi, maka tambahkan produk ke transaksi
            TransaksiDetail::create([
                'transaksi_id' => $this->transaksi_id,
                'produk_id' => $id,
                'jumlah' => 1,
            ]);
        }

        // $this->dispatchBrowserEvent('table-updated');
    }

    public function kurangQty($produk_id)
    {
        $transaksiDetail = TransaksiDetail::where('transaksi_id', $this->transaksi_id)
            ->where('produk_id', $produk_id)
            ->first();

        if ($transaksiDetail->jumlah == 1) {
            $transaksiDetail->delete();
        } else {
            $transaksiDetail->update([
                'jumlah' => $transaksiDetail->jumlah - 1,
            ]);
        }
    }

    public function updateQty($id)
    {
        // dd($this->qty[$id], $id);

        // get stok produk using produk_id in transaksi_detail
        $stok = TransaksiDetail::where('id', $id)->first()->produk->stok;

        // validate qty and show error message
        $this->validate(
            [
                'qty.' . $id => 'required|numeric|min:1|max:' . $stok,
            ],
            [
                'qty.' . $id . '.required' => 'Jumlah tidak boleh kosong',
                'qty.' . $id . '.numeric' => 'Jumlah harus berupa angka',
                'qty.' . $id . '.min' => 'Jumlah minimal 1',
                'qty.' . $id . '.max' => 'Jumlah melebihi stok',
            ]
        );

        // update jumlah produk
        $transaksiDetail = TransaksiDetail::where('id', $id)->first();
        $transaksiDetail->update([
            'jumlah' => $this->qty[$id],
        ]);
    }

    public function hapusProduk($id)
    {
        // hapus transaksi detail where produk_id = $id
        TransaksiDetail::where('produk_id', $id)->delete();
    }

    public function updateNamaPelanggan()
    {
        // update nama pelanggan
        Transaksi::where('id', $this->transaksi_id)->update([
            'nama_pelanggan' => $this->namaPelanggan,
        ]);
    }
}
