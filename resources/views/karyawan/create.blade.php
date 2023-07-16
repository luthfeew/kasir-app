@extends('layouts.app', ['title' => 'Tambah User'])

@section('breadcrumb')
<!-- <li class="breadcrumb-item">Gudang</li> -->
<li class="breadcrumb-item"><a href="{{ route('karyawan.index') }}">User</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <form action="{{ route('karyawan.store') }}" method="post">
            @csrf
            <x-card title="Tambah Data User">

                <h4 class="bg-primary">Data User</h4>
                <div class="row">
                    <div class="col-md"><x-input name="nama" label="Nama User" type="text" /></div>
                    <div class="col-md-5"><x-input name="alamat" label="Alamat" type="text" /></div>
                    <div class="col-md-3"><x-input name="telepon" label="No Telepon" type="number" /></div>
                </div>

                <h4 class="bg-primary mt-3">Data Login</h4>
                <div class="row">
                    <div class="col-md"><x-input name="username" label="Username" type="text" /></div>
                    <div class="col-md"><x-input name="password" label="Password" type="password" /></div>
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