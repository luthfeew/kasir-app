@extends('layouts.app', ['title' => 'Kas Kasir'])

@section('breadcrumb')
<li class="breadcrumb-item">Laporan</li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <x-card title="Laporan Kas Kasir">
            <livewire:laporan.kas-kasir />
        </x-card>

    </div>
</div>
@endsection