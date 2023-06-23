<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function index($id = null)
    {
        return view('kasir.index', [
            'id' => $id,
        ]);
    }
}
