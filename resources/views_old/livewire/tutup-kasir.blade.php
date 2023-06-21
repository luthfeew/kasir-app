<div>
    <div class="row">
        <div class="col-6">
            <!-- Date range -->
            <div class="form-group">
                <label>Rentang waktu:</label>

                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </span>
                    </div>
                    <input wire:model="tanggal" type="text" class="form-control float-right" id="reservation">
                </div>
                <!-- /.input group -->
            </div>
            <!-- /.form group -->
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
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
                        <h4>{{$item->first()->created_at->format('d-m-Y')}}</h4>
                        @foreach ($item as $i)
                        {{$i->waktu_mulai->format('H:i:s')}} - {{$i->waktu_selesai->format('H:i:s')}}<br>
                        @endforeach
                    </td>
                    <td>
                        <h4><br></h4>
                        @foreach ($item as $i)
                        {{$i->user->name}}<br>
                        @endforeach
                    </td>
                    <td>
                        <h4><br></h4>
                        @foreach ($item as $i)
                        {{$i->saldo_awal}}<br>
                        @endforeach
                    </td>
                    <td>
                        <h4><br></h4>
                        @foreach ($item as $i)
                        {{$i->saldo_akhir ?? '-'}}<br>
                        @endforeach
                    </td>
                    <td>
                        <h4><br></h4>
                        @foreach ($item as $i)
                        @if ($i->created_at->format('d-m-Y') != date('d-m-Y'))
                        Selesai<br>
                        @else
                        <a href="{{ route('tutup_kasir') }}" class="btn btn-sm btn-warning">Tutup</a>
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