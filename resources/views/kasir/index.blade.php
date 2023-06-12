@extends('layouts.app', ['title' => 'Kasir'])

@section('js')
<script>
    // on load
    document.addEventListener('DOMContentLoaded', function() {
        // jika tidak ada id atau hargaTotal1 = 0 maka tambahankan class d-none pada tombol simpan dan hapus
        if (!'{{ $id }}' && document.getElementById('hargaTotal1').innerHTML == 0) {
            document.getElementById('aksi').classList.add('d-none');
        }
    });

    // buat listener jika hargatotal1 tidak sama dengan 0 maka tampilkan tombol bayar
    var hargaTotal1 = document.getElementById('hargaTotal1');
    hargaTotal1.addEventListener('DOMSubtreeModified', function() {
        if (hargaTotal1.innerHTML != 0) {
            document.getElementById('aksi').classList.remove('d-none');
        } else {
            document.getElementById('aksi').classList.add('d-none');
        }
    });

    function showButton(index) {
        document.getElementById('button-' + index).classList.remove('d-none');
    }

    function copyHargaTotal() {
        var hargaTotal = document.getElementById('hargaTotal1').innerHTML;

        // buat format rupiah
        var reverse = hargaTotal.toString().split('').reverse().join(''),
            ribuan = reverse.match(/\d{1,3}/g);
        ribuan = ribuan.join('.').split('').reverse().join('');
        document.getElementById('hargaTotal2').innerHTML = ribuan;

        // copy value hargaTotal1 ke hargaTotal3
        document.getElementById('hargaTotal3').value = hargaTotal;
    }

    function hitungKembalian() {
        var hargaTotal = document.getElementById('hargaTotal1').innerHTML;
        var bayar = document.getElementById('bayar').value;

        // convert hargaTotal dan bayar ke number
        hargaTotal = parseInt(hargaTotal.replace(/\./g, ''));
        bayar = parseInt(bayar.replace(/\./g, ''));

        // jika bayar lebih dari sama dengan harga total maka tampilkan tombol bayar dan hitung kembalian
        if (bayar >= hargaTotal) {
            document.getElementById('button-bayar').disabled = false;

            var kembalian = bayar - hargaTotal;

            // buat format rupiah
            var reverse = kembalian.toString().split('').reverse().join(''),
                ribuan = reverse.match(/\d{1,3}/g);
            ribuan = ribuan.join('.').split('').reverse().join('');
            document.getElementById('kembalian').innerHTML = ribuan;
        } else {
            document.getElementById('button-bayar').disabled = true;
        }
    }
</script>
@endsection

@section('content')
<div class="row">

    <div class="col-xl">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Cari produk</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">

                <livewire:cari-produk />

            </div>
        </div>
    </div>

    <div class="col-xl">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Rincian Pesanan</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">

                <livewire:pesanan :transaksi_id="$id">

            </div>
            <div id="aksi" class="card-footer">
                <button onclick="copyHargaTotal()" type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-xl">
                    <i class="far fa-credit-card"></i> Bayar
                </button>

                <a class="btn btn-primary" href="{{ route('kasir.simpan', $id) }}" onclick="event.preventDefault(); document.getElementById('simpan-form').submit();">
                    <i class="fas fa-download"></i> Simpan
                </a>
                <form id="simpan-form" action="{{ route('kasir.simpan', $id) }}" method="POST" style="display: none;">
                    @csrf
                </form>

                <a class="btn btn-danger" href="{{ route('kasir.hapus', $id) }}" onclick="event.preventDefault(); document.getElementById('hapus-form').submit();">
                    <i class="fas fa-trash"></i> Hapus
                </a>
                <form id="hapus-form" action="{{ route('kasir.hapus', $id) }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-xl">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Bayar</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3>
                        <small class="text-muted">Total:</small>
                        Rp <span id="hargaTotal2">0</span>
                    </h3>
                    <h3>
                        <small class="text-muted">Bayar:</small>
                        Rp <input type="number" id="bayar" onkeyup="hitungKembalian()">
                    </h3>
                    <h3>
                        <small class="text-muted">Kembalian:</small>
                        Rp <span id="kembalian">0</span>
                    </h3>
                </div>
                <div class="modal-footer justify-content-between">
                    <form action="{{ route('kasir.bayar', $id) }}" method="post">
                        @csrf
                        <input type="number" name="harga_total" id="hargaTotal3">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                        <button disabled id="button-bayar" type="submit" class="btn btn-success"><i class="far fa-credit-card"></i> Bayar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection