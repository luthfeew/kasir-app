@extends('layouts.app', ['title' => 'Pelanggan'])

@section('breadcrumb')
<!-- <li class="breadcrumb-item">Gudang</li> -->
@endsection

@section('content')
<div class="row">
    <div class="col">

        <x-card title="Data Pelanggan">

            <x-button-add link="{{ route('pelanggan.create') }}" label="Tambah Pelanggan" />

            <x-data-tables :kolomTabel="['No', 'Nama', 'Alamat', 'No Telepon', 'Aksi']">
                @forelse ($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>{{ $item->telepon }}</td>
                    <td>
                        <x-button-edit link="{{ route('pelanggan.edit', $item->id) }}" />
                        <x-button-delete link="{{ route('pelanggan.destroy', $item->id) }}" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </x-data-tables>

        </x-card>

    </div>
</div>
@endsection