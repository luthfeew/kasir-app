<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\ProdukKategori;
use App\Models\ProdukGrosir;
use App\Models\Inventaris;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Produk::all();
        return view('gudang.produk.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = ProdukKategori::all()->pluck('nama', 'id');
        return view('gudang.produk.create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // clean request delete null value
        $request->merge([
            'minimal' => array_filter($request->minimal),
            'grosir' => array_filter($request->grosir),
        ]);

        $request->validate(
            [
                'nama' => 'required|unique:produks,nama',
                'sku' => 'required|unique:produks,sku',
                'stok' => 'required|numeric|min:1',
                'harga_beli' => 'required|numeric|min:1',
                'harga_jual' => 'required|numeric|gt:harga_beli',
                'satuan' => 'required',
                'produk_kategori_id' => 'required',
                'harga_pelanggan' => 'nullable|numeric|gt:harga_beli',
                'minimal.*' => 'nullable|numeric|min:1',
                'grosir.*' => 'nullable|numeric|gt:harga_beli|lt:harga_jual',
            ],
            [
                'nama.required' => 'Nama produk harus diisi',
                'nama.unique' => 'Nama produk sudah ada',
                'sku.required' => 'SKU produk harus diisi',
                'sku.unique' => 'SKU produk sudah ada',
                'stok.required' => 'Stok produk harus diisi',
                'stok.numeric' => 'Stok produk harus berupa angka',
                'stok.min' => 'Stok produk minimal 1',
                'harga_beli.required' => 'Harga beli produk harus diisi',
                'harga_beli.numeric' => 'Harga beli produk harus berupa angka',
                'harga_beli.min' => 'Harga beli produk minimal 1',
                'harga_jual.required' => 'Harga jual produk harus diisi',
                'harga_jual.numeric' => 'Harga jual produk harus berupa angka',
                'harga_jual.gt' => 'Harga jual produk harus lebih besar dari harga beli',
                'satuan.required' => 'Satuan produk harus diisi',
                'produk_kategori_id.required' => 'Kategori produk harus diisi',
                'harga_pelanggan.numeric' => 'Harga pelanggan produk harus berupa angka',
                'harga_pelanggan.gt' => 'Harga pelanggan produk harus lebih besar dari harga beli',
                'minimal.*.numeric' => 'Minimal grosir produk harus berupa angka',
                'minimal.*.min' => 'Minimal grosir produk minimal 1',
                'grosir.*.numeric' => 'Harga grosir produk harus berupa angka',
                'grosir.*.gt' => 'Harga grosir produk harus lebih besar dari harga beli',
                'grosir.*.lt' => 'Harga grosir produk harus lebih kecil dari harga jual',
            ]
        );

        $produk = Produk::create([
            'produk_kategori_id' => $request->produk_kategori_id,
            'nama' => $request->nama,
            'sku' => $request->sku,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'harga_pelanggan' => $request->harga_pelanggan,
            'satuan' => $request->satuan,
        ]);

        Inventaris::create([
            'produk_id' => $produk->id,
            'stok' => $request->stok,
        ]);

        if ($request->filled('minimal')) {
            foreach ($request->minimal as $key => $value) {
                ProdukGrosir::create([
                    'produk_id' => $produk->id,
                    'minimal' => $value,
                    'harga_grosir' => $request->grosir[$key],
                ]);
            }
        }

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
        $data = Produk::findOrFail($id);
        $kategori = ProdukKategori::all();
        $grosir = ProdukGrosir::where('produk_id', $id)->get();
        $stok = Inventaris::where('produk_id', $id)->sum('stok');
        return view('gudang.produk.edit', compact('data', 'kategori', 'grosir', 'stok'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->merge([
            'minimal' => array_filter($request->minimal),
            'grosir' => array_filter($request->grosir),
        ]);

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

        $produk = Produk::findOrFail($id);
        $produk->update([
            'produk_kategori_id' => $request->produk_kategori_id,
            'nama' => $request->nama,
            'sku' => $request->sku,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'harga_pelanggan' => $request->harga_pelanggan,
            'satuan' => $request->satuan,
        ]);

        $stok = Inventaris::where('produk_id', $id)->sum('stok');
        // check if stok is different with request stok
        if ($stok != $request->stok) {
            // if different, create new record so the sum of stok is equal with request stok
            Inventaris::create([
                'produk_id' => $produk->id,
                'stok' => $request->stok - $stok,
            ]);
        }

        if ($request->filled('minimal')) {
            foreach ($request->minimal as $key => $value) {
                ProdukGrosir::updateOrCreate(
                    [
                        'produk_id' => $produk->id,
                        'minimal' => $value,
                    ],
                    [
                        'harga_grosir' => $request->grosir[$key],
                    ]
                );
            }
        }

        // delete old record if not exist in request
        ProdukGrosir::where('produk_id', $produk->id)
            ->whereNotIn('minimal', $request->minimal)
            ->forceDelete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->forceDelete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus');
    }
}
