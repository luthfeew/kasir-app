<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Sesi;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Display the login view.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Buat sesi baru cek apakah user saat ini ada sesi yang 'aktif' atau 'mulai' jika ada maka abaikan jika tidak ada maka buat sesi baru
            $sesi = Sesi::where('user_id', Auth::user()->id)->where('status', 'aktif')->orWhere('status', 'mulai')->first();
            if (!$sesi) {
                Sesi::create([
                    'user_id' => Auth::user()->id,
                ]);
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        // Cek apakah user saat ini ada sesi yang aktif jika ada maka ubah status sesi menjadi selesai
        // $sesi = Sesi::where('user_id', Auth::user()->id)->where('status', 'aktif')->first();
        // if ($sesi) {
        //     $sesi->update([
        //         'status' => 'selesai',
        //     ]);
        // }

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
