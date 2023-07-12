@extends('layouts.app', ['title' => 'Tutup Kasir'])

@section('breadcrumb')
<li class="breadcrumb-item">Laporan</li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <x-card title="Laporan Tutup Kasir">
            <livewire:laporan.tutup-kasir />
        </x-card>

    </div>
</div>
@endsection