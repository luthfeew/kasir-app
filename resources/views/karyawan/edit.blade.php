@extends('layouts.app', ['title' => 'Edit Karyawan'])

@section('breadcrumb')
<!-- <li class="breadcrumb-item">Gudang</li> -->
<li class="breadcrumb-item"><a href="{{ route('karyawan.index') }}">Karyawan</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <form action="{{ route('karyawan.update', $data->id) }}" method="post">
            @csrf
            @method('PUT')
            <x-card title="Edit Data Karyawan">

                <h4 class="bg-primary">Data Karyawan</h4>
                <div class="row">
                    <div class="col-md"><x-input name="nama" label="Nama Karyawan" type="text" :value="$data->nama" /></div>
                    <div class="col-md-5"><x-input name="alamat" label="Alamat" type="text" :value="$data->alamat" /></div>
                    <div class="col-md-3"><x-input name="telepon" label="No Telepon" type="number" :value="$data->telepon" /></div>
                </div>

                <h4 class="bg-primary mt-3">Data Login</h4>
                <div class="row">
                    <div class="col-md"><x-input name="username" label="Username" type="text" :value="$data->username" /></div>
                    <div class="col-md"><x-input name="password" label="Password Baru (opsional)" type="password" placeholder="asasd" /></div>
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