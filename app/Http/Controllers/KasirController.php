<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function __construct()
    {
        // call function cekSesi() from HomeController, if it returns null, then redirect to dashboard
        $this->middleware(function ($request, $next) {
            $sesi = HomeController::cekSesi();
            if (!$sesi) {
                return redirect()->route('dashboard')->with('error', 'Anda belum membuka kasir.');
            }
            return $next($request);
        });
    }

    public function index($id = null)
    {
        return view('kasir.index', [
            'id' => $id,
        ]);
    }
}
