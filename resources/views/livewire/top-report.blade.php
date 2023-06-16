<div>
    <div class="row">
        <div class="col-6">
            <!-- Date range -->
            <div class="form-group">
                <label>Rentang waktu:</label>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                    </div>
                    <input wire:model="tanggal" type="text" class="form-control float-right" id="reservation">
                </div>
                <!-- /.input group -->
            </div>
            <!-- /.form group -->
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Produk Terjual</th>
                    <th>Harga Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->produk->nama}}</td>
                    <td>{{$item->total_qty}}</td>
                    <td>Rp. {{ number_format(($item->total_qty * $item->produk->harga_jual), 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>