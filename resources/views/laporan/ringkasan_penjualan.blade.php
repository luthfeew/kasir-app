@extends('layouts.app', ['title' => 'Ringkasan Penjualan'])

@section('breadcrumb')
<li class="breadcrumb-item">Laporan</li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <x-card title="Laporan Ringkasan Penjualan">
            <livewire:laporan.ringkasan-penjualan />
        </x-card>

    </div>
</div>
@endsection