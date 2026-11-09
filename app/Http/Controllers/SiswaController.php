<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tugas;
use App\Models\LampiranTugas;
use App\Models\KomentarTugas;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    // Menampilkan Dashboard Siswa & Tugas yang Ditugaskan Kepadanya
    public function dashboard()
    {
        if (session('peran') !== 'siswa') {
            return redirect('/login')->withErrors(['email' => 'Akses ditolak! Halaman ini khusus siswa.']);
        }

        $idSiswa = session('id_pengguna');
        $namaSiswa = session('nama', 'Siswa');

        // Ambil data profil siswa untuk menampilkan informasi kelas/NISN
        $siswa = Pengguna::where('id_pengguna', $idSiswa)->first();

        // Ambil tugas yang spesifik ditugaskan ke siswa yang sedang login beserta relasinya
        $tugasList = Tugas::with('proyek', 'lampiran', 'komentar.pengirim')
                        ->where('ditugaskan_kepada', $idSiswa)
                        ->get();

        // Hitung statistik status tugas secara dinamis dari database
        $hitungBelumMulai = $tugasList->where('status_tugas', 'belum_mulai')->count();
        $hitungProses = $tugasList->where('status_tugas', 'proses')->count();
        $hitungReview = $tugasList->where('status_tugas', 'menunggu_review')->count();
        $hitungSelesai = $tugasList->where('status_tugas', 'selesai')->count();

        return view('siswa.dashboard', compact(
            'namaSiswa', 
            'siswa', 
            'tugasList', 
            'hitungBelumMulai', 
            'hitungProses', 
            'hitungReview', 
            'hitungSelesai'
        ));
    }

    // Mengunggah Lampiran Berkas & Komentar Sekaligus dari Mini-Popup
public function uploadLampiran(Request $request, $idTugas)
{
    $request->validate([
        'berkas' => 'required|file|mimes:zip,rar,pdf,jpg,jpeg,png|max:25600', 
        'komentar' => 'nullable|string|max:500',
    ]);

    $idSiswa = session('id_pengguna');

    if ($request->hasFile('berkas')) {
        $file = $request->file('berkas');
        $namaFileAsli = $file->getClientOriginalName();
        $pathFile = $file->store('lampiran_tugas', 'public');

        // Sesuaikan dengan struktur Model LampiranTugas Anda
        LampiranTugas::create([
            'id_tugas'      => $idTugas,
            'nama_file'     => $namaFileAsli,   // Nama asli file
            'file_berkas'   => $pathFile,       // Path penyimpanan file di storage
            'diupload_oleh' => $idSiswa,        // ID siswa yang mengunggah
        ]);
    }

    if ($request->filled('komentar')) {
        KomentarTugas::create([
            'id_tugas'     => $idTugas,
            'id_pengguna'  => $idSiswa,
            'isi_komentar' => $request->komentar,
        ]);
    }

    // Ubah status tugas menjadi menunggu_review
    $tugas = Tugas::findOrFail($idTugas);
    $tugas->update([
        'status_tugas' => 'menunggu_review'
    ]);

    return back()->with('success', 'Tugas dan berkas berhasil dikirim!');
}

    // Mengirim Komentar / Diskusi Tugas secara Terpisah (Opsional)
    public function storeKomentar(Request $request, $idTugas)
    {
        $request->validate([
            'isi_komentar' => 'required|string|max:500',
        ]);

        KomentarTugas::create([
            'id_tugas' => $idTugas,
            'id_pengguna' => session('id_pengguna'),
            'isi_komentar' => $request->isi_komentar,
        ]);

        return back()->with('success', 'Komentar berhasil dikirim.');
    }

    // Menampilkan Halaman Nilai Tugas Siswa
    public function nilaiTugas()
    {
        if (session('peran') !== 'siswa') {
            return redirect('/login')->withErrors(['email' => 'Akses ditolak! Halaman ini khusus siswa.']);
        }

        $idSiswa = session('id_pengguna');
        $namaSiswa = session('nama', 'Siswa');
        $siswa = Pengguna::where('id_pengguna', $idSiswa)->first();

        // Ambil semua tugas milik siswa
        $tugasList = Tugas::with(['proyek', 'guru'])
                        ->where('ditugaskan_kepada', $idSiswa)
                        ->latest()
                        ->get();

        // Hitung statistik untuk kotak ringkasan atas
        $tugasSelesaiCount = $tugasList->filter(function($t) {
            return in_array(strtolower(trim($t->status_tugas)), ['selesai', 'selesai_dinilai', 'dinilai']);
        })->count();

        $tugasBelumCount = $tugasList->filter(function($t) {
            return !in_array(strtolower(trim($t->status_tugas)), ['selesai', 'selesai_dinilai', 'dinilai']);
        })->count();

        return view('siswa.nilai', compact(
            'namaSiswa', 
            'siswa', 
            'tugasList', 
            'tugasSelesaiCount', 
            'tugasBelumCount'
        ));
    }
}