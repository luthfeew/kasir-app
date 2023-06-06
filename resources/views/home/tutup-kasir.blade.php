@extends('layouts.app', ['title' => 'Tutup Kasir'])

@section('content')
<div class="row">

    <div class="col-sm">
        <div class="card card-primary">
            <!-- <div class="card-header">
                <h3 class="card-title">Tutup Kasir</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div> -->

            <form method="post" action="{{ route('tutup_kasir.store') }}">
                @csrf
                <div class="card-body card-primary card-outline">
                    <div class="form-group">
                        <label for="saldo_akhir">Saldo Akhir</label>
                        <input name="saldo_akhir" type="number" class="form-control form-control-lg" id="saldo_akhir" placeholder="Masukkan Saldo Akhir" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Tutup</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection