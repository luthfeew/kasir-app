@extends('layouts.app', ['title' => 'Tambah Pelanggan'])

@section('breadcrumb')
<!-- <li class="breadcrumb-item">Gudang</li> -->
<li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <form action="{{ route('pelanggan.store') }}" method="post">
            @csrf
            <x-card title="Tambah Data Pelanggan">

                <div class="row">
                    <div class="col-md"><x-input name="nama" label="Nama Pelanggan" type="text" /></div>
                    <div class="col-md-5"><x-input name="alamat" label="Alamat" type="text" /></div>
                    <div class="col-md-3"><x-input name="telepon" label="No Telepon" type="number" /></div>
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