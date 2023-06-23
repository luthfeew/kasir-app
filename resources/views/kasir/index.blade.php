@extends('layouts.app', ['title' => 'Kasir'])

@section('content')
<div class="row">
    <div class="col-xl-4">
        <x-card title="Cari Produk">
            <livewire:cari-produk />
        </x-card>
    </div>
    <div class="col-xl">
        <x-card title="Rincian Pesanan">
            <livewire:pesanan :transaksi_id="$id" />
        </x-card>
    </div>
</div>
<div class="row">
    <div class="col-xl">
        <x-card title="Transaksi Pending">
        </x-card>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endpush

@push('js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    $(function() {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Pilih salah satu",
            tags: true,
        })
    })
</script>
@endpush