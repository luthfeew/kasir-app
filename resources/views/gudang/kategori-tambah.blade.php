@extends('layouts.app', ['title' => 'Tambah Kategori Produk'])

@section('css')
<!-- SweetAlert2 -->
<!-- <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}"> -->
@endsection

@section('js')
<!-- SweetAlert2 -->
<!-- <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<script>
    $(function() {
        var Toast = Swal.mixin({
            toast: true,
            position: 'bottom-end',
            showConfirmButton: false,
            timer: 3000
        });

        $('.swalDefaultError').click(function() {
            Toast.fire({
                icon: 'error',
                title: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr.'
            })
        });
    });
</script> -->
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">Gudang</li>
<li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">

            <form method="post" action="{{ route('kategori.store') }}">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="nama">Nama Kategori</label>
                        <input name="nama" value="{{ old('nama') }}" type="text" class="form-control" id="nama" placeholder="Masukkan Nama Kategori" required>
                    </div>
                    <div class="form-group">
                        <label for="urutan">Urutan</label>
                        <input name="urutan" value="{{ old('urutan') }}" type="number" class="form-control" id="urutan" placeholder="Masukkan Urutan" required>
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