<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Pelanggan;


class Pesanan extends Component
{
    public $transaksi_id;
    // public $namaPembeli;

    public function mount()
    {
        if (!$this->transaksi_id) {
            $this->transaksi_id = $this->cekTransaksi();
        }
        // $this->namaPembeli = Transaksi::find($this->transaksi_id)->nama_pembeli;
    }

    protected $listeners = ['tambahProduk'];

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

    public function tambahProduk($id)
    {
        dd('HOLY HELL' . $id);
    }
}
