@extends('layouts.app', ['title' => 'Edit Karyawan'])

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('karyawan.index') }}">Karyawan</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <div class="card card-primary card-outline">

            <form action="{{ route('karyawan.update', $karyawan->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <h4 class="bg-primary">Data Karyawan</h4>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="name">Nama Karyawan</label>
                                <input name="name" value="{{ $karyawan->name }}" type="text" class="form-control" id="name" placeholder="Masukkan Nama Karyawan" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input name="alamat" value="{{ $karyawan->alamat }}" type="text" class="form-control" id="alamat" placeholder="Masukkan Alamat" required>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="no_telp">No Telepon</label>
                                <input name="no_telp" value="{{ $karyawan->no_telp }}" type="text" class="form-control" id="no_telp" placeholder="Masukkan No Telepon" required>
                            </div>
                        </div>
                    </div>

                    <h4 class="mt-3 bg-primary">Data Login</h4>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input name="username" value="{{ $karyawan->username }}" type="text" class="form-control" id="username" placeholder="Buat Username" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input name="password" value="" type="password" class="form-control" id="password" placeholder="(isi jika ingin mengubah password)">
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
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>

            </form>

        </div>

    </div>
</div>
@endsection