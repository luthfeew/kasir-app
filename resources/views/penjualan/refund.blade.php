@extends('layouts.app', ['title' => 'Refund Penjualan'])

@section('css')
<!-- icheck bootstrap -->
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@endsection

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
@endsection

@section('content')
{{-- $transaksi --}}
{{-- $transaksiDetail --}}
<div class="row">
    <div class="col">
        <div class="card card-primary card-outline">
            <form action="{{ route('penjualan.refund.store', $transaksi->id) }}" method="post">
                @csrf
                <div class="card-body">
                    <b>No Transaksi: #{{ $transaksi->id }}</b><br>
                    <br>
                    <b>Kasir:</b> {{ $transaksi->user->name }}<br>
                    <b>Waktu:</b> {{ $transaksi->updated_at }}<br>
                    <b>Nama Pelanggan:</b> {{ $transaksi->nama_pelanggan }}<br>
                    <h3>
                        <!-- Harga Total:</b> Rp. {{ number_format($transaksi->harga_total, 0, ',', '.') }} -->
                        <!-- Harga Total: <span id="hargaTotal">Rp. {{ number_format($transaksi->harga_total, 0, ',', '.') }}</span> -->
                        Harga Total: Rp. <span id="hargaTotal">{{ $transaksi->harga_total }}</span>
                        <input hidden id="hargaTotal2" name="harga_total" type="number" value="{{ $transaksi->harga_total }}">
                    </h3>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Refund</th>
                                    <th>Nama Produk</th>
                                    <th>Harga Satuan</th>
                                    <th>QTY</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>

                                <input hidden type="number" name="transaksi_id" value="{{$transaksi->id}}">
                                <input hidden type="text" name="nama_pelanggan" value="{{$transaksi->nama_pelanggan}}">

                                @foreach ($transaksiDetail as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <!-- <input type="checkbox" name="refund[{{$item->id}}]" id=""> -->
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" name="refund[{{$item->id}}]" id="checkboxPrimary[{{$item->id}}]" checked>
                                            <label for="checkboxPrimary[{{$item->id}}]">
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $item->produk->nama }}
                                    </td>
                                    <td>
                                        <input hidden type="number" name="harga_satuan_refund[{{$item->id}}]" value="{{ $item->produk->harga_jual }}">
                                        <span id="price[{{$item->id}}]">{{ $item->produk->harga_jual }}</span>
                                    </td>
                                    <td>
                                        <input hidden type="number" name="produk_id[{{$item->id}}]" value="{{ $item->produk_id }}">
                                        <input type="number" name="qty[{{$item->id}}]" id="qty[{{$item->id}}]" value="{{ $item->jumlah }}" min="1" max="{{ $item->jumlah }}">
                                    </td>
                                    <td>
                                        <span id="total[{{$item->id}}]">{{ $item->produk->harga_jual * $item->jumlah }}</span>
                                    </td>
                                </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="form-group row">
                                <label for="alasan_refund" class="col-sm-2 col-form-label">Alasan Refund</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="alasan_refund" name="alasan_refund" placeholder="Masukkan Alasan Refund" required>
                                </div>
                            </div>
                            <!-- <div class="form-group row">
                                <label for="password" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    // tambahkan event listener apabila qty diubah maka total harga akan berubah
    document.querySelectorAll('[id^="qty"]').forEach(item => {
        item.addEventListener('change', function() {
            let id = this.id.split('[')[1].split(']')[0];
            let qty = this.value;
            let price = document.getElementById('price[' + id + ']').innerHTML;
            let total = qty * price;
            document.getElementById('total[' + id + ']').innerHTML = total;
            let sum = 0;
            document.querySelectorAll('[id^="total"]').forEach(item => {
                sum += parseInt(item.innerHTML);
            });
            document.getElementById('hargaTotal').innerHTML = sum;
            document.getElementById('hargaTotal2').value = sum;
        });
    });

    // tambahkan event listener apabila checkbox tidak dicentang maka qty akan menjadi 0
    document.querySelectorAll('[id^="checkboxPrimary"]').forEach(item => {
        item.addEventListener('change', function() {
            let id = this.id.split('[')[1].split(']')[0];
            if (this.checked == false) {
                document.getElementById('qty[' + id + ']').value = 0;
                document.getElementById('qty[' + id + ']').disabled = true;
                document.getElementById('total[' + id + ']').innerHTML = 0;
            } else {
                document.getElementById('qty[' + id + ']').disabled = false;
                var max = document.getElementById('qty[' + id + ']').max;
                document.getElementById('qty[' + id + ']').value = max;
                let qty = document.getElementById('qty[' + id + ']').value;
                let price = document.getElementById('price[' + id + ']').innerHTML;
                let total = qty * price;
                document.getElementById('total[' + id + ']').innerHTML = total;
            }
            let sum = 0;
            document.querySelectorAll('[id^="total"]').forEach(item => {
                sum += parseInt(item.innerHTML);
            });
            document.getElementById('hargaTotal').innerHTML = sum;
            document.getElementById('hargaTotal2').value = sum;
        });
    });
</script>
@endsection