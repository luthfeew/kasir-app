<div>
    @env('local')
    {{ $tanggalAwal }}
    {{ $tanggalAkhir }}
    {{ $rentang }}
    {{ $inventaris }}
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
        <x-widget bgColor="bg-success" icon="fas fa-boxes" text="Total Produk Terjual" number="{{ abs($totalProdukTerjual) }} Produk" />
        <x-widget bgColor="bg-success" icon="fas fa-hand-holding-usd" text="Harga Total" number="Rp. {{ number_format($totalHargaTotal, 0, ',', '.') }}" />
    </div>

    <div class="table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Produk</th>
                    <th>Produk Terjual</th>
                    <th>Harga Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inventaris as $key => $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item['nama_produk'] }}</td>
                    <td>{{ abs($item['produk_terjual']) }}</td>
                    <td>Rp. {{ number_format($item['harga_total'], 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
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