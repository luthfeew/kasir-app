<div>
    @env('local')
    {{ $tanggalAwal }}
    {{ $tanggalAkhir }}
    {{ $rentang }}
    @endenv

    <div class="form-group">
        <label>Rentang Waktu:</label>

        <div id="reportrange" class="input-group" wire:ignore>
            <button type="button" class="btn btn-default float-right" id="daterange-btn">
                <i class="far fa-calendar-alt"></i> <span>Hari Ini</span>
                <i class="fas fa-caret-down"></i>
            </button>
        </div>
    </div>

    <div class="row">
        <x-widget bgColor="bg-info" icon="fas fa-dollar-sign" text="Total Penjualan" number="41" />
        <x-widget bgColor="bg-success" icon="fas fa-coins" text="Laba Kotor" number="41" />
        <x-widget bgColor="bg-success" icon="fas fa-hand-holding-usd" text="Terima Pembayaran" number="41" />
    </div>
    <div class="row">
        <x-widget bgColor="bg-danger" icon="fas fa-hand-holding-usd" text="Refund" number="41" />
        <x-widget bgColor="bg-warning" icon="fas fa-hand-holding-usd" text="Hutang" number="41" />
    </div>
    <div class="row">
        <x-widget bgColor="bg-info" icon="fas fa-money-check" text="Rata-Rata Transaksi" number="41" />
        <x-widget bgColor="bg-info" icon="fas fa-list" text="Total Transaksi" number="41" />
        <x-widget bgColor="bg-info" icon="fas fa-boxes" text="Total Produk" number="41" />
    </div>

    <div class="table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>183</td>
                    <td>John Doe</td>
                    <td>11-7-2014</td>
                    <td><span class="tag tag-success">Approved</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                </tr>
                <tr>
                    <td>219</td>
                    <td>Alexander Pierce</td>
                    <td>11-7-2014</td>
                    <td><span class="tag tag-warning">Pending</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                </tr>
                <tr>
                    <td>657</td>
                    <td>Bob Doe</td>
                    <td>11-7-2014</td>
                    <td><span class="tag tag-primary">Approved</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                </tr>
                <tr>
                    <td>175</td>
                    <td>Mike Doe</td>
                    <td>11-7-2014</td>
                    <td><span class="tag tag-danger">Denied</span></td>
                    <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@push('css')
<link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
@endpush

@push('js')
<!-- InputMask -->
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<!-- date-range-picker -->
<script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

<script>
    $('#daterange-btn').daterangepicker({
        ranges: {
            'Hari Ini': [moment(), moment()],
            'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
            '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
            'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
            'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment(),
        endDate: moment(),
        maxDate: moment(),
        locale: {
            format: 'DD/MM/YYYY',
            customRangeLabel: 'Pilih Rentang Waktu',
            applyLabel: "Pilih",
            cancelLabel: "Batal",
            daysOfWeek: [
                "Min",
                "Sen",
                "Sel",
                "Rab",
                "Kam",
                "Jum",
                "Sab"
            ],
            monthNames: [
                "Januari",
                "Februari",
                "Maret",
                "April",
                "Mei",
                "Juni",
                "Juli",
                "Agustus",
                "September",
                "Oktober",
                "November",
                "Desember"
            ],
        }
    });
    $('#daterange-btn').on('apply.daterangepicker', function(ev, picker) {
        var range;
        switch (picker.startDate.format('DD/MM/YYYY')) {
            case moment().format('DD/MM/YYYY'):
                $('#reportrange span').html('Hari Ini');
                range = 1;
                break;
            case moment().subtract(1, 'days').format('DD/MM/YYYY'):
                $('#reportrange span').html('Kemarin');
                range = 2;
                break;
            case moment().subtract(6, 'days').format('DD/MM/YYYY'):
                $('#reportrange span').html('7 Hari Terakhir');
                range = 3;
                break;
            case moment().subtract(29, 'days').format('DD/MM/YYYY'):
                $('#reportrange span').html('30 Hari Terakhir');
                range = 4;
                break;
            case moment().startOf('month').format('DD/MM/YYYY'):
                $('#reportrange span').html('Bulan Ini');
                range = 5;
                break;
            case moment().subtract(1, 'month').startOf('month').format('DD/MM/YYYY'):
                $('#reportrange span').html('Bulan Lalu');
                range = 6;
                break;
            default:
                $('#reportrange span').html(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                range = 7;
                break;
        }
        Livewire.emit('setTanggal', picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'), range);
    });
</script>
@endpush