@extends('layouts.app', ['title' => 'Kasir'])

@section('content')
<div class="row">

    <div class="col-sm">
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

                <livewire:search-produk />

            </div>
        </div>
    </div>

    <div class="col-sm">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Rincian Pesanan</h3>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <livewire:kasir />
            
        </div>
    </div>
</div>
@endsection