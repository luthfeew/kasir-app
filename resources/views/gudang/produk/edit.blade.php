@extends('layouts.app', ['title' => 'Edit Produk'])

@section('breadcrumb')
<li class="breadcrumb-item">Gudang</li>
<li class="breadcrumb-item"><a href="{{ route('produk.index') }}">Produk</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col-md">

        <form action="{{ route('produk.update', $data->id) }}" method="post">
            @csrf
            @method('PUT')
            <x-card title="Edit Produk">

                <h4 class="bg-primary">Detail Produk</h4>
                <div class="row">
                    <div class="col-md"><x-input name="nama" label="Nama Produk" type="text" :value="$data->nama" /></div>
                    <div class="col-md-4"><x-select name="produk_kategori_id" label="Kategori" :options="$kategori" :selected="$data->produk_kategori_id" /></div>
                </div>
                <div class="row">
                    <div class="col-md"><x-input name="sku" label="Nomor SKU" type="text" :value="$data->sku" /></div>
                    <div class="col-md-4"><x-input name="stok" label="Stok" type="number" :value="$stok" /></div>
                </div>
                <div class="row">
                    <div class="col-md"><x-input name="harga_beli" label="Harga Beli (Rp)" type="number" :value="$data->harga_beli" /></div>
                    <div class="col-md"><x-input name="harga_jual" label="Harga Jual (Rp)" type="number" :value="$data->harga_jual" /></div>
                    <div class="col-md-3"><x-input name="satuan" label="Satuan" type="text" :value="$data->satuan" /></div>
                </div>

                <h4 class="bg-primary mt-3">Harga Pelanggan & Grosir (opsional)</h4>
                <div class="row">
                    <div class="col-md"><x-input name="harga_pelanggan" label="Harga Pelanggan (Rp)" type="number" :value="$data->harga_pelanggan" /></div>
                    <div class="col-md"></div>
                    <div class="col-md-3"></div>
                </div>

                <table class="table table-sm table-bordered" id="tabel_grosir">
                    <tr>
                        <th>Minimal Pembelian</th>
                        <th>Harga Grosir (Rp)</th>
                        <th>Aksi</th>
                    </tr>
                    @forelse ($grosir as $i => $item)
                    <tr>
                        <td><x-input name="minimal[{{ $i }}]" type="number" placeholder="QTY" :value="$item->minimal" /></td>
                        <td><x-input name="grosir[{{ $i }}]" type="number" placeholder="Masukkan Harga" :value="$item->harga_grosir" /></td>
                        @if ($loop->first)
                        <td><x-button type="button" class="btn-sm" id="tambah_grosir"><i class="fa fa-plus"></i></x-button></td>
                        @else
                        <td><button type="button" class="btn btn-sm btn-danger hapus_input"><i class="fa fa-trash"></i></button></td>
                        @endif
                    <tr>
                        @empty
                    <tr>
                        <td><x-input name="minimal[0]" type="number" placeholder="QTY" /></td>
                        <td><x-input name="grosir[0]" type="number" placeholder="Masukkan Harga" /></td>
                        <td><x-button type="button" class="btn-sm" id="tambah_grosir"><i class="fa fa-plus"></i></x-button></td>
                    </tr>
                    @endforelse
                </table>

                <x-slot name="footer">
                    <x-button>Update</x-button>
                    <x-button type="reset" color="secondary">Reset</x-button>
                </x-slot>

            </x-card>
        </form>

    </div>
</div>
@endsection

@push('js')
<script type="text/javascript">
    var i = 69;
    $("#tambah_grosir").click(function() {
        ++i;
        $("#tabel_grosir").append('<tr><td><input type="number" name="minimal[' + i + ']" id="minimal[' + i + ']" class="form-control form-control-border border-width-2" placeholder="QTY"></td><td><input type="number" name="grosir[' + i + ']" id="grosir[' + i + ']" class="form-control form-control-border border-width-2" placeholder="Masukkan Harga"></td><td><button type="button" class="btn btn-sm btn-danger hapus_input"><i class="fa fa-trash"></i></button></td></tr></td></tr>');
    });
    $(document).on('click', '.hapus_input', function() {
        $(this).parents('tr').remove();
    });
</script>
@endpush