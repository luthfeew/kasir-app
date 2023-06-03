@extends('layouts.app', ['title' => 'Kategori Produk'])

@section('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection

@section('js')
<!-- DataTables & Plugins -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{ asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{ asset('plugins/pdfmake/vfs_fonts.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
</script>
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">Gudang</li>
@endsection

@section('content')
<div class="row">
    <div class="col">
        <div class="card">

            <div class="card-header">
                <a href="{{ route('kategori.create') }}" type="button" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Kategori Produk</a>
            </div>

            <div class="card-body">
                <table id="example1" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Urutan</th>
                            <th>Nama</th>
                            <!-- <th>Produk Terdaftar</th> -->
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $value)
                        <tr>
                            <td>{{ $value->urutan }}</td>
                            <td>{{ $value->nama }}</td>
                            <!-- <td></td> -->
                            <td>
                                <a href="{{ route('kategori.edit', $value->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                <form action="{{ route('kategori.destroy', $value->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus item ini?')">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Urutan</th>
                            <th>Nama</th>
                            <!-- <th>Produk Terdaftar</th> -->
                            <th>#</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection