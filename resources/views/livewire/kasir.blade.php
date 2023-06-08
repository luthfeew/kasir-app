<div>
    <div class="card-body">

        <p>
            No Transaksi: {{ $transaksi->id }} <br>
            Kasir: {{ $transaksi->user->name }} <br>
            Nama Pelanggan: - <br>
        </p>

        <!-- {{ $transaksi }} -->

        <div class="table-responsive">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>QTY</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi->transaksiDetail as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->produk->nama }}</td>
                        <td>
                            <div class="row">
                                <div class="col-3">
                                    <input type="text" class="form-control form-control-sm" value="{{ $item->jumlah }}">
                                </div>
                            </div>
                        </td>
                        <td>{{ $item->produk->harga_jual * $item->jumlah }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-right" colspan="3">Total Harga</td>
                        <td>
                            @php
                            $hargaTotal = 0;
                            foreach ($transaksi->transaksiDetail as $item) {
                            $hargaTotal += $item->jumlah * $item->produk->harga_jual;
                            }
                            @endphp
                            {{ $hargaTotal }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
    <div class="card-footer">
        <button type="button" class="btn btn-primary"><i class="fas fa-money-bill"></i> Bayar</button>
        <button type="button" class="btn btn-secondary"><i class="fas fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Batal</button>
    </div>
</div>