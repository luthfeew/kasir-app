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
                <x-button onclick="copyTotal()" data-toggle="modal" data-target="#modal-bayar">Bayar</x-button>
                <x-button color="secondary" data-toggle="modal" data-target="#modal-bayar">Simpan</x-button>
                <x-button color="danger" data-toggle="modal" data-target="#modal-bayar">Hapus</x-button>
            </x-slot>

            <div class="modal fade" id="modal-bayar">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Bayar</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('kasir.bayar', $id) }}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="col-8">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th style="width:50%">Total Tagihan:</th>
                                                    <td>
                                                        <h3><span id="tagihanView"></span></h3>
                                                        <input type="hidden" name="tagihan" id="tagihan">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Bayar:</th>
                                                    <td>
                                                        <div class="d-flex">
                                                            <div class="mr-2">
                                                                <h3>Rp</h3>
                                                            </div>
                                                            <div>
                                                                <input type="text" onkeyup="hitungKembalian()" name="" id="bayarView" class="form-control form-control-lg">
                                                                <input type="hidden" name="bayar" id="bayar">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Kembalian:</th>
                                                    <td>
                                                        <h3>Rp <span id="kembalianView"></span></h3>
                                                        <input type="hidden" name="kembalian" id="kembalian">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Hutang:</th>
                                                    <td>
                                                        <h3>Rp <span id="hutangView"></span></h3>
                                                        <input type="hidden" name="hutang" id="hutang">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-primary">Bayar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </x-card>
    </div>
</div>
<div class="row">
    <div class="col-xl">
        <x-card title="Transaksi Pending">

            @env('local')
            {{ $transaksi_pending }}
            @endenv
            <!-- <x-data-tables :kolomTabel="['No', 'Nama', 'Tanggal', 'Total Harga', 'Aksi']">
            @forelse ($transaksi_pending as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    {{ $item->pelanggan->nama ?? $item->nama_pembeli }}
                </td>
                <td>{{ $item->updated_at }}</td>
                <td>@rupiah($item->total_harga)</td>
                <td>
                    <a href="{{ route('kasir', $item->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data</td>
            </tr>
            @endforelse
        </x-data-tables> -->
            <div class="table-responsive">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>No Transaksi</th>
                            <th>Nama Pelanggan</th>
                            <th>Waktu</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksi_pending as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $item->pelanggan->nama ?? $item->nama_pembeli }}
                            </td>
                            <td>{{ $item->updated_at }}</td>
                            <td>@rupiah($item->total_harga)</td>
                            <td>
                                <a href="{{ route('kasir', $item->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


        </x-card>
    </div>
    <div class="col-xl">
        <x-card title="Bayar Hutang">

            @env('local')
            {{ $transaksi_hutang }}
            @endenv

            <!-- <x-data-tables :kolomTabel="['No', 'Nama', 'Tanggal', 'Total Hutang', 'Aksi']">
                @forelse ($transaksi_hutang as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        {{ $item->pelanggan->nama ?? $item->nama_pembeli }}
                    </td>
                    <td>{{ $item->updated_at }}</td>
                    <td>@rupiah($item->bayar->hutang)</td>
                    <td>
                        <a href="{{ route('kasir', $item->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </x-data-tables> -->

            <div class="table-responsive">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>No Transaksi</th>
                            <th>Nama Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total Hutang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksi_hutang as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                {{ $item->pelanggan->nama ?? $item->nama_pembeli }}
                            </td>
                            <td>{{ $item->updated_at }}</td>
                            <td>@rupiah($item->bayar->hutang)</td>
                            <td>
                                <a href="{{ route('kasir', $item->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>


        </x-card>
    </div>
</div>
@endsection

@push('js')
<script>
    function showButton(index) {
        document.getElementById('button-' + index).classList.remove('d-none');
    }

    function showBtnPembeli() {
        document.getElementById('btn-pembeli').classList.remove('d-none');
    }

    function onlyNumber(value) {
        return value.replace(/\D/g, '');
    }

    function copyTotal() {
        var tagihan = document.getElementById('total').innerHTML;
        document.getElementById('tagihan').value = onlyNumber(tagihan);
        document.getElementById('tagihanView').innerHTML = tagihan;
    }

    var bayarView = document.getElementById('bayarView');
    bayarView.addEventListener('keyup', function(e) {
        // tambahkan 'Rp.' pada saat form di ketik
        // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
        bayarView.value = formatRupiah(this.value, '');
    });

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
    }

    function hitungKembalian() {
        document.getElementById('bayar').value = onlyNumber(bayarView.value);
        var tagihan = document.getElementById('tagihan').value;
        var bayar = document.getElementById('bayar').value;
        var kembalian = bayar - tagihan;
        // alert(kembalian);
        if (kembalian < 0) {
            document.getElementById('kembalian').value = 0;
            document.getElementById('kembalianView').innerHTML = 0;
            document.getElementById('hutang').value = Math.abs(kembalian);
            document.getElementById('hutangView').innerHTML = Math.abs(kembalian).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        } else {
            document.getElementById('kembalian').value = kembalian;
            document.getElementById('kembalianView').innerHTML = kembalian.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            document.getElementById('hutang').value = 0;
            document.getElementById('hutangView').innerHTML = 0;
        }
    }

    // function hitungKembalian() {
    //     var tagihan = document.getElementById('tagihan').value;
    //     var bayar = document.getElementById('bayar').value;
    //     var kembalian = bayar - tagihan;
    //     // document.getElementById('kembalian').innerHTML = kembalian;
    //     // if kembalian < 0, maka hutang = kembalian
    //     if (kembalian < 0) {
    //         document.getElementById('kembalian').innerHTML = 0;
    //         document.getElementById('hutang').innerHTML = Math.abs(kembalian);
    //     } else {
    //         document.getElementById('kembalian').innerHTML = kembalian;
    //         document.getElementById('hutang').innerHTML = 0;
    //     }
    // }
</script>
@endpush