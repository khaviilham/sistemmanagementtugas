@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')
<div class="admin-dashboard-wrapper">

    <!-- SIDEBAR KIRI -->
    @include('admin.partials.sidebar')

    <!-- KONTEN UTAMA KANAN -->
    <main class="admin-main-content">

        <!-- Header Topbar -->
        <header class="topbar-header">
            <div class="topbar-left">
                <span class="text-muted small"><i class="bi bi-book"></i> Sistem Manajemen Tugas</span>
            </div>
            <div class="topbar-right">
                <div class="user-badge-role">Guru Informatika</div>
                <div class="user-profile-info">
                    <span class="fw-bold d-block text-dark">{{ $admin->nama ?? session('nama') ?? 'Administrator' }}</span>
                    <small class="text-muted">{{ $admin->email ?? '' }}</small>
                </div>
                <div class="user-avatar">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100" alt="Avatar">
                </div>
                <form action="{{ route('logout') }}" method="POST" class="logout-form"
                    onsubmit="return confirm('Yakin ingin keluar dari akun?')">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </header>

        <!-- Banner Selamat Datang (Dinamis Sesuai Akun Login) -->
        <section class="welcome-banner">
            <div class="banner-left">
                <span class="badge-semester">Tahun Ajaran 2026/2027 • Semester Ganjil</span>
                <h1 class="welcome-title">Selamat Datang Kembali, {{ $admin->nama ?? session('nama') ?? 'Admin' }}.</h1>
                <p class="welcome-subtitle">Guru Pengampu Informatika</p>
            </div>
            <div class="banner-right">
                <a href="/admin/proyek" class="btn-create-task">
                    <i class="bi bi-plus-lg"></i> Buat Tugas Baru
                </a>
            </div>
        </section>

        <!-- Menu Utama / Quick Access Cards -->
        <section class="quick-menu-section mb-4">
            <h5 class="section-heading"><i class="bi bi-grid-fill"></i> Menu Utama <small class="text-muted fw-normal float-end">Navigasi Cepat Guru</small></h5>

            <div class="quick-cards-grid">
                <div class="quick-card">
                    <div class="q-top">
                        <div class="q-icon-box bg-purple-light text-purple"><i class="bi bi-clipboard-data"></i></div>
                        <div class="q-content">
                            <h5>Menu Proyek</h5>
                            <p>Kelola Proyek &amp; Tugas pembelajaran, lembar kerja, dan rubrik asesmen.</p>
                        </div>
                    </div>
                    <a href="/admin/proyek" class="q-link">Buka Proyek &amp; Tugas <i class="bi bi-arrow-right"></i></a>
                </div>

                <div class="quick-card">
                    <div class="q-top">
                        <div class="q-icon-box bg-orange-light text-orange"><i class="bi bi-journal-richtext"></i></div>
                        <div class="q-content">
                            <h5>Menu Tugas</h5>
                            <p>Daftar Pengumpulan &amp; Penilaian tugas masuk dari para siswa.</p>
                        </div>
                    </div>
                    <a href="/admin/penilaian" class="q-link">Buka Penilaian Tugas <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </section>

        <!-- Daftar Proyek Aktif (Dari Database) -->
        <section class="mb-4">
            <h5 class="section-heading"><i class="bi bi-folder-fill"></i> Daftar Proyek Aktif</h5>
            <div class="row">
                @forelse($proyekList as $proyek)
                <div class="col-md-4 mb-3">
                    <div class="card p-3 border-0 shadow-sm h-100">
                        <h5 class="text-success fw-bold">{{ $proyek->judul_proyek }}</h5>
                        <p class="text-muted small mb-2">{{ $proyek->deskripsi_proyek }}</p>
                        <div class="d-flex justify-content-between text-muted small mt-auto pt-2 border-top">
                            <span>Mulai: {{ $proyek->tanggal_mulai }}</span>
                            <span>Selesai: {{ $proyek->tanggal_selesai }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <p class="text-muted bg-white p-3 rounded shadow-sm text-center">Belum ada proyek yang dibuat.</p>
                </div>
                @endforelse
            </div>
        </section>

        <!-- Tabel Pengumpulan Tugas Masuk (Dari Database) -->
        <section class="tasks-review-section mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="section-heading mb-1"><i class="bi bi-clipboard-check-fill"></i> Pengumpulan Tugas Masuk Butuh Penilaian</h5>
                    <p class="text-muted small mb-0">Tugas siswa yang siap untuk diperiksa dan diberikan nilai umpan balik</p>
                </div>
                <a href="/admin/penilaian" class="text-success text-decoration-none fw-semibold small">
                    Lihat Semua ({{ $totalTugasMasuk ?? 0 }}) <i class="bi bi-chevron-right"></i>
                </a>
            </div>

            <div class="table-container-card">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>NAMA SISWA</th>
                            <th>JUDUL TUGAS</th>
                            <th>WAKTU KIRIM</th>
                            <th>STATUS</th>
                            <th class="text-end">TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tugasMasuk ?? [] as $tugas)
                        <tr>
                            <td>
                                <div class="student-info-cell">
                                    <div class="avatar-initials bg-primary-soft">
                                        {{ strtoupper(substr($tugas->siswa->nama ?? 'S', 0, 2)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $tugas->siswa->nama ?? 'Siswa' }}</strong>
                                        <small class="d-text-muted d-block">NIS. {{ $tugas->siswa->nis ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $tugas->judul_tugas }}</span>
                                <small class="d-block text-primary">{{ $tugas->proyek->judul_proyek ?? 'Proyek' }}</small>
                            </td>
                            <td><span class="text-muted small">{{ $tugas->created_at->diffForHumans() }}</span></td>
                            <td>
                                <span class="badge-status warning">● {{ ucwords(str_replace('_', ' ', $tugas->status_tugas)) }}</span>
                            </td>
                            <td class="text-end">
                                <a href="/admin/penilaian/{{ $tugas->id_tugas }}" class="btn-action-grade">
                                    Beri Nilai <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-check-circle fs-3 text-success d-block mb-1"></i>
                                Belum ada tugas siswa yang masuk.
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