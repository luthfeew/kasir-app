<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdukKategori;
use App\Models\Produk;

class ProdukKategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ProdukKategori::all();
        return view('gudang.kategori.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gudang.kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'nama' => 'required|unique:produk_kategoris,nama',
                'urutan' => 'required|numeric',
            ],
            [
                'nama.required' => 'Nama kategori harus diisi',
                'nama.unique' => 'Nama kategori sudah ada',
                'urutan.required' => 'Urutan kategori harus diisi',
                'urutan.numeric' => 'Urutan kategori harus berupa angka',
            ]
        );

        ProdukKategori::create($request->all());

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan');
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
        $data = ProdukKategori::findOrFail($id);
        return view('gudang.kategori.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'nama' => 'required|unique:produk_kategoris,nama,' . $id,
                'urutan' => 'required|numeric',
            ],
            [
                'nama.required' => 'Nama kategori harus diisi',
                'nama.unique' => 'Nama kategori sudah ada',
                'urutan.required' => 'Urutan kategori harus diisi',
                'urutan.numeric' => 'Urutan kategori harus berupa angka',
            ]
        );

        ProdukKategori::findOrFail($id)->update($request->all());

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $produk = Produk::where('produk_kategori_id', $id)->first();
        // if ($produk) {
        //     return redirect()->route('kategori.index')->with('danger', 'Kategori tidak bisa dihapus karena masih digunakan');
        // }

        ProdukKategori::findOrFail($id)->forceDelete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus');
    }
}
