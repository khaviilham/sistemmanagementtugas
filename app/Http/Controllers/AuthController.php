<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function tampilkanLogin()
    {
        return view('auth.login');
    }

    // Memproses data login
public function prosesLogin(Request $request)
{
    $request->validate([
        'email' => ['required', 'email'],
        'kata_sandi' => ['required'],
    ]);

    // Cari pengguna berdasarkan email
    $pengguna = DB::table('pengguna')->where('email', $request->email)->first();

    if ($pengguna) {
        // Cek password: bisa berupa teks biasa ATAU hash Laravel
        $cekPassword = ($request->kata_sandi === $pengguna->kata_sandi) || 
                       (\Illuminate\Support\Facades\Hash::check($request->kata_sandi, $pengguna->kata_sandi));

        if ($cekPassword) {
            // Simpan sesi login
            session([
                'id_pengguna' => $pengguna->id_pengguna,
                'nama' => $pengguna->nama,
                'peran' => $pengguna->peran,
            ]);

            // Arahkan sesuai peran
            if ($pengguna->peran === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('siswa.dashboard');
            }
        }
    }

    return back()->withErrors([
        'email' => 'Email atau kata sandi yang Anda masukkan salah.',
    ])->withInput();
}
public function logout(Request $request)
{
    $request->session()->flush();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
}
}