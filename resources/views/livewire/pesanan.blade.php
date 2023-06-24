<div>
    {{-- $transaksi_id --}}

    <div class="row">
        <div class="col-md">
            <b>No Transaksi: #{{ $transaksi->kode }}</b>
            <br>
            <b>Kasir:</b> {{ $transaksi->user->nama }}<br>

            <div class="d-flex align-items-center">
                <div>
                    <b>Pelanggan: </b>
                </div>
                <div class="ml-2">
                    <select wire:model="pelanggan_id" wire:change="updatePelanggan()" class="form-control">
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
                <div>
                    @json($pelanggan_id)
                </div>
            </div>

            <table class="table table-sm mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>QTY</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksi->transaksi_detail as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->produk->nama }}</td>
                        <td>{{ $item->jumlah_beli }}</td>
                        <td>{{ $item->produk->harga_jual * $item->jumlah_beli }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">-</td>
                    </tr>
                    @endforelse
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
@endpush

@push('js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script>
    $(document).ready(function() {
        $('#select2-dropdown').select2({
            theme: 'bootstrap4',
            // placeholder: "Pilih Pelanggan",
        });
        // $(document).on('change', '#select2-dropdown', function(e) {
        //     var data = $('#select2-dropdown').select2("val");
        //     @this.set('pelanggan_id', data);
        //     Livewire.emit('updatePelanggan');
        // });
    });
</script>
@endpush