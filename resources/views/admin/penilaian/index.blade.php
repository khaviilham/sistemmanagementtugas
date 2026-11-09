@extends('layouts.app')

@section('title', 'Daftar Pengumpulan & Penilaian Tugas')

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

        <!-- Judul & Deskripsi Halaman -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="badge bg-success-subtle text-success fw-semibold px-3 py-1 rounded-pill mb-1" style="font-size: 11px;">REKAPITULASI ASESMEN</span>
                <h1 class="welcome-title fs-3 mb-1 text-dark fw-bold">Daftar Pengumpulan & Penilaian Tugas</h1>
                <p class="text-muted small mb-0">Kelola dan periksa seluruh lembar kerja pengumpulan tugas siswa secara terpusat.</p>
            </div>
        </div>

        <!-- Tabel Daftar Tugas Masuk -->
        <section class="tasks-review-section p-4 bg-white rounded-4 shadow-sm border">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold text-dark mb-0 fs-6"><i class="bi bi-clipboard-check-fill me-2 text-success"></i>Semua Tugas Masuk Siswa</h5>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light text-muted small">
                        <tr>
                            <th class="py-3">NAMA SISWA</th>
                            <th class="py-3">JUDUL TUGAS & PROYEK</th>
                            <th class="py-3">WAKTU KIRIM</th>
                            <th class="py-3">STATUS</th>
                            <th class="py-3 text-end">TINDAKAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tugasList as $tugas)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar-initials bg-success-subtle text-success fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 13px;">
                                        {{ strtoupper(substr($tugas->siswa->nama ?? 'S', 0, 2)) }}
                                    </div>
                                    <div>
                                        <strong class="text-dark d-block small">{{ $tugas->siswa->nama ?? 'Siswa' }}</strong>
                                        <small class="text-muted" style="font-size: 11px;">NIS. {{ $tugas->siswa->nis ?? '-' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="fw-semibold text-dark small d-block">{{ $tugas->judul_tugas }}</span>
                                <small class="text-muted" style="font-size: 11px;"><i class="bi bi-folder2 me-1"></i>{{ $tugas->proyek->judul_proyek ?? 'Proyek Umum' }}</small>
                            </td>
                            <td>
                                <span class="text-muted small"><i class="bi bi-clock me-1"></i>{{ $tugas->updated_at ? $tugas->updated_at->format('d M Y, H:i') : '-' }}</span>
                            </td>
                            <td>
                                @if($tugas->status_tugas == 'selesai')
                                    <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1" style="font-size: 11px;">● Selesai Dinilai</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1" style="font-size: 11px;">● Menunggu Review</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <!-- Tombol mengarah ke halaman show.blade.php berdasarkan ID tugas -->
                                <a href="{{ route('admin.penilaian.show', $tugas->id_tugas) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 shadow-sm" style="font-size: 12px;">
                                    <i class="bi bi-pencil-square me-1"></i> Periksa Submission
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-journal-x fs-2 d-block mb-2 text-secondary"><span>Belum ada pengumpulan tugas dari siswa saat ini.</span></i>
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