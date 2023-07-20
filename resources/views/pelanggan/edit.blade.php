@extends('layouts.app', ['title' => 'Edit Pelanggan'])

@section('breadcrumb')
<!-- <li class="breadcrumb-item">Gudang</li> -->
<li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <form action="{{ route('pelanggan.update', $data->id) }}" method="post">
            @csrf
            @method('PUT')
            <x-card title="Edit Data Pelanggan">

                <div class="row">
                    <div class="col-md"><x-input name="nama" label="Nama Pelanggan" type="text" :value="$data->nama" /></div>
                    <div class="col-md"><x-input name="alamat" label="Alamat" type="text" :value="$data->alamat" /></div>
                    <div class="col-md"><x-input name="telepon" label="No Telepon" type="number" :value="$data->telepon" /></div>
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