<div>
    <div class="input-group">
        <input wire:model="cari" id="myInput" type="search" class="form-control form-control-lg" placeholder="Input Nama/Nomor SKU">
        <div class="input-group-append">
            <button type="button" class="btn btn-lg btn-default">
                <i class="fa fa-search"></i>
            </button>
        </div>
    </div>

    <table class="table table-sm">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($produks as $produk)
            <tr>
                <td>{{ $produk->sku }}</td>
                <td>{{ $produk->nama }}</td>
                <td>{{ $produk->harga_jual }}</td>
                <td>{{ $produk->inventaris()->sum('stok') }}</td>
                <td><x-button wire:click="tambah({{ $produk->id }})" type="button" class="btn-xs"><i class="fa fa-plus"></i></x-button></td>
            </tr>
            @empty
            <tr>
                <td colspan="4">-</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('js')
<script>
    // trigger function tambahProduk() when myInput is entered
    document.getElementById("myInput").addEventListener("keyup", function(event) {
        if (event.keyCode === 13) {
            // livewire emit tambahProduk with input value
            Livewire.emit('enter', this.value);
            this.value = '';
            // Livewire.emit('enter');
            // clear myinput value
        }
    });
</script>
@endpush