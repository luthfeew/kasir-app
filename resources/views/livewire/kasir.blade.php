<div>
    <div class="card-body">

        <!-- <table class="table table-borderless table-sm">
            <tr>
                <td style="width: 150px">No Transaksi:</td>
                <td>{{ $transaksi->id }}</td>
            </tr>
            <tr>
            <td style="width: 150px">Nama Pelanggan:</td>
            <td>-</td>
        </tr>
        </table> -->

        <p>
            No Transaksi: {{ $transaksi->id }}<br>
            Nama Pelanggan: -
        </p>

        <!-- {{ $transaksi }} -->

        <div class="table-responsive">
            <table class="table table-sm table-bordered table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>QTY</th>
                        <th>Nama</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaksi->transaksiDetail as $item)
                    <tr>
                        <td>{{ $item->jumlah }}</td>
                        <td>{{ $item->produk->nama }}</td>
                        <td>{{ $item->produk->harga_jual * $item->jumlah }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <!-- <tfoot>
                    <tr>
                        <td colspan="2">sad</td>
                    </tr>
                </tfoot> -->
            </table>
        </div>

        <!-- <table class="table table-borderless table-sm">
            <tr>
                <td style="width: 150px">Kasir:</td>
                <td>{{ $transaksi->user->name }}</td>
            </tr>
            <tr>
                <td style="width: 150px">Subtotal:</td>
                <td>{{ $transaksi->transaksiDetail->sum('jumlah') }}</td>
            </tr>
            <tr>
                <td style="width: 150px">Harga Total:</td>
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
        </table> -->

        <p>
            Kasir: {{ $transaksi->user->name }}<br>
            Subtotal: {{ $transaksi->transaksiDetail->sum('jumlah') }}<br>
            Harga Total:
            @php
            $hargaTotal = 0;
            foreach ($transaksi->transaksiDetail as $item) {
            $hargaTotal += $item->jumlah * $item->produk->harga_jual;
            }
            @endphp
            {{ $hargaTotal }}
        </p>

    </div>
    <div class="card-footer">
        <button type="button" class="btn btn-primary"><i class="fas fa-money-bill"></i> Bayar</button>
        <button type="button" class="btn btn-secondary"><i class="fas fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Batal</button>
    </div>
</div>