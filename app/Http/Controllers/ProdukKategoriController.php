<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdukKategori;

class ProdukKategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ProdukKategori::all();
        return view('gudang.kategori', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gudang.kategori-tambah');
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

        ProdukKategori::create([
            'nama' => $request->nama,
            'urutan' => $request->urutan,
        ]);

        return redirect()->route('kategori.index')->with('success', 'Berhasil menambahkan kategori produk.');
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
        $data = ProdukKategori::find($id);
        return view('gudang.kategori-edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|unique:produk_kategoris,nama,' . $id,
            'urutan' => 'required|numeric',
        ]);

        $data = ProdukKategori::find($id);
        $data->nama = $request->nama;
        $data->urutan = $request->urutan;
        $data->save();

        return redirect()->route('kategori.index')->with('success', 'Berhasil mengubah kategori produk.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = ProdukKategori::find($id);
        $data->delete();

        return redirect()->route('kategori.index')->with('success', 'Data berhasil dihapus');
    }
}
