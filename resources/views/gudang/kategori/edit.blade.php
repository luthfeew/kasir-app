@extends('layouts.app', ['title' => 'Edit Kategori Produk'])

@section('breadcrumb')
<li class="breadcrumb-item">Gudang</li>
<li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <form action="{{ route('kategori.update', $data->id) }}" method="post">
            @csrf
            @method('PUT')
            <x-card title="Edit Kategori Produk">

                <div class="row">
                    <div class="col-md"><x-input name="nama" label="Nama Kategori" type="text" :value="$data->nama" /></div>
                    <div class="col-md"><x-input name="urutan" label="Urutan" type="number" :value="$data->urutan" /></div>
                </div>

                <x-slot name="footer">
                    <x-button>Update</x-button>
                    <x-button type="reset" color="secondary">Reset</x-button>
                </x-slot>

            </x-card>
        </form>

    </div>
</div>
@endsection