@extends('layouts.app', ['title' => 'Kategori Produk'])

@section('breadcrumb')
<li class="breadcrumb-item">Gudang</li>
@endsection

@section('content')
<div class="row">
    <div class="col">

        <x-card title="Daftar Kategori Produk">
            
            <x-button-add link="{{ route('kategori.create') }}" label="Tambah Kategori Produk" />

            <x-data-tables :kolomTabel="['Urutan', 'Nama', 'Produk Terdaftar', 'Aksi']">
                @forelse ($data as $item)
                <tr>
                    <td>{{ $item->urutan }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->produk->count() }}</td>
                    <td>
                        <x-button-edit link="{{ route('kategori.edit', $item->id) }}" />
                        <x-button-delete link="{{ route('kategori.destroy', $item->id) }}" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </x-data-tables>

        </x-card>

    </div>
</div>
@endsection