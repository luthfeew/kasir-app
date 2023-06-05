@extends('layouts.app', ['title' => 'Buka Kasir'])

@section('content')
<div class="row">

    <div class="col-sm">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Buka Kasir</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <form method="post" action="{{ route('buka_kasir.store') }}">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="saldo_awal">Saldo Awal</label>
                        <input name="saldo_awal" type="number" class="form-control form-control-lg" id="saldo_awal" placeholder="Masukkan Saldo Awal" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Buka</button>
                    <button type="reset" class="btn btn-secondary">Reset</button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection