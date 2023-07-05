<div>
    {{-- $transaksi_id --}}

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
                    <select wire:model="pelanggan_id" wire:change="updatePelanggan()" class="form-control form-control-sm">
                        <option value="">-</option>
                        @foreach ($daftarPelanggan as $key => $value)
                        @if ($key == $pelanggan_id)
                        <option value="{{ $key }}" selected>{{ $value }}</option>
                        @else
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>

            @env('local')
            {{ $transaksiDetail }}
            @endenv
            
            @if ($transaksi->is_melunasi)
            <br>
            <span class="bg-warning"><b>Hutang: @rupiah($transaksi->parent->bayar->hutang)</b></span><br>
            <input type="number" id="hutangSebelumnya" value="{{ $transaksi->parent->bayar->hutang }}">
            @endif
            
            <table class="table table-sm mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <!-- <th>Harga Satuan</th> -->
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
                        <!-- <td>{{ $item->produk->harga_jual }}</td> -->
                        <td>
                            @if ($item->produk->harga_jual != $item->harga_satuan)
                            <del>@rupiah($item->produk->harga_jual)</del>
                            @endif
                            @rupiah($item->harga_satuan)
                        </td>
                        <!-- <td>
                            {{ $item->jumlah_beli }}
                            <input type="number" wire:model="jumlah_beli.{{ $item->produk_id }}" class="form-control form-control-sm">
                            <div class="btn-group">
                                <button wire:click="tambahProduk({{ $item->produk_id }})" class="btn btn-sm btn-success"><i class="fas fa-plus"></i></button>
                                <button wire:click="kurangProduk({{ $item->produk_id }})" class="btn btn-sm btn-warning"><i class="fas fa-minus"></i></button>
                                <button wire:click="hapusProduk({{ $item->produk_id }})" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                            </div>
                        </td> -->
                        <td>
                            {{-- $item->jumlah_beli --}}
                            <div class="d-flex justify-content-start align-items-center">
                                <div>
                                    <form wire:submit.prevent="updateQty({{$item->id}})" class="form-inline">
                                        <input wire:model.defer="jumlah_beli.{{$item->id}}" oninput="showButton(<?php echo $loop->index; ?>)" min="1" type="number" class="form-control form-control-sm w-50 mr-1 @error('jumlah_beli.' . $item->id) is-invalid @enderror">
                                        <button id="button-{{ $loop->index }}" class="d-none btn btn-sm btn-primary" type="submit">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                </div>
                                <!-- <div>Stok: {{ $item->produk->inventaris()->sum('stok') }}</div> -->
                                <div class="btn-group">
                                    @if ($item->jumlah_beli != $item->produk->inventaris()->sum('stok'))
                                    <button wire:click="tambahProduk({{ $item->produk_id }})" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i></button>
                                    @endif
                                    @if ($item->jumlah_beli > 1)
                                    <button wire:click="kurangProduk({{ $item->produk_id }})" class="btn btn-sm btn-warning"><i class="fas fa-minus"></i></button>
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

<!-- @push('css')
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endpush

@push('js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    // $(document).ready(function() {
    //     $('#select2-dropdown').select2({
    //         theme: 'bootstrap4',
    //         // placeholder: "Pilih Pelanggan",
    //     });
    //     // $(document).on('change', '#select2-dropdown', function(e) {
    //     //     var data = $('#select2-dropdown').select2("val");
    //     //     @this.set('pelanggan_id', data);
    //     //     Livewire.emit('updatePelanggan');
    //     // });
    // });
</script>
@endpush -->