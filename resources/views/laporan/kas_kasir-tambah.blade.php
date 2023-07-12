@extends('layouts.app', ['title' => 'Tambah Transaksi'])

@section('breadcrumb')
<li class="breadcrumb-item">Laporan</li>
<li class="breadcrumb-item"><a href="{{ route('laporan.kas_kasir') }}">Kas Kasir</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <form action="{{ route('laporan.kas_kasir.store') }}" method="post">
            @csrf
            <x-card title="Tambah Transaksi Kas Kasir">

                <div class="row">
                    <div class="col-md"><x-input name="nama_transaksi" label="Nama Transaksi" type="text" /></div>
                    <div class="col-md"><x-input name="catatan" label="Catatan" type="text" /></div>
                </div>
                <div class="row">
                    <div class="col-md"><x-input name="nominal" label="Nominal" type="number" /></div>
                    <div class="col-md">
                        <div class="form-group clearfix">
                            <label for="">Jenis Transaksi</label>
                            <div class="icheck-primary">
                                <input type="radio" id="radioPrimary1" name="jenis" value="masuk" checked>
                                <label for="radioPrimary1"> Uang Masuk
                                </label>
                            </div>
                            <div class="icheck-warning">
                                <input type="radio" id="radioPrimary2" name="jenis" value="keluar">
                                <label for="radioPrimary2"> Uang Keluar
                                </label>
                            </div>
                        </div>
                    </div>
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

@push('css')
<!-- icheck bootstrap -->
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@endpush