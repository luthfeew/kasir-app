@extends('layouts.app', ['title' => 'Buka Kasir'])

@section('content')
<div class="row">
    <div class="col">

        <form action="{{ route('buka_kasir.store') }}" method="post">
            @csrf
            <x-card title="Buka Kasir">
                <x-input type="number" name="saldo_awal" label="Saldo Awal" />
                <x-slot name="footer">
                    <x-button>Simpan</x-button>
                    <x-button type="reset" color="secondary">Reset</x-button>
                </x-slot>
            </x-card>
        </form>

    </div>
</div>
@endsection