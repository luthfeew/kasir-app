@extends('layouts.app', ['title' => 'Tutup Kasir'])

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Default box -->
        <div class="card">
            <div class="card-header">

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="accordion">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h4 class="card-title w-100">
                                <a class="d-block w-100 text-center" data-toggle="collapse" href="#collapseOne">
                                    <i class="nav-icon fas fa-door-closed"></i> Tutup Kasir
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="collapse" data-parent="#accordion">
                            <form method="post" action="{{ route('tutup_kasir.store') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="saldo_akhir">Saldo Akhir</label>
                                        <input name="saldo_akhir" type="number" class="form-control form-control-lg" id="saldo_akhir" placeholder="Masukkan Saldo Akhir" required>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-warning">Tutup</button>
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection