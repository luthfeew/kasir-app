@extends('layouts.app', ['title' => 'Hutang'])

@section('breadcrumb')
<li class="breadcrumb-item">Laporan</li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <x-card title="Laporan Hutang">
            <x-data-tables :kolomTabel="['Nama Pembeli', 'Tanggal', 'Total Hutang']">
                @foreach ($transaksiHutang as $item)
                <tr>
                    <td>{{ $item->first()->pelanggan_id ? $item->first()->pelanggan->nama : $item->first()->nama_pembeli }}</td>
                    <td>
                        @foreach ($item as $transaksi)
                        {{ $transaksi->created_at->format('d/m/Y') }}<br>
                        @endforeach
                    </td>
                    <td>
                        @php
                        $totalHutang = 0;
                        foreach ($item as $transaksi) {
                        $totalHutang += $transaksi->bayar->hutang;
                        }
                        @endphp
                        Rp {{ number_format($totalHutang, 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </x-data-tables>
        </x-card>

    </div>
</div>
@endsection