@extends('layouts.app', ['title' => 'Kasir'])

@section('content')
<div class="row">
    <div class="col-xl-4">
        <x-card title="Cari Produk">
            <livewire:cari-produk />
        </x-card>
    </div>
    <div class="col-xl-8">
        <x-card title="Rincian Pesanan">
            <livewire:pesanan :transaksiId="$transaksiId" />

            <x-slot name="footer">
                <x-button onclick="copyTotal()" data-toggle="modal" data-target="#modal-bayar">Bayar</x-button>
                <x-button color="secondary" data-toggle="modal" data-target="#modal-simpan">Simpan</x-button>
                <x-button color="danger" data-toggle="modal" data-target="#modal-hapus">Hapus</x-button>
            </x-slot>
        </x-card>
    </div>
</div>

<div class="row">
    <div class="col-xl-6">
        <x-card title="Transaksi Pending">
            @env('local')
            {{ $transaksiPending }}
            @endenv

            <table class="table">
                <thead>
                    <tr>
                        <th>No Transaksi</th>
                        <th>Nama Pembeli</th>
                        <th>Tanggal</th>
                        <th>Total Tagihan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksiPending as $item)
                    <tr>
                        <td>{{ $item->kode }}</td>
                        <td>{{ $item->pelanggan->nama ?? $item->nama_pembeli }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>@rupiah($item->transaksi_detail->sum('harga_total'))</td>
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
        </x-card>
    </div>
    <div class="col-xl-6">
        <x-card title="Transaksi Hutang">
            @env('local')
            {{ $transaksiHutang }}
            @endenv

            <table class="table">
                <thead>
                    <tr>
                        <th>No Transaksi</th>
                        <th>Nama Pembeli</th>
                        <th>Tanggal</th>
                        <th>Total Hutang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksiHutang as $item)
                    <tr>
                        <td>{{ $item->kode }}</td>
                        <td>{{ $item->pelanggan->nama ?? $item->nama_pembeli }}</td>
                        <td>{{ $item->updated_at }}</td>
                        <td>@rupiah($item->bayar->hutang)</td>
                        <td>
                            <a href="{{ route('kasir.bayar_hutang', $item->id) }}" class="btn btn-sm btn-primary">
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

        </x-card>
    </div>
</div>

<!-- MODAL BAYAR -->
<div class="modal fade" id="modal-bayar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Bayar Pesanan</h4>
            </div>
            <form action="{{ route('kasir.bayar', $transaksiId) }}" method="post">
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

<!-- MODAL SIMPAN -->
<div class="modal fade" id="modal-simpan">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Simpan Pesanan</h4>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin menyimpan pesanan ini?</p>
            </div>
            <form action="{{ route('kasir.simpan', $transaksiId) }}" method="post">
                @csrf
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-primary">Ya</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL HAPUS -->
<div class="modal fade" id="modal-hapus">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Hapus Pesanan</h4>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin menghapus pesanan ini?</p>
            </div>
            <form action="{{ route('kasir.hapus', $transaksiId) }}" method="post">
                @csrf
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-danger">Ya</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
    function onlyNumber(value) {
        return value.replace(/\D/g, '');
    }

    function copyTotal() {
        var hutangSebelumnya = document.getElementById('hutangSebelumnya');
        var tagihan = document.getElementById('total').innerHTML;
        // jika ada elemen hutangSebelumnya maka tambahkan hutangSebelumnya ke tagihan
        if (hutangSebelumnya != null) {
            tagihan = parseInt(onlyNumber(tagihan)) + parseInt(hutangSebelumnya.value);
            document.getElementById('tagihan').value = tagihan;
            document.getElementById('tagihanView').innerHTML = "Rp " + tagihan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        } else {
            document.getElementById('tagihan').value = onlyNumber(tagihan);
            document.getElementById('tagihanView').innerHTML = tagihan;
        }

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
</script>
@endpush