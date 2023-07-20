@extends('layouts.app', ['title' => 'Penjualan'])

@section('content')
<div class="row">
    <div class="col">

        <x-card title="Daftar Penjualan">

            <x-data-tables :kolomTabel="['No', 'Kode', 'Nama', 'Kasir', 'Total', 'Waktu', 'Status', 'Aksi']">
                @forelse ($transaksis as $transaksi)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $transaksi->kode }}</td>
                    <td>{{ $transaksi->nama_pembeli }}</td>
                    <td>{{ $transaksi->user->nama }}</td>
                    <td>@rupiah($transaksi->bayar->harga_total)</td>
                    <td>{{ $transaksi->waktu_transaksi }}</td>
                    <td>
                        @if ($transaksi->status == 'selesai')
                        <span class="badge badge-success">Selesai</span>
                        @else
                        <span class="badge badge-warning">{{ Str::ucfirst($transaksi->status) }}</span>
                        @endif

                        @if ($transaksi->is_hutang)
                        <span class="badge badge-danger">Hutang</span>
                        @endif

                        @if ($transaksi->is_refund)
                        <span class="badge badge-danger">Refund</span>
                        @endif
                    </td>
                    <td>
                        <!-- <x-button-edit link="{{ route('penjualan.edit', $transaksi->id) }}" /> -->
                        <!-- <x-button-delete link="{{ route('penjualan.destroy', $transaksi->id) }}" /> -->
                        <x-button-show link="{{ route('penjualan.show', $transaksi->id) }}" />
                        <!-- <a href="{{ route('penjualan.refund', $transaksi->id) }}" class="btn btn-sm btn-danger">Refund</a> -->
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </x-data-tables>

        </x-card>

    </div>
</div>
@endsection