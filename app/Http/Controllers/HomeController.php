<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sesi;

class HomeController extends Controller
{
    public function index()
    {
        // Check if user latest status in sesi = aktif, if aktif then redirect to buka_kasir
        // if (Auth::user()->sesi->last()->status == 'aktif') {
        //     return redirect()->route('buka_kasir');
        // }

        return view('home.index');
    }

    public function buka_kasir()
    {
        return view('home.buka-kasir');
    }

    public function buka_kasir_store(Request $request)
    {
        $request->validate([
            'saldo_awal' => 'required|numeric',
        ]);

        // Update status terakhir user yang memiliki status 'aktif' menjadi 'mulai' dan masukkan saldo awal
        $sesi = Sesi::where('user_id', Auth::user()->id)->where('status', 'aktif')->first();
        // jika user tidak memiliki sesi aktif, maka buat sesi baru
        if (!$sesi) {
            $sesi = new Sesi;
            $sesi->user_id = Auth::user()->id;
        }
        $sesi->status = 'mulai';
        $sesi->waktu_mulai = now();
        $sesi->saldo_awal = $request->saldo_awal;
        $sesi->save();

        return redirect()->route('dashboard')->with('success', 'Berhasil membuka kasir.');
    }

    public function tutup_kasir()
    {
        return view('home.tutup-kasir');
    }

    public function tutup_kasir_store(Request $request)
    {
        $request->validate([
            'saldo_akhir' => 'required|numeric',
        ]);

        // Update status terakhir user yang memiliki status 'mulai' menjadi 'selesai' dan masukkan saldo awal
        $sesi = Sesi::where('user_id', Auth::user()->id)->where('status', 'mulai')->first();
        $sesi->status = 'selesai';
        $sesi->waktu_selesai = now();
        $sesi->saldo_akhir = $request->saldo_akhir;
        $sesi->save();

        return redirect()->route('dashboard')->with('success', 'Berhasil menutup kasir.');
    }
}
