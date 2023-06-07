<div>
    <div class="input-group">
        <input wire:model="search" type="text" class="form-control form-control-lg" placeholder="Masukkan nomor SKU atau nama produk">
        <div class="input-group-append">
            <button type="submit" class="btn btn-lg btn-default">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produks as $produk)
                <tr>
                    <td>{{ $produk->sku }}</td>
                    <td>{{ $produk->nama }}</td>
                    <td>{{ $produk->harga_jual }}</td>
                    <td>{{ $produk->stok }}</td>
                    <td>
                        <button wire:click="tambahProduk({{ $produk->sku }})" class="btn btn-sm btn-primary">
                            <i class="fa fa-plus"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>