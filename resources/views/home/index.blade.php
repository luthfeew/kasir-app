@extends('layouts.app', ['title' => 'Beranda'])

@section('content')
<div class="row">
    <div class="col">

        <x-card title="Status Kasir">
            @if ($sesi)
            <x-alert type="success" title="Aktif">
                Waktu mulai: {{ $sesi->waktu_mulai->format('d-m-Y H:i:s') }} <br>
                Saldo awal: Rp {{ number_format($sesi->saldo_awal, 0, ',', '.') }}
            </x-alert>
            @else
            <x-alert type="info" title="Tidak Aktif">
                Kasir tidak dapat melakukan transaksi. Apabila ingin melakukan transaksi, silakan buka kasir terlebih dahulu.
            </x-alert>
            @endif
        </x-card>

    </div>
</div>
@endsection