@extends('layouts.app', ['title' => 'Laporan | Tutup Kasir'])

@section('css')
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css')}}">
@endsection

@section('js')
<!-- InputMask -->
<script src="{{ asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js')}}"></script>

<script>
    $(function() {
        //Date range picker
        $('#reservation').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY'
            }
        });
        $('#reservation').on('apply.daterangepicker', function(ev, picker) {
            // console.log(picker.startDate.format('YYYY-MM-DD'));
            // console.log(picker.endDate.format('YYYY-MM-DD'));

            // emit to listener setStartDate and setEndDate
            Livewire.emit('getTanggal', picker.startDate.format('YYYY-MM-DD') + ' ~ ' + picker.endDate.format('YYYY-MM-DD'));
        });
    })
</script>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card card-primary card-outline">
            <!-- <div class="card-header">
                <h3 class="card-title">Produk Terlaris</h3>
            </div> -->
            <div class="card-body">

                <livewire:tutup-kasir />

            </div>
        </div>

    </div>
</div>
@endsection