@extends('layouts.app', ['title' => 'Tutup Kasir'])

@section('content')
<div class="row">
    <div class="col">

        <form action="{{ route('tutup_kasir.store') }}" method="post">
            @csrf
            <x-card title="Tutup Kasir">
                <x-input type="number" name="saldo_akhir" label="Saldo Akhir" />
                <x-slot name="footer">
                    <x-button>Simpan</x-button>
                    <x-button type="reset" color="secondary">Reset</x-button>
                </x-slot>
            </x-card>
        </form>

    </div>
</div>
@endsection