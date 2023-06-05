<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sesi;

class HomeController extends Controller
{
    public function index()
    {
        // cek apakah user memiliki sesi 'mulai' hari ini
        $sesi = Sesi::where('user_id', Auth::user()->id)
            ->where('status', 'mulai')
            ->whereDate('waktu_mulai', now())
            ->first();
        return view('home.index', compact('sesi'));
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

        // Buat sesi baru jika user tidak memiliki sesi 'mulai' hari ini
        $sesi = Sesi::where('user_id', Auth::user()->id)
            ->where('status', 'mulai')
            ->whereDate('waktu_mulai', now())
            ->first();
        if (!$sesi) {
            $sesi = new Sesi;
            $sesi->user_id = Auth::user()->id;
            $sesi->status = 'mulai';
            $sesi->waktu_mulai = now();
            $sesi->waktu_selesai = now()->endOfDay();
            $sesi->saldo_awal = $request->saldo_awal;
            $sesi->save();
        } else {
            return redirect()->route('dashboard')->with('error', 'Anda sudah membuka kasir.');
        }

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

        // Update sesi jika user memiliki sesi 'mulai' hari ini
        $sesi = Sesi::where('user_id', Auth::user()->id)
            ->where('status', 'mulai')
            ->whereDate('waktu_mulai', now())
            ->first();
        if ($sesi) {
            $sesi->status = 'selesai';
            $sesi->waktu_selesai = now();
            $sesi->saldo_akhir = $request->saldo_akhir;
            $sesi->save();
        } else {
            return redirect()->route('dashboard')->with('error', 'Anda belum membuka kasir.');
        }

        return redirect()->route('dashboard')->with('success', 'Berhasil menutup kasir.');
    }
}
