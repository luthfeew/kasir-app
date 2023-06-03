@extends('layouts.app', ['title' => 'Tambah Produk'])

@section('css')
<!-- Select2 -->
<!-- <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}"> -->
@endsection

@section('js')
<!-- Select2 -->
<!-- <script src="{{ asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    })
</script> -->
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">Gudang</li>
<li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Produk</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">

            <form method="post" action="{{ route('produk.update', $data->id) }}">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-8">
                            <div class="form-group">
                                <label for="nama">Nama Produk</label>
                                <input name="nama" value="{{ $data->nama }}" type="text" class="form-control" id="nama" placeholder="Masukkan Nama Produk" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Pilih Kategori</label>
                                <select name="produk_kategori_id" class="form-control" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategori as $k)
                                    @if($k->id == $data->produk_kategori_id)
                                    <option value="{{ $k->id }}" selected>{{ $k->nama }}</option>
                                    @else
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="form-group">
                                <label for="sku">Nomor SKU</label>
                                <input name="sku" value="{{ $data->sku }}" type="text" class="form-control" id="sku" placeholder="Masukkan Nomor SKU" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="stok">Stok</label>
                                <input name="stok" value="{{ $data->stok }}" type="number" class="form-control" id="stok" placeholder="Masukkan Stok" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="harga_beli">Harga Beli (Rp)</label>
                                <input name="harga_beli" value="{{ $data->harga_beli }}" type="number" class="form-control" id="harga_beli" placeholder="Masukkan Harga Beli" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="harga_jual">Harga Jual (Rp)</label>
                                <input name="harga_jual" value="{{ $data->harga_jual }}" type="number" class="form-control" id="harga_jual" placeholder="Masukkan Harga Jual" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="satuan">Satuan</label>
                                <input name="satuan" value="{{ $data->satuan }}" type="text" class="form-control" id="satuan" placeholder="Masukkan Satuan" required>
                            </div>
                        </div>
                    </div>
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-ban"></i> Error!</h5>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </form>

        </div>

    </div>
</div>
@endsection