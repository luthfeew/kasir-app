@extends('layouts.app', ['title' => 'Tambah Transaksi Kas Kasir'])

@section('css')
<!-- icheck bootstrap -->
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('laporan.kas_kasir') }}">Laporan | Kas Kasir</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card card-primary card-outline">

            <form method="post" action="{{ route('laporan.kas_kasir.store') }}">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="nama_transaksi">Nama Transaksi</label>
                                <input name="nama_transaksi" value="{{ old('nama_transaksi') }}" type="text" class="form-control" id="nama_transaksi" placeholder="Masukkan Nama Transaksi" required>
                            </div>
                            <div class="form-group">
                                <label for="catatan">Catatan</label>
                                <input name="catatan" value="{{ old('catatan') }}" type="text" class="form-control" id="catatan" placeholder="Masukkan Catatan" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="nominal">Nominal</label>
                                <input name="nominal" value="{{ old('nominal') }}" type="number" class="form-control" id="nominal" placeholder="Masukkan Nominal" required>
                            </div>
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
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>

        </div>

    </div>
</div>
@endsection