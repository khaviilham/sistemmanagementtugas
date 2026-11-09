@extends('layouts.app')

@section('title', 'Nilai Tugas Siswa')

@section('content')
<div class="admin-dashboard-wrapper">

    <!-- SIDEBAR KHUSUS SISWA -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <div class="brand-title">Todoist</div>
            <div class="brand-subtitle">Sistem Management Tugas</div>
        </div>

        <div class="sidebar-badge-role">
            <i class="bi bi-person-badge"></i> Siswa
        </div>

        <div class="nav-section-title">AKSES SISWA</div>
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('siswa.dashboard') }}" class="nav-link">
                    <i class="bi bi-journal-richtext"></i> Tugas Saya
                </a>
            </li>
            <li>
                <a href="{{ route('siswa.nilai') }}" class="nav-link active">
                    <i class="bi bi-star"></i> Nilai Tugas
                </a>
            </li>
        </ul>

    </aside>

    <!-- KONTEN UTAMA KANAN -->
    <main class="admin-main-content">

        <!-- Header Topbar -->
        <header class="topbar-header">
            <div class="topbar-left">
                <span class="text-muted small"><i class="bi bi-book me-1"></i> Portal Akademik Terpadu</span>
            </div>
            <div class="topbar-right">
                <div class="user-badge-role">Siswa</div>
                <div class="user-profile-info">
                    <span class="fw-bold d-block text-dark">{{ $namaSiswa ?? session('nama') ?? 'Budi Kurniawan' }}</span>
                    <small class="text-muted" style="font-size: 11px;">NISN. {{ $siswa->nisn ?? '12345678' }}</small>
                </div>
                <div class="user-avatar">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100" alt="Avatar">
                </div>
            </div>
        </header>

        <!-- Judul Halaman -->
        <div class="mb-4">
            <h1 class="welcome-title fs-3 fw-bold text-dark">Nilai & Evaluasi Tugas</h1>
            <p class="text-muted small mb-0">Rekapitulasi lengkap skor asesmen dan catatan evaluasi dari guru pembimbing.</p>
        </div>

        <!-- Statistik Atas (2 Kotak Sesuai Mockup) -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white d-flex flex-row justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold d-block mb-1">Tugas Selesai</span>
                        <h2 class="fw-bold text-dark mb-2 fs-3">{{ $tugasSelesaiCount }} <span class="text-muted fs-6 fw-normal">/ {{ count($tugasList) }} Tugas</span></h2>
                        <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1 fw-normal" style="font-size: 11px;">
                            ● Sudah dinilai
                        </span>
                    </div>
                    <div class="bg-success-subtle text-success p-3 rounded-4 fs-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white d-flex flex-row justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small fw-semibold d-block mb-1">Belum Selesai</span>
                        <h2 class="fw-bold text-dark mb-2 fs-3">{{ $tugasBelumCount }} <span class="text-muted fs-6 fw-normal">Tugas Tersisa</span></h2>
                        <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-3 py-1 fw-normal" style="font-size: 11px;">
                            ● Menunggu pengumpulan / penilaian
                        </span>
                    </div>
                    <div class="bg-warning-subtle text-warning-emphasis p-3 rounded-4 fs-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Rekapitulasi Nilai Tugas -->
        <section class="tasks-review-section p-4 bg-white rounded-4 shadow-sm border">
            <div class="mb-4">
                <h5 class="fw-bold text-dark mb-0 fs-6"><i class="bi bi-journal-text me-2 text-success"></i>Daftar Riwayat Penilaian Asesmen</h5>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="py-3">PROYEK</th>
                            <th class="py-3">TUGAS & PENGAJAR</th>
                            <th class="py-3">TANGGAL DINILAI</th>
                            <th class="py-3">NILAI AKHIR</th>
                            <th class="py-3">CATATAN EVALUASI GURU</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tugasList as $tugas)
                        @php
                            $statusClean = strtolower(str_replace(' ', '_', trim($tugas->status_tugas ?? '')));
                            $isSelesai = in_array($statusClean, ['selesai', 'selesai_dinilai', 'dinilai']);
                        @endphp
                        <tr>
                            <td>
                                <span class="fw-semibold text-dark small d-block">{{ $tugas->proyek->judul_proyek ?? 'Proyek Pembelajaran' }}</span>
                                <span class="badge bg-indigo-soft text-indigo fw-normal mt-1" style="font-size: 10px;">Mandiri / Individu</span>
                            </td>
                            <td>
                                <span class="fw-bold text-dark small d-block">{{ $tugas->judul_tugas }}</span>
                                <small class="text-muted" style="font-size: 11px;"><i class="bi bi-person me-1"></i>{{ $tugas->guru->nama ?? 'Pengajar Mata Pelajaran' }}</small>
                            </td>
                            <td>
                                <span class="text-muted small">
                                    <i class="bi bi-clock me-1"></i>{{ $isSelesai ? $tugas->updated_at->format('d M Y, H:i') . ' WIB' : '-' }}
                                </span>
                            </td>
                            <td>
                                @if($isSelesai)
                                    <span class="badge bg-success-subtle text-success fw-bold px-3 py-2 rounded-pill fs-6">
                                        {{ $tugas->nilai ?? 0 }} <span class="fw-normal" style="font-size: 11px;">/ 100</span>
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis fw-semibold px-3 py-2 rounded-pill" style="font-size: 11px;">
                                        Menunggu Review
                                    </span>
                                @endif
                            </td>
                            <td>
                                <p class="text-secondary small mb-0 fst-italic" style="max-width: 250px;">
                                    "{{ $tugas->feedback ?? ($isSelesai ? 'Pengerjaan sudah baik dan sesuai instruksi.' : 'Belum ada catatan evaluasi.') }}"
                                </p>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-journal-x fs-2 d-block mb-2 text-secondary"></i>
                                <span>Belum ada riwayat tugas atau penilaian saat ini.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </main>
</div>
@endsection