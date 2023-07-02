@extends('layouts.app', ['title' => 'Kasir'])

@section('content')
<div class="row">
    <div class="col-xl-4">
        <x-card title="Cari Produk">
            <livewire:cari-produk />
        </x-card>
    </div>
    <div class="col-xl">
        <x-card title="Rincian Pesanan">
            <livewire:pesanan :transaksi_id="$id" />

            <x-slot name="footer">
                <x-button data-toggle="modal" data-target="#modal-bayar">Bayar</x-button>
                <x-button color="secondary" data-toggle="modal" data-target="#modal-bayar">Simpan</x-button>
                <x-button color="danger" data-toggle="modal" data-target="#modal-bayar">Hapus</x-button>
            </x-slot>

            <div class="modal fade" id="modal-bayar">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">BAYAR</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>One fine body&hellip;</p>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                            <button type="button" class="btn btn-primary">Bayar</button>
                        </div>
                    </div>
                </div>
            </div>

        </x-card>
    </div>
</div>
<div class="row">
    <div class="col-xl">
        <x-card title="Transaksi Pending">
        </x-card>
    </div>
</div>
@endsection

@push('js')
<script>
    function showButton(index) {
        document.getElementById('button-' + index).classList.remove('d-none');
    }
</script>
@endpush