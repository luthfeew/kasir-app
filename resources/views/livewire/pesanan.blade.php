<div>
    {{-- $transaksi_id --}}

    <div class="row">
        <div class="col-md">
            <b>No Transaksi: #{{ $transaksi->kode }}</b>
            <br>
            <b>Kasir:</b> {{ $transaksi->user->nama }}<br>

            
        </div>
    </div>
</div>