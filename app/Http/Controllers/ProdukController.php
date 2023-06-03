<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\ProdukKategori;

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

        Produk::create($request->all());

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Produk::find($id);
        $kategori = ProdukKategori::all();
        return view('gudang.produk-edit', compact('data', 'kategori'));
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

        Produk::find($id)->update($request->all());

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Produk::find($id);
        $data->delete();

        return redirect()->route('produk.index')->with('success', 'Data berhasil dihapus');
    }
}
