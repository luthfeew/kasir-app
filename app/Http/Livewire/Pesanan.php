<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Pelanggan;
use App\Models\Produk;

class Pesanan extends Component
{
    public $transaksi_id;
    public $pelanggan_id;
    // public $namaPembeli;

    public function mount()
    {
        if (!$this->transaksi_id) {
            $this->transaksi_id = $this->cekTransaksi();
        }
        $this->pelanggan_id = Transaksi::find($this->transaksi_id)->pelanggan_id;
        // $this->namaPembeli = Transaksi::find($this->transaksi_id)->nama_pembeli;
    }

    protected $listeners = [
        'tambahProduk' => 'tambahProduk',
        'tambahProdukEnter' => 'tambahProdukEnter',
        'updatePelanggan' => 'updatePelanggan',
    ];

    public function render()
    {
        return view('livewire.pesanan', [
            'transaksi' => Transaksi::find($this->transaksi_id),
            'daftarPelanggan' => Pelanggan::all()->pluck('nama', 'id'),
        ]);
    }

    public static function cekTransaksi()
    {
        // cek apakah user ada transaksi yang statusnya proses
        $transaksi = Transaksi::where('user_id', Auth::user()->id)->where('status', 'proses')->first();

        // jika tidak ada maka buat transaksi baru
        if (!$transaksi) {
            $transaksi = Transaksi::create([
                'user_id' => Auth::user()->id,
                // buat kode transaksi dengan format: tahun-bulan-tanggal-jumlah transaksi hari ini
                'kode' => 'TRX' . date('ymd') . sprintf("%04s", Transaksi::whereDate('created_at', date('Y-m-d'))->count() + 1),
                'status' => 'proses',
            ]);
        }

        return $transaksi->id;
    }

    public function cekProduk($id)
    {
        // cek apakah sudah ada produk di transaksi detail
        $produk = TransaksiDetail::where('transaksi_id', $this->transaksi_id)->where('produk_id', $id)->first();

        return $produk;
    }

    public function tambahProduk($id)
    {
        // cek apakah sudah ada produk di transaksi detail
        $produk = self::cekProduk($id);

        // jika sudah ada, maka tambahkan jumlahnya
        if ($produk) {
            $produk->jumlah_beli = $produk->jumlah_beli + 1;
            $produk->update();
        } else {
            // jika belum ada, maka tambahkan produk baru
            TransaksiDetail::create([
                'transaksi_id' => $this->transaksi_id,
                'produk_id' => $id,
                'jumlah_beli' => 1,
            ]);
        }
    }

    public function tambahProdukEnter($cari)
    {
        // cari produk berdasarkan nama atau sku
        $produk = Produk::where('nama', 'like', '%' . $cari . '%')
            ->orWhere('sku', 'like', '%' . $cari . '%')
            ->first();

        // jika produk ada, maka tambahkan produk
        if ($produk) {
            $this->tambahProduk($produk->id);
        }
    }

    public function updatePelanggan()
    {
        if (!$this->pelanggan_id) {
            $this->pelanggan_id = null;
        }
        Transaksi::where('id', $this->transaksi_id)->update(['pelanggan_id' => $this->pelanggan_id]);
    }
}
