<div>
    <div class="card-body">

        <p>
            No Transaksi: {{ $transaksi->id ?? '' }} <br>
            Kasir: {{ $user_id }} <br>
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
                @if ($transaksi)
                <tbody>
                    @foreach ($transaksi->transaksiDetail as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->produk->nama }}</td>
                        <td>
                            <form>
                                <div class="d-flex">
                                    <div class="mr-2"><input type="number" class="form-control form-control-sm" wire:model.lazy="qty.{{$item->id}}" min="1"></div>
                                    <div><button type="button" class="btn btn-sm btn-primary" wire:click="update({{ $item->id }})"><i class="fas fa-check"></i></button></div>
                                    <!-- @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif -->
                                    @error('qty.' . $item->id)
                                    <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </form>
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
                @endif
            </table>
        </div>

    </div>
    <div class="card-footer">
        <button type="button" class="btn btn-primary"><i class="fas fa-money-bill"></i> Bayar</button>
        <button type="button" class="btn btn-secondary"><i class="fas fa-save"></i> Simpan</button>
        <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i> Batal</button>
    </div>
</div>