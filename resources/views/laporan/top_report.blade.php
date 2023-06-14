@extends('layouts.app', ['title' => 'Laporan | Top 10 Report'])

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Produk</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Qty</th>
                                <th>Produk</th>
                                <th>Harga Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->total_qty}}</td>
                                <td>{{$item->produk->nama}}</td>
                                <td>Rp. {{ number_format(($item->total_qty * $item->produk->harga_jual), 0, ',', '.') }}</td>
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