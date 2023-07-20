<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use App\Models\TransaksiDetail;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\ProdukGrosir;
use App\Models\Inventaris;

class Pesanan extends Component
{
    public $transaksiId;
    public $pelangganId;
    public $namaPembeli;
    public $jumlahBeli = [];

    public function mount()
    {
        $this->transaksiId = $this->transaksiId ?? self::cekSesi()->id;
        $this->pelangganId = $this->pelangganId ?? self::cekSesi()->pelanggan_id;
        $this->namaPembeli = $this->namaPembeli ?? self::cekSesi()->nama_pembeli;
    }

    protected $listeners = ['passTambah', 'passEnter'];

    protected $rules = ['jumlahBeli.*' => 'required|numeric|min:1'];

    public function passTambah($produkId)
    {
        self::produk($produkId, 'tambah');
    }

    public function passEnter($cari)
    {
        // cari produk berdasarkan nama atau sku
        $produk = Produk::whereRelation('inventaris', 'stok', '>', 0)
            ->where(function ($query) use ($cari) {
                $query->where('nama', 'like', '%' . $cari . '%')
                    ->orWhere('sku', 'like', '%' . $cari . '%');
            })
            ->first();

        // jika produk ada maka tambahkan
        if ($produk) {
            self::produk($produk->id, 'tambah');
        }
    }

    public function tambahQty($produkId)
    {
        self::produk($produkId, 'tambah');
    }

    public function kurangQty($produkId)
    {
        self::produk($produkId, 'kurang');
    }

    public function updateQty($id)
    {
        // get produk_id using id
        $produkId = TransaksiDetail::find($id)->produk_id;

        // cek stok
        $stok = Inventaris::where('produk_id', $produkId)->sum('stok');
        $this->validate([
            'jumlahBeli.' . $id => 'required|numeric|min:1|max:' . $stok
        ]);

        // cek apakah produk sudah ada di transaksi detail atau belum
        $cek = self::cekProduk($produkId);

        // jika produk sudah ada maka update jumlahnya
        if ($cek) {
            $cek->update([
                'jumlah_beli' => $this->jumlahBeli[$id],
            ]);
        }

        // refresh harga
        self::refreshHarga($produkId);
    }

    public function hapusProduk($produkId)
    {
        TransaksiDetail::where('transaksi_id', $this->transaksiId)
            ->where('produk_id', $produkId)
            ->delete();
    }

    public function produk($produkId, $aksi = null)
    {
        // cek apakah produk sudah ada di transaksi detail atau belum
        $cek = self::cekProduk($produkId);
        $harga = self::getHarga($produkId, $aksi);

        // jika produk sudah ada maka kurangi jumlahnya
        if ($cek) {
            $cek->update([
                'jumlah_beli' => $aksi == 'tambah' ? $cek->jumlah_beli + 1 : $cek->jumlah_beli - 1,
                // 'harga_satuan' => $harga,
                // 'harga_total' => $cek->jumlah_beli * $harga
            ]);
        } else {
            // jika produk belum ada maka tambahkan
            TransaksiDetail::create([
                'transaksi_id' => $this->transaksiId,
                'produk_id' => $produkId,
                'jumlah_beli' => 1,
                'harga_satuan' => $harga,
                'harga_total' => $harga
            ]);
        }

        // refresh harga
        self::refreshHarga($produkId);
    }

    public function updateNamaPembeli()
    {
        $this->validate([
            'namaPembeli' => 'required'
        ]);

        Transaksi::find($this->transaksiId)->update([
            'nama_pembeli' => $this->namaPembeli
        ]);
    }

    public function updatePelanggan()
    {
        // jika tidak ada pelanggan id maka null
        if (!$this->pelangganId) {
            $this->pelangganId = null;
        }

        Transaksi::find($this->transaksiId)->update([
            'pelanggan_id' => $this->pelangganId
        ]);

        // call refresh harga
        foreach (TransaksiDetail::where('transaksi_id', $this->transaksiId)->get() as $item) {
            self::refreshHarga($item->produk_id);
        }
    }

    public function cekSesi()
    {
        $transaksi = Transaksi::where('user_id', Auth::id())
            ->where('status', 'proses')
            ->first();

        if (!$transaksi) {
            $transaksi = Transaksi::create([
                'user_id' => Auth::id(),
                'kode' => 'TRX' . date('ymd') . Str::padLeft(Transaksi::where('user_id', Auth::id())->whereDate('waktu_transaksi', date('Y-m-d'))->count() + 1, 4, '0'),
                'status' => 'proses'
            ]);
        }

        return $transaksi;
    }

    public function cekProduk($produkId)
    {
        // cek apakah produk sudah ada di transaksi detail atau belum
        $cek = TransaksiDetail::where('transaksi_id', $this->transaksiId)
            ->where('produk_id', $produkId)
            ->first();

        return $cek;
    }

    public function getHarga($produkId, $aksi = null)
    {
        // cek apakah pelanggan atau bukan, jika pelanggan gunakan harga pelanggan
        if ($this->pelangganId) {
            // jika ada harga pelanggan, gunakan harga pelanggan
            $harga = Produk::find($produkId)->harga_pelanggan ?? Produk::find($produkId)->harga_jual;
        } else {
            // jika tidak ada harga pelanggan, gunakan harga jual
            $harga = Produk::find($produkId)->harga_jual;
        }

        $jumlahBeli = TransaksiDetail::where('transaksi_id', $this->transaksiId)
            ->where('produk_id', $produkId)
            ->first()->jumlah_beli ?? 0;

        if ($aksi == 'tambah') {
            $jumlahBeli++;
        } elseif ($aksi == 'kurang') {
            $jumlahBeli--;
        }

        // cek apakah produk termasuk produk grosir atau bukan
        $produkGrosir = ProdukGrosir::where('produk_id', $produkId)
            ->where('minimal', '<=', $jumlahBeli)
            ->orderBy('minimal', 'desc')
            ->first();

        // jika sudah bisa grosir, maka gunakan harga grosir. apabila harga grosir lebih tinggi dari harga sekarang, maka gunakan harga sekarang
        if ($produkGrosir) {
            $harga = $produkGrosir->harga_grosir < $harga ? $produkGrosir->harga_grosir : $harga;
        }

        return $harga;
    }

    public function refreshHarga($produkId)
    {
        $cek = self::cekProduk($produkId);
        $harga = self::getHarga($produkId);

        if ($cek) {
            $cek->update([
                'harga_satuan' => $harga,
                'harga_total' => $cek->jumlah_beli * $harga
            ]);
        }
    }

    public function render()
    {
        $transaksiDetail = TransaksiDetail::where('transaksi_id', $this->transaksiId)->get();

        // isi jumlahBeli dengan jumlah_beli dari transaksi detail
        foreach ($transaksiDetail as $item) {
            $this->jumlahBeli[$item->id] = $item->jumlah_beli;
        }

        return view('livewire.pesanan', [
            'transaksi' => Transaksi::find($this->transaksiId),
            'transaksiDetail' => $transaksiDetail,
            'daftarPelanggan' => Pelanggan::all()->pluck('nama', 'id'),
        ]);
    }
}
