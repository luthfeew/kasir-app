@extends('layouts.app', ['title' => 'Edit Produk'])

@section('breadcrumb')
<li class="breadcrumb-item">Gudang</li>
<li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Produk</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <form action="{{ route('produk.update', $data->id) }}" method="post">
            @csrf
            @method('PUT')
            <x-card title="Edit Produk">

                <h4 class="bg-primary">Detail Produk</h4>
                <div class="row">
                    <div class="col"><x-input name="nama" label="Nama Produk" type="text" :value="$data->nama" /></div>
                    <div class="col-4"><x-select name="produk_kategori_id" label="Kategori" :selected="$data->produk_kategori_id" :options="$kategori" /></div>
                </div>
                <div class="row">
                    <div class="col"><x-input name="sku" label="Nomor SKU" type="text" :value="$data->sku" /></div>
                    <div class="col-4"><x-input name="stok" label="Stok" type="number" :value="$stok" /></div>
                </div>
                <div class="row">
                    <div class="col"><x-input name="harga_beli" label="Harga Beli (Rp)" type="number" :value="$data->harga_beli" /></div>
                    <div class="col"><x-input name="harga_jual" label="Harga Jual (Rp)" type="number" :value="$data->harga_jual" /></div>
                    <div class="col"><x-input name="harga_pelanggan" label="Harga Pelanggan (Rp)" type="number" :value="$data->harga_pelanggan" /></div>
                    <div class="col-3"><x-input name="satuan" label="Satuan" type="text" :value="$data->satuan" /></div>
                </div>

                <h4 class="bg-primary mt-3">Grosir (opsional)</h4>
                @foreach ($grosir as $i => $item)
                <div class="row">
                    <div class="col-3"><x-input name="minimal[{{ $i }}]" label="Minimal Pembelian" type="number" :value="$item->minimal" /></div>
                    <div class="col-3"><x-input name="grosir[{{ $i }}]" label="Harga Grosir (Rp)" type="number" :value="$item->harga_grosir" /></div>
                </div>
                @endforeach

                <x-slot name="footer">
                    <x-button>Update</x-button>
                    <x-button type="reset" color="secondary">Reset</x-button>
                </x-slot>

            </x-card>
        </form>

    </div>
</div>
@endsection