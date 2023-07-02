<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\ProdukGrosir;
use App\Models\Inventaris;

class Pesanan extends Component
{
    public $transaksi_id;
    public $pelanggan_id;
    public $namaPembeli;
    public $jumlah_beli = [];

    public function mount()
    {
        if (!$this->transaksi_id) {
            $this->transaksi_id = $this->cekTransaksi();
        }
        $this->pelanggan_id = Transaksi::find($this->transaksi_id)->pelanggan_id;
        $this->namaPembeli = Transaksi::find($this->transaksi_id)->nama_pembeli;
    }

    protected $listeners = [
        'tambahProduk' => 'tambahProduk',
        'tambahProdukEnter' => 'tambahProdukEnter',
        'updatePelanggan' => 'updatePelanggan',
    ];

    protected $rules = [
        'jumlah_beli.*' => 'required|numeric|min:1',
    ];

    public function render()
    {
        $transaksiDetail = TransaksiDetail::where('transaksi_id', $this->transaksi_id)->get();
        // // tambah field harga di collection transaksi detail dengan fungsi getHarga()
        // $transaksiDetail->map(function ($item, $key) {
        //     $item->harga = self::getHarga($item->produk_id);
        //     return $item;
        // });

        // isi field jumlah_beli
        foreach ($transaksiDetail as $item) {
            $this->jumlah_beli[$item->id] = $item->jumlah_beli;
        }

        return view('livewire.pesanan', [
            'transaksi' => Transaksi::find($this->transaksi_id),
            'transaksiDetail' => $transaksiDetail,
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

    public static function cekProduk($id)
    {
        // cek apakah sudah ada produk di transaksi detail
        // $produk = TransaksiDetail::where('transaksi_id', $this->transaksi_id)->where('produk_id', $id)->first();
        $produk = TransaksiDetail::where('transaksi_id', self::cekTransaksi())->where('produk_id', $id)->first();

        return $produk;
    }

    public function updateQty($id)
    {
        // dd($id);
        
        // get produk_id from transaksi detail using $id
        $produk_id = TransaksiDetail::find($id)->produk_id;

        // cek stok produk di tabel inventaris
        $stok = Inventaris::where('produk_id', $produk_id)->sum('stok');
        // dd($stok);

        // validasi qty tidak boleh lebih dari stok
        $this->validate([
            'jumlah_beli.' . $id => 'required|numeric|min:1|max:' . $stok,
        ]);

        // cek apakah sudah ada produk di transaksi detail
        $produk = self::cekProduk($produk_id);

        // jika sudah ada, maka update jumlah beli
        if ($produk) {
            $produk->jumlah_beli = $this->jumlah_beli[$id];
            $produk->update();
        }

        // call refreshHarga() untuk mengupdate harga satuan dan harga total
        $this->refreshHarga();
    }

    public function tambahProduk($id)
    {
        // cek apakah sudah ada produk di transaksi detail
        $produk = self::cekProduk($id);
        $harga_satuan = self::getHarga($id, 'tambah');

        // jika sudah ada, maka tambahkan jumlahnya
        if ($produk) {
            $produk->jumlah_beli = $produk->jumlah_beli + 1;
            $produk->harga_satuan = $harga_satuan;
            $produk->harga_total = $produk->jumlah_beli * $harga_satuan;
            $produk->update();
        } else {
            // jika belum ada, maka tambahkan produk baru
            TransaksiDetail::create([
                'transaksi_id' => $this->transaksi_id,
                'produk_id' => $id,
                'jumlah_beli' => 1,
                'harga_satuan' => $harga_satuan,
                'harga_total' => $harga_satuan,
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

    public function kurangProduk($id)
    {
        // cek apakah sudah ada produk di transaksi detail
        $produk = self::cekProduk($id);
        $harga_satuan = self::getHarga($id, 'kurang');

        // jika sudah ada, maka kurangi jumlahnya
        if ($produk) {
            $produk->jumlah_beli = $produk->jumlah_beli - 1;
            $produk->harga_satuan = $harga_satuan;
            $produk->harga_total = $produk->jumlah_beli * $harga_satuan;
            $produk->update();

            // jika jumlahnya 0, maka hapus produk
            if ($produk->jumlah_beli == 0) {
                $produk->delete();
            }
        }
    }

    public function hapusProduk($id)
    {
        // cek apakah sudah ada produk di transaksi detail
        $produk = self::cekProduk($id);

        // jika sudah ada, maka hapus produk
        if ($produk) {
            $produk->delete();
        }
    }

    public function updatePelanggan()
    {
        if (!$this->pelanggan_id) {
            $this->pelanggan_id = null;
        }
        Transaksi::where('id', $this->transaksi_id)->update(['pelanggan_id' => $this->pelanggan_id]);

        // call refreshHarga() untuk mengupdate harga satuan dan harga total
        $this->refreshHarga();
    }

    public function updateNamaPembeli()
    {
        Transaksi::where('id', $this->transaksi_id)->update(['nama_pembeli' => $this->namaPembeli]);
    }

    public static function getHarga($id, $action = null)
    {
        // cek apakah pelanggan atau bukan, jika pelanggan gunakan harga pelanggan
        if (Transaksi::find(self::cekTransaksi())->pelanggan_id) {
            if (Produk::find($id)->harga_pelanggan) {
                $harga = Produk::find($id)->harga_pelanggan;
            } else {
                $harga = Produk::find($id)->harga_jual;
            }
        } else {
            $harga = Produk::find($id)->harga_jual;
        }

        // kalkulasi jumlah beli
        if (!self::cekProduk($id)) {
            $jumlah_beli = 0;
        } else {
            $jumlah_beli = self::cekProduk($id)->jumlah_beli;
        }

        if ($action == 'tambah') {
            $jumlah_beli = $jumlah_beli + 1;
        } elseif ($action == 'kurang') {
            $jumlah_beli = $jumlah_beli - 1;
        }

        // cek apakah produk sudah bisa grosir atau belum
        $produkGrosir = ProdukGrosir::where('produk_id', $id)->where('minimal', '<=', $jumlah_beli)->orderBy('minimal', 'desc')->first();

        // jika sudah bisa grosir, maka gunakan harga grosir. apabila harga grosir lebih tinggi dari harga sekarang, maka gunakan harga sekarang
        if ($produkGrosir) {
            // 5900 < 6000
            if ($produkGrosir->harga_grosir < $harga) {
                $harga = $produkGrosir->harga_grosir;
            }
        }

        return $harga;
    }

    public function refreshHarga()
    {
        // ambil semua transaksi detail
        $transaksiDetail = TransaksiDetail::where('transaksi_id', $this->transaksi_id)->get();

        // update harga satuan dan harga total
        foreach ($transaksiDetail as $item) {
            $item->harga_satuan = self::getHarga($item->produk_id);
            $item->harga_total = $item->jumlah_beli * $item->harga_satuan;
            $item->update();
        }
    }
}
