@extends('layouts.app', ['title' => 'Detail Penjualan'])

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection

@section('js')
<!-- DataTables & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    @if ($transaksi->status == 'selesai')
                    Penjualan
                    @else
                    Refund
                    @endif
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <b>No Transaksi: #{{ $transaksi->id }}</b><br>
                <br>
                <b>Kasir:</b> {{ $transaksi->user->name }}<br>
                <b>Waktu:</b> {{ $transaksi->updated_at }}<br>
                <b>Nama Pelanggan:</b> {{ $transaksi->nama_pelanggan }}<br>
                <h3>Harga Total:</b> Rp. {{ number_format($transaksi->harga_total, 0, ',', '.') }}</h3>
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Harga Satuan</th>
                            <th>QTY</th>
                            <th>Harga Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksiDetail as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->produk->nama }}</td>
                            <td>
                                @isset ($item->produk->harga_jual_grosir)
                                @if ($item->produk->harga_jual != $item->produk->harga_jual_grosir)
                                <del>Rp. {{ number_format($item->produk->harga_jual, 0, ',', '.') }}</del><br>
                                @endif
                                Rp. {{ number_format($item->produk->harga_jual_grosir, 0, ',', '.') }}
                                @else
                                Rp. {{ number_format($item->produk->harga_jual, 0, ',', '.') }}
                                @endisset
                            </td>
                            <td>{{ abs($item->jumlah) }}</td>
                            <td>
                                @isset ($item->produk->harga_jual_grosir)
                                Rp. {{ number_format(abs($item->jumlah * $item->produk->harga_jual_grosir), 0, ',', '.') }}
                                @else
                                Rp. {{ number_format(abs($item->jumlah * $item->produk->harga_jual), 0, ',', '.') }}
                                @endisset
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Harga Satuan</th>
                            <th>QTY</th>
                            <th>Harga Total</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
            @if($transaksi->refunded || $transaksi->status == 'refund')
            <div class="card-footer">
                <b>Transaksi ini sudah pernah direfund</b>
            </div>
            @else
            <div class="card-footer">
                <a href="{{ route('penjualan.refund', $transaksi->id) }}" class="btn btn-info">Refund</a>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection