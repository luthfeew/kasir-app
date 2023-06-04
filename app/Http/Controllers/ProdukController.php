<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\Produk;
use App\Models\ProdukKategori;
use App\Models\ProdukGrosir;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Produk::all();
        return view('gudang.produk', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = ProdukKategori::all();
        return view('gudang.produk-tambah', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate(
            [
                'nama' => 'required|unique:produks,nama',
                'sku' => 'required|unique:produks,sku',
                'stok' => 'required|numeric',
                'harga_beli' => 'required|numeric',
                'harga_jual' => 'required|numeric',
                'satuan' => 'required',
                'produk_kategori_id' => 'required',
            ],
            [
                'nama.required' => 'Nama produk harus diisi',
                'nama.unique' => 'Nama produk sudah ada',
                'sku.required' => 'SKU produk harus diisi',
                'sku.unique' => 'SKU produk sudah ada',
                'stok.required' => 'Stok produk harus diisi',
                'stok.numeric' => 'Stok produk harus berupa angka',
                'harga_beli.required' => 'Harga beli produk harus diisi',
                'harga_beli.numeric' => 'Harga beli produk harus berupa angka',
                'harga_jual.required' => 'Harga jual produk harus diisi',
                'harga_jual.numeric' => 'Harga jual produk harus berupa angka',
                'satuan.required' => 'Satuan produk harus diisi',
                'produk_kategori_id.required' => 'Kategori produk harus diisi',
            ]
        );

        $produk = Produk::create($request->all());

        // get kelipatan1, keliapatan2, kelipatan3 and convert it to single array
        $kelipatan = array($request->kelipatan1, $request->kelipatan2, $request->kelipatan3);
        $harga = array($request->harga1, $request->harga2, $request->harga3);
        // pair array kelipatan and harga
        $x = array_combine($kelipatan, $harga);
        // remove array with empty key or value
        $x = Arr::where($x, function ($value, $key) {
            return $key != null && $value != null;
        });
        // unpair array kelipatan and harga
        $kelipatan = array_keys($x);
        $harga = array_values($x);

        // save to produk_grosir table
        for ($i = 0; $i < count($kelipatan); $i++) {
            ProdukGrosir::create([
                'produk_id' => $produk->id,
                'kelipatan' => $kelipatan[$i],
                'harga' => $harga[$i],
            ]);
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Produk::find($id);
        return view('gudang.produk-detail', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Produk::find($id);
        $kategori = ProdukKategori::all();
        $grosir = ProdukGrosir::where('produk_id', $id)->get();
        return view('gudang.produk-edit', compact('data', 'kategori', 'grosir'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'nama' => 'required|unique:produks,nama,' . $id,
                'sku' => 'required|unique:produks,sku,' . $id,
                'stok' => 'required|numeric',
                'harga_beli' => 'required|numeric',
                'harga_jual' => 'required|numeric',
                'satuan' => 'required',
                'produk_kategori_id' => 'required',
            ],
            [
                'nama.required' => 'Nama produk harus diisi',
                'nama.unique' => 'Nama produk sudah ada',
                'sku.required' => 'SKU produk harus diisi',
                'sku.unique' => 'SKU produk sudah ada',
                'stok.required' => 'Stok produk harus diisi',
                'stok.numeric' => 'Stok produk harus berupa angka',
                'harga_beli.required' => 'Harga beli produk harus diisi',
                'harga_beli.numeric' => 'Harga beli produk harus berupa angka',
                'harga_jual.required' => 'Harga jual produk harus diisi',
                'harga_jual.numeric' => 'Harga jual produk harus berupa angka',
                'satuan.required' => 'Satuan produk harus diisi',
                'produk_kategori_id.required' => 'Kategori produk harus diisi',
            ]
        );

        $produk = Produk::find($id)->update($request->all());

        // get kelipatan1, keliapatan2, kelipatan3 and convert it to single array
        $kelipatan = array($request->kelipatan1, $request->kelipatan2, $request->kelipatan3);
        $harga = array($request->harga1, $request->harga2, $request->harga3);
        // pair array kelipatan and harga
        $x = array_combine($kelipatan, $harga);
        // remove array with empty key or value
        $x = Arr::where($x, function ($value, $key) {
            return $key != null && $value != null;
        });
        // unpair array kelipatan and harga
        $kelipatan = array_keys($x);
        $harga = array_values($x);
        // remove all data in produk_grosir table
        ProdukGrosir::where('produk_id', $id)->delete();
        // save to produk_grosir table
        for ($i = 0; $i < count($kelipatan); $i++) {
            ProdukGrosir::create([
                'produk_id' => $id,
                'kelipatan' => $kelipatan[$i],
                'harga' => $harga[$i],
            ]);
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Produk::find($id);
        $data->delete();
        
        // also delete data in produk_grosir table
        ProdukGrosir::where('produk_id', $id)->delete();

        return redirect()->route('produk.index')->with('success', 'Data berhasil dihapus');
    }
}
