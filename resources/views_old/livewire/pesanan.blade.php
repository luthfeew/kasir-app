<div>
    {{-- $transaksi_id --}}
    {{-- $transaksiDetail --}}

    <div class="row">
        <div class="col">
            <b>No Transaksi: #{{ $transaksi_id }}</b><br>
            <br>
            <b>Kasir:</b> {{ $kasir }}<br>
            <b>Waktu:</b> <span wire:ignore id="time"></span><br>

            <form wire:submit.prevent="updateNamaPelanggan()" class="form-inline">
                <label for="inlineFormInputName2">Nama Pelanggan: </label>
                <input wire:model.defer="namaPelanggan" oninput="showBtnPelanggan()" type="text" class="form-control form-control-sm ml-2" id="inlineFormInputName2">

                <button id="btn-pelanggan" type="submit" class="d-none btn btn-sm btn-primary ml-2">
                    <i class="fas fa-check"></i>
                </button>
            </form>

        </div>
    </div>

    <div class="mt-3 table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>QTY</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksiDetail as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->produk->nama }}</td>
                    <td>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-inline">
                                <form wire:submit.prevent="updateQty({{$item->id}})" class="form-inline">
                                    <input hidden type="number" wire:model.defer="qty.{{$item->produk_id}}">
                                    <input wire:model.defer="qty.{{$item->id}}" oninput="showButton(<?php echo $loop->index; ?>)" min="1" type="number" class="form-control form-control-sm mr-1 @error('qty.' . $item->id) is-invalid @enderror">

                                    <button id="button-{{ $loop->index }}" class="d-none btn btn-sm btn-primary" type="submit">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            </div>
                            <div>Stok: {{ $item->produk->stok }}</div>
                            <div>
                                <button wire:click="hapusProduk({{ $item->produk_id }})" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                <button wire:click="kurangQty({{ $item->produk_id }})" class="btn btn-sm btn-warning"><i class="fas fa-minus"></i></button>
                                @if ($item->jumlah !== $item->produk->stok)
                                <button wire:click="tambahQty({{ $item->produk_id }})" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i></button>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>{{ $item->produk->harga_jual * $item->jumlah }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-right" colspan="3">Total:</th>
                    <td id="hargaTotal1">{{ $hargaTotal }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>