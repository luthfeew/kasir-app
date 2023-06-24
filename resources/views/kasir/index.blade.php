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