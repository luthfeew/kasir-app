<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawan = User::where('role', 'kasir')->get();
        return view('karyawan.index', compact('karyawan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('karyawan.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'no_telp' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|max:255',
            ],
            [
                'name.required' => 'Nama karyawan harus diisi',
                'alamat.required' => 'Alamat karyawan harus diisi',
                'no_telp.required' => 'No. telp karyawan harus diisi',
                'username.required' => 'Username karyawan harus diisi',
                'password.required' => 'Password karyawan harus diisi',
            ]
        );

        User::create([
            'name' => $request->name,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'kasir',
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $karyawan = User::findOrFail($id);
        return view('karyawan.edit', compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'alamat' => 'required|string|max:255',
                'no_telp' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,' . $id,
            ],
            [
                'name.required' => 'Nama karyawan harus diisi',
                'alamat.required' => 'Alamat karyawan harus diisi',
                'no_telp.required' => 'No. telp karyawan harus diisi',
                'username.required' => 'Username karyawan harus diisi',
            ]
        );

        $karyawan = User::findOrFail($id);
        $karyawan->update([
            'name' => $request->name,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'username' => $request->username,
        ]);

        // jika password tidak kosong, maka update password
        if ($request->password != null) {
            $karyawan->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus');
    }
}
