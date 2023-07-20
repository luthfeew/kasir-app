@extends('layouts.app', ['title' => 'User'])

@section('breadcrumb')
<!-- <li class="breadcrumb-item">Gudang</li> -->
@endsection

@section('content')
<div class="row">
    <div class="col">

        <x-card title="Data User">
            
            <x-button-add link="{{ route('karyawan.create') }}" label="Tambah User" />

            <x-data-tables :kolomTabel="['No', 'Nama', 'Alamat', 'No Telepon', 'Role', 'Aksi']">
                @forelse ($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>{{ $item->telepon }}</td>
                    <td>{{ $item->role == 'admin' ? 'Owner' : $item->role }}</td>
                    <td>
                        <x-button-edit link="{{ route('karyawan.edit', $item->id) }}" />
                        <x-button-delete link="{{ route('karyawan.destroy', $item->id) }}" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </x-data-tables>

        </x-card>

    </div>
</div>
@endsection