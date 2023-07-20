@extends('layouts.app', ['title' => 'Tambah Kategori Produk'])

@section('breadcrumb')
<li class="breadcrumb-item">Gudang</li>
<li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <form action="{{ route('kategori.store') }}" method="post">
            @csrf
            <x-card title="Tambah Kategori Produk">

                <div class="row">
                    <div class="col-md"><x-input name="nama" label="Nama Kategori" type="text" /></div>
                    <div class="col-md"><x-input name="urutan" label="Urutan" type="number" /></div>
                </div>

                <x-slot name="footer">
                    <x-button>Simpan</x-button>
                    <x-button type="reset" color="secondary">Reset</x-button>
                </x-slot>

            </x-card>
        </form>

    </div>
</div>
@endsection