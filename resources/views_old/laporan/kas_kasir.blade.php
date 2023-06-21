@extends('layouts.app', ['title' => 'Laporan | Kas Kasir'])

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card card-primary card-outline">

            <div class="card-header">
                <a href="{{ route('laporan.kas_kasir.create') }}" type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Transaksi Kas Kasir</a>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-arrow-down"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">MASUK</span>
                                <span class="info-box-number">
                                    Rp. {{ number_format($masuk, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-arrow-up"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">KELUAR</span>
                                <span class="info-box-number">
                                    Rp. {{ number_format($keluar, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Transaksi</th>
                                <th>Masuk</th>
                                <th>Keluar</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                            <tr>
                                <td>{{ $item->nama_transaksi }}</td>
                                <td>{{ $item->jenis == 'masuk' ? $item->nominal : '-' }}</td>
                                <td>{{ $item->jenis == 'keluar' ? $item->nominal : '-' }}</td>
                                <td>{{ $item->catatan }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection