<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sesi;

class HomeController extends Controller
{
    public static function cekSesi()
    {
        // cek apakah user memiliki sesi 'mulai' hari ini
        $sesi = Sesi::where('user_id', Auth::user()->id)
            ->where('status', 'mulai')
            ->whereDate('waktu_mulai', now())
            ->first();
        return $sesi;
    }

    public function index()
    {
        // panggil fungsi cekSesi()
        $sesi = $this->cekSesi();
        return view('home.index', compact('sesi'));
    }

    public function bukaKasir()
    {
        return view('home.buka-kasir');
    }

    public function bukaKasirStore(Request $request)
    {
        $request->validate([
            'saldo_awal' => 'required|numeric',
        ]);

        // panggil fungsi cekSesi()
        $sesi = $this->cekSesi();
        if (!$sesi) {
            Sesi::create([
                'user_id' => Auth::user()->id,
                'status' => 'mulai',
                'waktu_mulai' => now(),
                'waktu_selesai' => now()->endOfDay(),
                'saldo_awal' => $request->saldo_awal,
            ]);
        } else {
            return redirect()->route('dashboard')->with('error', 'Anda sudah membuka kasir.');
        }

        return redirect()->route('dashboard')->with('success', 'Berhasil membuka kasir.');
    }

    public function tutupKasir()
    {
        return view('home.tutup-kasir');
    }

    public function tutupKasirStore(Request $request)
    {
        $request->validate([
            'saldo_akhir' => 'required|numeric',
        ]);

        // panggil fungsi cekSesi()
        $sesi = $this->cekSesi();
        if ($sesi) {
            $sesi->update([
                'status' => 'selesai',
                'waktu_selesai' => now(),
                'saldo_akhir' => $request->saldo_akhir,
            ]);
        } else {
            return redirect()->route('dashboard')->with('error', 'Anda belum membuka kasir.');
        }

        return redirect()->route('dashboard')->with('success', 'Berhasil menutup kasir.');
    }
}
