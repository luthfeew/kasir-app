<div>
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label>Rentang Waktu:</label>

                <div id="reportrange" class="input-group" wire:ignore>
                    <button type="button" class="btn btn-default float-right" id="daterange-btn">
                        <i class="far fa-calendar-alt"></i> <span>Hari Ini</span>
                        <i class="fas fa-caret-down"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-4">
            <div class="form-group" wire:ignore>
                <label>Kasir: </label>
                <select class="form-control select2bs4" style="width: 100%;">
                    <option value="0">Owner</option>
                </select>
            </div>
        </div> -->
    </div>

    @env('local')
    {{ $data }}
    @endenv

    <div class="table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Kasir</th>
                    <th>Saldo Awal</th>
                    <th>Saldo Akhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $item)
                <tr>
                    <td>
                        <h4>{{$item->first()->created_at->format('d/m/Y')}}</h4>
                        @foreach ($item as $i)
                        {{$i->waktu_mulai->format('H:i:s')}} - {{$i->waktu_selesai->format('H:i:s')}}<br>
                        @endforeach
                    </td>
                    <td>
                        <h4>{{$item->first()->user->nama}}</h4>
                    </td>
                    <td>
                        <h4></h4><br>
                        @foreach ($item as $i)
                        Rp {{number_format($i->saldo_awal, 0, ',', '.')}}<br>
                        @endforeach
                    </td>
                    <td>
                        <h4></h4><br>
                        @foreach ($item as $i)
                        Rp {{number_format($i->saldo_akhir ?? 0, 0, ',', '.')}}<br>
                        @endforeach
                    </td>
                    <td>
                        <h4></h4><br>
                        @foreach ($item as $i)
                        @if ($i->saldo_akhir || $i->created_at->format('d-m-Y') != date('d-m-Y'))
                        Selesai<br>
                        @else
                        <a href="{{ route('tutup_kasir') }}" class="btn btn-sm btn-warning">Tutup</a><br>
                        @endif
                        @endforeach
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
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