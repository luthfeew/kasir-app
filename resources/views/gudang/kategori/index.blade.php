@extends('layouts.app', ['title' => 'Kategori Produk'])

@section('content')
<div class="row">
    <div class="col">

        <x-card title="Daftar Kategori Produk">

            <x-data-tables :kolomTabel="['Urutan', 'Nama', 'Produk Terdaftar', 'Aksi']">
                @foreach ($data as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->produk->count() }}</td>
                    <td>
                        EDIT
                    </td>
                </tr>
                @endforeach
            </x-data-tables>

        </x-card>

    </div>
</div>
@endsection