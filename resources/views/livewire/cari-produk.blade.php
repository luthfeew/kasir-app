<div>
    <div class="input-group">
        <input wire:model="cari" type="text" class="form-control form-control-lg" placeholder="Masukkan nomor SKU atau nama produk">
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
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($produks as $produk)
                <tr>
                    <td class="align-middle">{{ $produk->sku }}</td>
                    <td class="align-middle">{{ $produk->nama }}</td>
                    <td class="align-middle">{{ $produk->harga_jual }}</td>
                    <td class="align-middle">{{ $produk->stok }}</td>
                    <td class="align-middle">
                        <button wire:click="tambahProduk({{ $produk->id }})" class="btn btn-sm btn-primary">
                            <i class="fa fa-plus"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada produk yang ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>