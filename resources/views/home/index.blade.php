@extends('layouts.app', ['title' => 'Beranda'])

@section('content')
<div class="row">

    <div class="col-sm">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Status Kasir</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body">

                @if ($sesi)
                <div class="alert alert-success">
                    <h5><i class="icon fas fa-check"></i> Aktif</h5>
                    Waktu mulai: {{ $sesi->waktu_mulai->format('d-m-Y H:i:s') }} <br>
                    Saldo awal: Rp {{ number_format($sesi->saldo_awal, 0, ',', '.') }}
                </div>
                @else
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Tidak Aktif</h5>
                    Kasir tidak dapat melakukan transaksi. Apabila ingin melakukan transaksi, silakan buka kasir terlebih dahulu.
                </div>
                @endif

            </div>
        </div>
    </div>

</div>
@endsection