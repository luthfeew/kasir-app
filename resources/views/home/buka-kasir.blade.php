@extends('layouts.app', ['title' => 'Buka Kasir'])

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
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4 class="card-title w-100">
                                <a class="d-block w-100 text-center" data-toggle="collapse" href="#collapseOne">
                                    <i class="nav-icon fas fa-door-open"></i> Buka Kasir
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="collapse" data-parent="#accordion">
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
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>
@endsection