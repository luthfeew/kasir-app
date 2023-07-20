<div>
    @env('local')
    {{ $transaksiId }}
    {{ $pelangganId }}
    {{ $namaPembeli }}
    {{ $transaksiDetail }}
    @endenv

    <div class="row">
        <div class="col-md">

            <b>No Transaksi: #{{ $transaksi->kode }}</b>
            <br>
            <b>Kasir:</b> {{ $transaksi->user->nama }}<br>

            @if (!$transaksi->pelanggan_id)
            <form wire:submit.prevent="updateNamaPembeli()" class="form-inline">
                <label>Nama Pembeli: </label>
                <input wire:model.defer="namaPembeli" oninput="showBtnPembeli()" type="text" class="form-control form-control-sm ml-2 mr-1">

                <button id="btn-pembeli" type="submit" class="d-none btn btn-sm btn-primary">
                    <i class="fas fa-check"></i>
                </button>
            </form>
            @endif

            <div class="d-flex align-items-center">
                <div>
                    <b>Nama Pelanggan: </b>
                </div>
                <div class="ml-2">
                    <select wire:model="pelangganId" wire:change="updatePelanggan()" class="form-control form-control-sm">
                        <option value="">-</option>
                        @foreach ($daftarPelanggan as $key => $value)
                        @if ($key == $pelangganId)
                        <option value="{{ $key }}" selected>{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- if this transaksi parent_id has is_hutang = true -->
            @env('local')
            {{ optional($transaksi->parent)->is_hutang }}
            @endenv
            @if (optional($transaksi->parent)->is_hutang)
            <br>
            <span class="bg-warning"><b>Hutang: @rupiah($transaksi->parent->bayar->hutang)</b></span><br>
            <input hidden type="number" id="hutangSebelumnya" value="{{ $transaksi->parent->bayar->hutang }}">
            @endif


            <table class="table table-sm mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Satuan</th>
                        <th>Harga Satuan</th>
                        <th>QTY</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksiDetail as $item)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>{{ $item->produk->nama }}</td>
                        <td>{{ $item->produk->satuan }}</td>
                        <td>
                            @if ($item->produk->harga_jual != $item->harga_satuan)
                            <del>@rupiah($item->produk->harga_jual)</del>
                            @endif
                            @rupiah($item->harga_satuan)
                        </td>
                        <td>
                            {{-- $item->jumlah_beli --}}
                            <div class="d-flex justify-content-start align-items-center">
                                <div>
                                    <form wire:submit.prevent="updateQty({{$item->id}})" class="form-inline">
                                        <input wire:model.defer="jumlahBeli.{{$item->id}}" oninput="showButton(<?php echo $loop->index; ?>)" min="1" type="number" class="form-control form-control-sm w-50 mr-1 @error('jumlahBeli.' . $item->id) is-invalid @enderror">
                                        <button id="button-{{ $loop->index }}" class="d-none btn btn-sm btn-primary" type="submit">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                </div>
                                <div class="btn-group">
                                    @if ($item->jumlah_beli != $item->produk->inventaris()->sum('stok'))
                                    <button wire:click="tambahQty({{ $item->produk_id }})" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i></button>
                                    @endif
                                    @if ($item->jumlah_beli > 1)
                                    <button wire:click="kurangQty({{ $item->produk_id }})" class="btn btn-sm btn-warning"><i class="fas fa-minus"></i></button>
                                    @endif
                                    <button wire:click="hapusProduk({{ $item->produk_id }})" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </td>
                        <td class="text-right">@rupiah($item->harga_total)</td>
                    </tr>
                    @empty
                    <tr>
                        <td>-</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="text-right">
                        <td colspan="4"><b>Total</b></td>
                        <td><b><span id="total">@rupiah($transaksiDetail->sum('harga_total'))</span></b></td>
                    </tr>
                </tfoot>
            </table>

        </div>
    </div>
</div>

@push('js')
<script>
    function showButton(index) {
        document.getElementById('button-' + index).classList.remove('d-none');
    }

    function showBtnPembeli() {
        document.getElementById('btn-pembeli').classList.remove('d-none');
    }
</script>
@endpush