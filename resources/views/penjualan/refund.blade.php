@extends('layouts.app', ['title' => 'Refund Penjualan'])

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <x-card title="Detail Refund">

            <form action="{{ route('penjualan.refund.store', $transaksi->id) }}" method="post">
                @csrf

                <b>No Transaksi: #{{ $transaksi->kode }}</b><br>
                <br>
                <b>Kasir:</b> {{ $transaksi->user->nama }}<br>
                <b>Waktu:</b> {{ $transaksi->created_at }}<br>
                <b>Nama Pelanggan:</b> {{ $transaksi->pelanggan->nama ?? $transaksi->nama_pembeli }}<br>
                <h3>
                    Total Refund:</b> <span id="totalRefundView">@rupiah($transaksi->bayar->harga_total)</span>
                    <input hidden id="totalRefund" name="total_refund" type="number" value="{{ $transaksi->bayar->harga_total }}">
                </h3>

                <x-data-tables :kolomTabel="['No', 'QTY', 'Nama Barang', 'Harga Total']">
                    @forelse ($transaksiDetail as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td style="width: 100px">
                            @env('local')
                            {{ $item->jumlah_beli }}
                            @endenv
                            <input type="hidden" name="produk_id[{{$item->id}}]" value="{{ $item->produk_id }}">
                            <input type="number" name="jumlah_beli[{{$item->id}}]" id="jumlah_beli[{{$item->id}}]" value="{{ $item->jumlah_beli }}" class="form-control" min="0" max="{{ $item->jumlah_beli }}">
                            <input hidden type="number" name="harga_satuan[{{$item->id}}]" id="harga_satuan[{{$item->id}}]" value="{{ $item->harga_satuan }}" class="form-control">
                        </td>
                        <td>{{ $item->produk->nama }}</td>
                        <td>
                            <span id="hargaTotalView[{{$item->id}}]">@rupiah($item->harga_total)</span>
                            <input type="number" name="harga_total[{{$item->id}}]" id="harga_total[{{$item->id}}]" value="{{ $item->harga_total }}" class="form-control">
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </x-data-tables>

                <!-- buat alasan refund dengan component input -->
                <div class="mt-5">
                    <x-input name="alasan" label="Alasan Refund" type="text" required />
                </div>

                <x-slot:footer>
                    <button type="submit" class="btn btn-primary">Refund</button>
                </x-slot:footer>
                <button type="submit" class="btn btn-primary">Refund</button>
            </form>

        </x-card>

    </div>
</div>
@endsection

@push('js')
<script>
    // tambahkan event listener apabila jumlah_beli diubah maka akan mengubah harga_total
    document.querySelectorAll('[id^="jumlah_beli"]').forEach(item => {
        item.addEventListener('input', function() {
            let id = this.id.split('[')[1].split(']')[0];
            let harga_satuan = document.getElementById('harga_satuan[' + id + ']').value;
            let harga_total = this.value * harga_satuan;
            document.getElementById('harga_total[' + id + ']').value = harga_total;
            document.getElementById('hargaTotalView[' + id + ']').innerHTML = "Rp " + harga_total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");

            let sum = 0;
            document.querySelectorAll('[id^="harga_total"]').forEach(item => {
                sum += parseInt(item.value);
            });
            document.getElementById('totalRefund').value = sum;
            document.getElementById('totalRefundView').innerHTML = "Rp " + sum.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        });
    });
</script>
@endpush