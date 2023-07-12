@extends('layouts.app', ['title' => 'Top Report'])

@section('breadcrumb')
<li class="breadcrumb-item">Laporan</li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <x-card title="Laporan Top Report">
            <livewire:laporan.top-report />
        </x-card>

    </div>
</div>
@endsection