<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyek;
use App\Models\Tugas;
use App\Models\Pengguna;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Menampilkan Dashboard Admin/Guru beserta seluruh data dari database
    public function index()
    {
        if (session('peran') !== 'admin') {
            return redirect('/login')->withErrors(['email' => 'Akses ditolak! Halaman ini khusus Admin/Guru.']);
        }

        // Ambil data admin yang sedang login berdasarkan session
        $admin = Pengguna::where('id_pengguna', session('id_pengguna'))->first();

        // 1. Mengambil daftar proyek beserta relasi pembuat dan tugasnya
        $proyekList = Proyek::with('pembuat', 'tugas.siswa')->latest()->get();

        // 2. Mengambil daftar tugas siswa yang masuk untuk ditampilkan pada tabel dashboard
        $tugasMasuk = Tugas::with(['siswa', 'proyek'])
            ->latest()
            ->take(5)
            ->get();

        // 3. Menghitung total tugas masuk
        $totalTugasMasuk = Tugas::count();

        // 4. Mengambil daftar data siswa
        $siswaList = Pengguna::where('peran', 'siswa')->get();

        return view('admin.dashboard', compact('proyekList', 'tugasMasuk', 'totalTugasMasuk', 'siswaList', 'admin'));
    }

    // Menampilkan Halaman Kelola Proyek & Tugas Akademik (Sesuai Mockup)
    public function proyekIndex(Request $request)
    {
        if (session('peran') !== 'admin') {
            return redirect('/login')->withErrors(['email' => 'Akses ditolak! Halaman ini khusus Admin/Guru.']);
        }

        // Ambil data admin yang sedang login
        $admin = Pengguna::where('id_pengguna', session('id_pengguna'))->first();

        // Ambil semua daftar proyek
        $proyekList = Proyek::with(['tugas.siswa', 'pembuat'])->latest()->get();

        // Tentukan proyek yang sedang dipilih (default ke proyek pertama jika ada)
        $selectedProyekId = $request->get('proyek_id', $proyekList->first()->id_proyek ?? null);
        $selectedProyek = Proyek::with(['tugas.siswa'])->find($selectedProyekId);

        // Ambil daftar tugas yang terhubung dengan proyek yang sedang dipilih
        $tugasList = $selectedProyek ? $selectedProyek->tugas : collect();

        // Ambil siswa dari database
        $siswaList = \App\Models\Pengguna::where('peran', 'siswa')->get();

        return view('admin.proyek.index', compact('admin', 'proyekList', 'selectedProyek', 'tugasList', 'siswaList'));
    }

    // Menyimpan Proyek Baru
    public function storeProyek(Request $request)
    {
        $request->validate([
            'judul_proyek' => 'required|string|max:255',
            'deskripsi_proyek' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        Proyek::create([
            'judul_proyek' => $request->judul_proyek,
            'deskripsi_proyek' => $request->deskripsi_proyek,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'dibuat_oleh' => session('id_pengguna'),
        ]);

        return redirect()->route('admin.proyek.index')->with('success', 'Proyek berhasil ditambahkan!');
    }

    // Menyimpan Tugas Baru & Menugaskan ke Siswa
    public function storeTugas(Request $request)
    {
        $request->validate([
            'id_proyek'         => 'required|exists:proyek,id_proyek',
            'judul_tugas'       => 'required|string|max:255',
            'deskripsi_tugas'   => 'required|string',
            'ditugaskan_kepada' => 'required|exists:pengguna,id_pengguna',
            'tanggal_deadline'  => 'required|date',
            'jam_deadline'      => 'required',
        ]);

        // Gabungkan tanggal dan jam deadline menjadi format datetime (YYYY-MM-DD HH:MM:SS)
        $tenggatGabung = $request->tanggal_deadline . ' ' . $request->jam_deadline . ':00';

        Tugas::create([
            'id_proyek'         => $request->id_proyek,
            'judul_tugas'       => $request->judul_tugas,
            'deskripsi_tugas'   => $request->deskripsi_tugas,
            'ditugaskan_kepada' => $request->ditugaskan_kepada,
            'status_tugas'      => 'belum_mulai',
            'tenggat_waktu'     => $tenggatGabung,
        ]);

        return redirect()->route('admin.proyek.index', ['proyek_id' => $request->id_proyek])
            ->with('success', 'Tugas berhasil ditambahkan!');
    }

        // Memperbarui Tugas
    public function updateTugas(Request $request, $id)
    {
        if (session('peran') !== 'admin') {
            return redirect('/login')->withErrors(['email' => 'Akses ditolak! Halaman ini khusus Admin/Guru.']);
        }

        $request->validate([
            'id_proyek'         => 'required|exists:proyek,id_proyek',
            'judul_tugas'       => 'required|string|max:255',
            'deskripsi_tugas'   => 'required|string',
            'ditugaskan_kepada' => 'required|exists:pengguna,id_pengguna',
            'tanggal_deadline'  => 'required|date',
            'jam_deadline'      => 'required',
        ]);

        $tugas = Tugas::findOrFail($id);

        $tugas->update([
            'id_proyek'         => $request->id_proyek,
            'judul_tugas'       => $request->judul_tugas,
            'deskripsi_tugas'   => $request->deskripsi_tugas,
            'ditugaskan_kepada' => $request->ditugaskan_kepada,
            'tenggat_waktu'     => $request->tanggal_deadline . ' ' . $request->jam_deadline . ':00',
        ]);

        return redirect()->route('admin.proyek.index', ['proyek_id' => $request->id_proyek])
            ->with('success', 'Tugas berhasil diperbarui!');
    }

    // Menghapus Tugas
    public function destroyTugas($id)
    {
        if (session('peran') !== 'admin') {
            return redirect('/login')->withErrors(['email' => 'Akses ditolak! Halaman ini khusus Admin/Guru.']);
        }

        $tugas = Tugas::findOrFail($id);
        $idProyek = $tugas->id_proyek;
        $tugas->delete();

        return redirect()->route('admin.proyek.index', ['proyek_id' => $idProyek])
            ->with('success', 'Tugas berhasil dihapus.');
    }

    // Menilai Tugas & Memberikan Feedback
// Menampilkan daftar rekap di index.blade.php
    public function indexPenilaian()
    {
        if (session('peran') !== 'admin') {
            return redirect('/login')->withErrors(['email' => 'Akses ditolak! Halaman ini khusus Admin/Guru.']);
        }

        $admin = Pengguna::where('id_pengguna', session('id_pengguna'))->first();
        $tugasList = Tugas::with(['proyek', 'siswa', 'lampiran'])
                    ->whereIn('status_tugas', ['menunggu_review', 'selesai', 'selesai_dinilai'])
                    ->latest()
                    ->get();

    return view('admin.penilaian.index', compact('tugasList'));

    }

    // Menampilkan halaman form penilaian satuan di show.blade.php
    public function showPenilaian($id)
    {
        if (session('peran') !== 'admin') {
            return redirect('/login')->withErrors(['email' => 'Akses ditolak! Halaman ini khusus Admin/Guru.']);
        }

        $admin = Pengguna::where('id_pengguna', session('id_pengguna'))->first();
        $tugas = Tugas::with(['siswa', 'proyek'])->findOrFail($id);

        return view('admin.penilaian.show', compact('admin', 'tugas'));
    }

    // Memproses penyimpanan nilai
    public function nilaiTugas(Request $request, $id)
    {
        $request->validate([
            'nilai' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:500',
        ]);

        $tugas = Tugas::findOrFail($id);
        $tugas->nilai = $request->nilai;
        $tugas->feedback = $request->feedback;
        $tugas->status_tugas = 'selesai';
        $tugas->save();

        return redirect()->route('admin.penilaian.index')->with('success', 'Nilai berhasil disimpan.');
    }
}