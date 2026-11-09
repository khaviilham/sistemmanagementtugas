@extends('layouts.app')

@section('title', 'Detail Penilaian Tugas Siswa')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/penilaian.css') }}">
@endpush

@section('content')
<div class="admin-dashboard-wrapper">

    @include('admin.partials.sidebar')

    <main class="admin-main-content">

        <!-- Topbar Header -->
        <header class="topbar-header mb-4">
            <div class="topbar-left">
                <span class="text-muted small"><i class="bi bi-book"></i> Sistem Manajemen Tugas</span>
            </div>
            <div class="topbar-right d-flex align-items-center gap-3">
                <span class="badge bg-indigo-soft text-indigo fw-semibold px-3 py-2 rounded-pill">Guru Informatika</span>
                <div class="user-profile-info text-end">
                    <span class="fw-bold d-block text-dark small">{{ $admin->nama ?? session('nama') ?? 'Administrator' }}</span>
                    <small class="text-muted" style="font-size: 11px;">{{ $admin->email ?? '' }}</small>
                </div>
                <div class="user-avatar">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100" class="rounded-circle" width="40" height="40" alt="Avatar">
                </div>
            </div>
        </header>

        <!-- Tombol Kembali -->
        <div class="mb-3">
            <a href="{{ route('admin.penilaian.index') }}" class="text-success text-decoration-none small fw-semibold">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Rekap Penilaian
            </a>
        </div>

        <!-- Header Ringkasan Tugas & Siswa -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-initials bg-indigo-subtle text-indigo fw-bold rounded-4 d-flex align-items-center justify-content-center fs-4" style="width: 56px; height: 56px;">
                        {{ strtoupper(substr($tugas->siswa->nama ?? 'S', 0, 2)) }}
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h4 class="fw-bold text-dark mb-0 fs-5">{{ $tugas->judul_tugas }}</h4>
                            <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1" style="font-size: 11px;">
                                <i class="bi bi-dot"></i> {{ ucwords(str_replace('_', ' ', $tugas->status_tugas ?? 'Menunggu Review')) }}
                            </span>
                        </div>
                        <div class="text-muted small d-flex align-items-center gap-2 flex-wrap">
                            <span><i class="bi bi-person me-1"></i>{{ $tugas->siswa->nama ?? '-' }}</span>
                            <span>•</span>
                            <span>Email: <strong>{{ $tugas->siswa->email ?? '-' }}</strong></span>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 bg-light p-3 rounded-4 border">
                    <div class="pe-3 border-end">
                        <small class="text-muted d-block fw-semibold" style="font-size: 10px; letter-spacing: 0.5px;">BATAS WAKTU</small>
                        <span class="fw-bold text-dark small">{{ $tugas->tenggat_waktu ?? 'Tidak ditentukan' }}</span>
                    </div>
                    <div>
                        <small class="text-muted d-block fw-semibold" style="font-size: 10px; letter-spacing: 0.5px;">TOTAL SKOR BOBOT</small>
                        <span class="fw-bold text-primary fs-6">100 Poin</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid 2 Kolom: Informasi Pengumpulan & Form Penilaian -->
        <div class="row g-4">
            
            <!-- Kolom Kiri: Informasi Pengumpulan Siswa -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="icon-square bg-indigo-subtle text-indigo rounded-3 p-2 d-flex align-items-center justify-content-center">
                                <i class="bi bi-check2-square fs-5"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-0 fs-6">Informasi Pengumpulan</h5>
                        </div>
                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 fw-normal" style="font-size: 11px;">
                            <i class="bi bi-clock me-1"></i> {{ $tugas->updated_at ? $tugas->updated_at->format('d M Y, H:i') : '-' }} WIB
                        </span>
                    </div>

                    <div class="bg-light p-3 rounded-4 mb-4 border">
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted d-block text-uppercase" style="font-size: 10px;">NAMA SISWA</small>
                                <span class="fw-bold text-dark">{{ $tugas->siswa->nama ?? '-' }}</span>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block text-uppercase" style="font-size: 10px;">EMAIL</small>
                                <span class="fw-bold text-dark">{{ $tugas->siswa->email ?? '-' }}</span>
                            </div>
                            <div class="col-12 mt-2">
                                <small class="text-muted d-block text-uppercase" style="font-size: 10px;">JUDUL TUGAS</small>
                                <span class="fw-bold text-success">{{ $tugas->judul_tugas }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bagian Dokumen / Gambar Lampiran dari Database (Pratinjau / Preview Saja) -->
                    <div>
                        <label class="form-label text-muted text-uppercase" style="font-size: 10px; font-weight: 600;">PRATINJAU LAMPIRAN TUGAS SISWA</label>
                        
                        @php
                            // Mengambil data lampiran terbaru yang dikirim oleh siswa untuk tugas ini
                            $lampiranTerbaru = $tugas->lampiran()->latest()->first();
                        @endphp

                        @if($lampiranTerbaru)
                            <div class="p-3 rounded-4 bg-light border text-center">
                                @php
                                    $ekstensi = pathinfo($lampiranTerbaru->file_berkas, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($ekstensi), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp

                                @if($isImage)
                                    <!-- Jika file berupa gambar, tampilkan pratinjau langsung -->
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $lampiranTerbaru->file_berkas) }}" alt="Lampiran Tugas" class="img-fluid rounded-3 shadow-sm" style="max-height: 350px; object-fit: contain;">
                                    </div>
                                    <span class="text-muted small"><i class="bi bi-image me-1"></i> {{ $lampiranTerbaru->nama_file ?? 'Gambar Lampiran' }}</span>
                                @else
                                    <!-- Jika file berupa dokumen/arsip (PDF, ZIP, dll) -->
                                    <div class="py-4">
                                        <i class="bi bi-file-earmark-text display-4 text-success mb-2 d-block"></i>
                                        <h6 class="fw-bold text-dark small mb-1">{{ $lampiranTerbaru->nama_file ?? 'Dokumen Lampiran' }}</h6>
                                        <span class="badge bg-secondary-subtle text-secondary">Format .{{ strtoupper($ekstensi) }}</span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="p-4 rounded-4 bg-light border text-center text-muted small">
                                <i class="bi bi-file-earmark-x fs-3 d-block mb-1"></i>
                                Belum ada berkas lampiran yang diunggah oleh siswa.
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            <!-- Kolom Kanan: Form Penilaian Guru -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="icon-square bg-success-subtle text-success rounded-3 p-2 d-flex align-items-center justify-content-center">
                                <i class="bi bi-pencil-square fs-5"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-0 fs-6">Form Penilaian Guru</h5>
                        </div>
                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1" style="font-size: 10px;">Fitur Utama</span>
                    </div>

                    <form action="{{ route('admin.tugas.nilai', $tugas->id_tugas) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="nilai" class="form-label mb-0 fw-semibold text-dark small">Nilai (0 – 100) <span class="text-danger">*</span></label>
                                <span class="badge bg-success text-white rounded-pill px-2 py-1 fw-normal" style="font-size: 11px;">Rentang Valid</span>
                            </div>
                            <div class="input-group input-group-lg rounded-4 overflow-hidden border">
                                <input type="number" class="form-control border-0 text-center fw-bold fs-3" id="nilai" name="nilai" min="0" max="100" value="{{ $tugas->nilai ?? '' }}" placeholder="0" required>
                                <span class="input-group-text bg-white border-0 text-muted fw-semibold fs-5 px-4">/ 100</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="feedback" class="form-label mb-0 fw-semibold text-dark small">Feedback / Evaluasi Siswa</label>
                                <small class="text-muted" style="font-size: 11px;">Maks. 500 Karakter</small>
                            </div>
                            <textarea class="form-control rounded-4 border p-3 text-secondary small" id="feedback" name="feedback" rows="6" placeholder="Tulis masukan atau catatan evaluasi untuk siswa di sini...">{{ $tugas->feedback ?? '' }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 rounded-pill py-3 fw-bold d-flex align-items-center justify-content-center gap-2 shadow-sm mb-3" style="background-color: #0d8a4e;">
                            <i class="bi bi-check-circle-fill"></i> Simpan Nilai & Feedback
                        </button>

                        <div class="alert alert-light border-0 bg-light rounded-4 p-3 mb-0 d-flex gap-2">
                            <i class="bi bi-info-circle text-info fs-5 flex-shrink-0"></i>
                            <p class="mb-0 text-secondary" style="font-size: 11px; line-height: 1.5;">
                                <strong>Keterangan Aksi:</strong> Saat tombol diklik, status tugas berubah otomatis menjadi <span class="text-success fw-bold">Selesai Dinilai</span> dan data tersimpan di database.
                            </p>
                        </div>
                    </form>

                </div>
            </div>

        </div>

    </main>
</div>
@endsection