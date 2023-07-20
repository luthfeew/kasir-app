@extends('layouts.app', ['title' => 'Detail Penjualan'])

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <x-card title="Detail Penjualan">

            <b>No Transaksi: #{{ $transaksi->kode }}</b><br>
            <br>
            <b>Kasir:</b> {{ $transaksi->user->nama }}<br>
            <b>Waktu:</b> {{ $transaksi->waktu_transaksi }}<br>
            <b>Nama Pelanggan:</b> {{ $transaksi->pelanggan->nama ?? $transaksi->nama_pembeli }}<br>
            <h3>Harga Total:</b> @rupiah($transaksi->bayar->harga_total)</h3>

            <x-data-tables :kolomTabel="['No', 'Nama Barang', 'Harga Satuan', 'QTY', 'Harga Total']">
                @forelse ($transaksiDetail as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->produk->nama }}</td>
                    <td>
                        @if ($item->produk->harga_jual != $item->harga_satuan)
                        <del>@rupiah($item->produk->harga_jual)</del>
                        @endif
                        @rupiah($item->harga_satuan)
                    </td>
                    <td>{{ abs($item->jumlah_beli) }}</td>
                    <td>@rupiah($item->harga_total)</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </x-data-tables>

            <x-slot name="footer">
                <!-- Refund if is_refundable -->
                @if ($is_refundable && !$transaksi->is_refund)
                <a href="{{ route('penjualan.refund', $transaksi->id) }}" class="btn btn-danger">Refund</a>
                @endif
            </x-slot>

        </x-card>

    </div>
</div>
@endsection