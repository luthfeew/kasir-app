<div>
    @env('local')

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
        <x-widget bgColor="bg-info" icon="fas fa-dollar-sign" text="Total Penjualan" number="Rp. {{ number_format($totalPenjualan, 0, ',', '.') }}" />
        <x-widget bgColor="bg-success" icon="fas fa-coins" text="Laba Kotor" number="Rp. {{ number_format($labaKotor, 0, ',', '.') }}" />
        <x-widget bgColor="bg-success" icon="fas fa-hand-holding-usd" text="Terima Pembayaran" number="Rp. {{ number_format($terimaPembayaran, 0, ',', '.') }}" />
    </div>
    <div class="row">
        <x-widget bgColor="bg-danger" icon="fas fa-hand-holding-usd" text="Refund" number="Rp. {{ number_format($sumRefund, 0, ',', '.') }}" />
        <x-widget bgColor="bg-warning" icon="fas fa-hand-holding-usd" text="Hutang" number="Rp. {{ number_format($sumHutang, 0, ',', '.') }}" />
    </div>
    <div class="row">
        <x-widget bgColor="bg-info" icon="fas fa-money-check" text="Rata-Rata Transaksi" number="Rp. {{ number_format($rataTransaksi, 0, ',', '.') }}" />
        <x-widget bgColor="bg-info" icon="fas fa-list" text="Total Transaksi" number="{{ $totalTransaksi }}" />
        <x-widget bgColor="bg-info" icon="fas fa-boxes" text="Total Produk" number="{{ $totalProdukTerjual }}" />
    </div>

    @if ($listData)
    <div class="table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Total Penjualan</th>
                    <th>Laba Kotor</th>
                    <th>Terima Pembayaran</th>
                    <th>Refund</th>
                    <th>Hutang</th>
                    <th>Rata-rata Transaksi</th>
                    <th>Total Transaksi</th>
                    <th>Total Produk</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($listData as $item)
                <tr>
                    <td>{{ $item['tanggal'] }}</td>
                    <td>{{ $item['totalPenjualan'] }}</td>
                    <td>{{ $item['labaKotor'] }}</td>
                    <td>{{ $item['terimaPembayaran'] }}</td>
                    <td>{{ $item['sumRefund'] }}</td>
                    <td>{{ $item['sumHutang'] }}</td>
                    <td>{{ $item['rataTransaksi'] }}</td>
                    <td>{{ $item['totalTransaksi'] }}</td>
                    <td>{{ $item['totalProdukTerjual'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif
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
            'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Tahun Ini': [moment().startOf('year'), moment().endOf('year')],
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
            case moment().startOf('year').format('DD/MM/YYYY'):
                $('#reportrange span').html('Tahun Ini');
                range = 7;
                break;
            default:
                $('#reportrange span').html(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                range = 0;
                break;
        }
        Livewire.emit('setTanggal', picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'), range);
    });
</script>
@endpush