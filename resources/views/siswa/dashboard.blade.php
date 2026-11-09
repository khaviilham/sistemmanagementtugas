@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="admin-dashboard-wrapper">

    <!-- SIDEBAR SISWA -->
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
                <a href="{{ route('siswa.dashboard') }}" class="nav-link active">
                    <i class="bi bi-journal-richtext"></i> Tugas Saya
                </a>
            </li>
            <li>
                <a href="{{ route('siswa.nilai') }}" class="nav-link">
                    <i class="bi bi-star"></i> Nilai Tugas
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="btn-logout w-100 justify-content-center">
                    <i class="bi bi-box-arrow-right"></i> Keluar Akun
                </button>
            </form>
        </div>
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
                    <span class="fw-bold d-block text-dark">{{ $namaSiswa ?? session('nama') ?? 'Hibban Ibrahim' }}</span>
                    <small class="text-muted" style="font-size: 11px;">{{ session('email') ?? 'hibban@siswa.com' }}</small>
                </div>
                <div class="user-avatar">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100" alt="Avatar">
                </div>
            </div>
        </header>

        <!-- Banner Selamat Datang Siswa -->
        <section class="welcome-banner">
            <div class="banner-left">
                <span class="badge-semester"><i class="bi bi-person-check me-1"></i> Siswa Aktif</span>
                <h1 class="welcome-title">Selamat Datang, {{ $namaSiswa ?? 'Hibban Ibrahim' }}</h1>
                <p class="welcome-subtitle">Semester Ganjil 2026/2027</p>
            </div>
        </section>

        <!-- Statistik Kartu Ringkasan (4 Kotak) -->
        <div class="student-stats-grid">
            <div class="stat-card-box">
                <div class="stat-icon-wrap" style="background-color: #fde4c4; color: #f97316;">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-info">
                    <span>Perlu Dikerjakan</span>
                    <h3>{{ $hitungBelumMulai ?? 1 }}</h3>
                </div>
            </div>

            <div class="stat-card-box">
                <div class="stat-icon-wrap" style="background-color: #e0e7ff; color: #4f46e5;">
                    <i class="bi bi-arrow-repeat"></i>
                </div>
                <div class="stat-info">
                    <span>Sedang Dikerjakan</span>
                    <h3>{{ $hitungProses ?? 0 }}</h3>
                </div>
            </div>

            <div class="stat-card-box">
                <div class="stat-icon-wrap" style="background-color: #fef3c7; color: #b45309;">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div class="stat-info">
                    <span>Menunggu Review</span>
                    <h3>{{ $hitungReview ?? 0 }}</h3>
                </div>
            </div>

            <div class="stat-card-box">
                <div class="stat-icon-wrap" style="background-color: #dcfce7; color: #15803d;">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-info">
                    <span>Selesai Dinilai</span>
                    <h3>{{ $hitungSelesai ?? 2 }}</h3>
                </div>
            </div>
        </div>

        <!-- Daftar Kartu Tugas Siswa (Grid 3 Kolom) -->
        <section class="mb-4">
            <h5 class="section-heading mb-3"><i class="bi bi-grid-fill me-2"></i> Daftar Tugas & Proyek Pembelajaran</h5>

            <div class="student-tasks-grid">
                @forelse($tugasList ?? [] as $tugas)
                @php
                $statusClean = strtolower(str_replace(' ', '_', trim($tugas->status_tugas ?? 'belum_mulai')));
                $guruData = \App\Models\Pengguna::find($tugas->id_pengguna ?? $tugas->id_guru ?? null);
                $namaGuruPengampu = $guruData->nama ?? $tugas->nama_guru ?? 'Pengajar Mata Pelajaran';
                @endphp

                <div class="student-task-card">
                    <div>
                        <!-- Header Kartu: Kategori & Status Badge -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small fw-semibold"><i class="bi bi-code-slash me-1"></i> {{ $tugas->proyek->judul_proyek ?? 'Proyek Pembelajaran' }}</span>

                            @if(in_array($statusClean, ['belum_mulai', 'belum']))
                            <span class="badge-sub-info belum-mulai">Belum Mulai</span>
                            @elseif(in_array($statusClean, ['proses', 'sedang_proses', 'sedang_dikerjakan']))
                            <span class="badge-sub-info sedang-proses">Sedang Proses</span>
                            @elseif(in_array($statusClean, ['menunggu_review', 'review', 'sudah_dikirim']))
                            <span class="badge-sub-info menunggu-review">Menunggu Review</span>
                            @else
                            <span class="badge-sub-info selesai-dinilai"><i class="bi bi-dot"></i> Selesai Dinilai</span>
                            @endif
                        </div>

                        <!-- Judul Tugas -->
                        <h6 class="fw-bold text-dark mb-2 fs-6">{{ $tugas->judul_tugas }}</h6>

                        <!-- Pengajar / Guru -->
                        <p class="text-muted small mb-4"><i class="bi bi-person me-1"></i> {{ $namaGuruPengampu }}</p>
                    </div>

                    <!-- Footer Card Berdasarkan Status -->
                    <div class="mt-auto pt-3 border-top">
                        @if(in_array($statusClean, ['selesai', 'selesai_dinilai', 'dinilai']))
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small">Nilai Akhir</span>
                            <span class="fw-bold text-success fs-6">{{ $tugas->nilai ?? 0 }} / 100</span>
                        </div>
                        <a href="{{ route('siswa.nilai') }}" class="btn btn-success w-100 rounded-pill py-2 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2" style="font-size: 12px; background-color: #0d8a4e;">
                            <i class="bi bi-award"></i> Lihat Hasil Nilai
                        </a>

                        @elseif(in_array($statusClean, ['menunggu_review', 'review', 'sudah_dikirim']))
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small">Status Kirim</span>
                            <span class="fw-bold text-success small"><i class="bi bi-check-circle me-1"></i> Sudah Dikirim</span>
                        </div>
                        <a href="#" class="btn w-100 rounded-pill py-2 fw-semibold text-dark shadow-sm d-flex align-items-center justify-content-center gap-2" style="font-size: 12px; background-color: #e2e8f0;">
                            <i class="bi bi-eye"></i> Lihat Tugas lain yang ditugaskan.
                        </a>

                        @else
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted small">Tenggat Waktu</span>
                            <span class="fw-bold text-danger small"><i class="bi bi-clock me-1 text-danger"></i> {{ $tugas->tenggat_waktu ?? '2026-11-10 23:59' }}</span>
                        </div>
                        <!-- Tombol Membuka Mini Popup Modal Detail Tugas -->
                        <button type="button" class="btn btn-success w-100 rounded-pill py-2 fw-semibold shadow-sm d-flex align-items-center justify-content-center gap-2" style="font-size: 12px; background-color: #0d8a4e;" data-bs-toggle="modal" data-bs-target="#modalDetailTugas-{{ $tugas->id_tugas }}">
                            Lanjutkan Pengerjaan <i class="bi bi-arrow-right"></i>
                        </button>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5 bg-white rounded-4 border shadow-sm">
                    <i class="bi bi-journal-check fs-1 text-success d-block mb-2"></i>
                    <h6 class="fw-bold text-dark">Belum ada tugas yang ditugaskan kepada Anda.</h6>
                </div>
                @endforelse
            </div>
        </section>

    </main>
</div>

<!-- ========================================== -->
<!-- MINI POPUP MODAL (DI LUAR GRID UTAMA)       -->
<!-- ========================================== -->
@foreach($tugasList ?? [] as $tugas)
<div class="modal fade" id="modalDetailTugas-{{ $tugas->id_tugas }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            <div class="modal-header bg-light border-bottom px-4 py-3">
                <div>
                    <span class="badge bg-warning-subtle text-warning-emphasis fw-semibold mb-1" style="font-size: 11px;">
                        <i class="bi bi-clock-history me-1"></i> {{ ucfirst(str_replace('_', ' ', $tugas->status_tugas ?? 'Sedang Berjalan')) }}
                    </span>
                    <span class="text-muted small ms-2">{{ $tugas->proyek->judul_proyek ?? 'Proyek Pembelajaran' }}</span>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <h4 class="fw-bold text-dark mb-3">{{ $tugas->judul_tugas }}</h4>

                <div class="row g-2 mb-4 p-3 bg-light rounded-3 border align-items-center">
                    <div class="col-md-4">
                        <small class="text-muted d-block" style="font-size: 10px;">GURU PENGAMPU</small>
                        @php
                        $guruDataModal = \App\Models\Pengguna::find($tugas->id_pengguna ?? $tugas->id_guru ?? null);
                        $namaGuruModal = $guruDataModal->nama ?? $tugas->nama_guru ?? 'Pengajar Mata Pelajaran';
                        @endphp
                        <span class="fw-semibold text-dark small"><i class="bi bi-person-badge me-1"></i> {{ $namaGuruModal }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block" style="font-size: 10px;">TENGGAT WAKTU</small>
                        <span class="fw-semibold text-danger small"><i class="bi bi-clock me-1"></i> {{ $tugas->tenggat_waktu ?? '2026-11-10 23:59' }}</span>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block" style="font-size: 10px;">BOBOT POIN</small>
                        <span class="fw-semibold text-success small"><i class="bi bi-award me-1"></i> 100 Poin Maks.</span>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="fw-bold text-dark fs-6 mb-2"><i class="bi bi-journal-text text-success me-1"></i> Instruksi & Kriteria Penilaian</h6>
                    <p class="text-secondary small mb-3">
                        {{ $tugas->deskripsi_tugas ?? 'Silakan selesaikan tugas ini sesuai dengan instruksi yang diberikan pengajar dan unggah berkas pengerjaan Anda.' }}
                    </p>
                </div>

                <!-- Form Upload ke Admin -->
                <form action="{{ route('siswa.upload', $tugas->id_tugas) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold text-dark fs-6 mb-0"><i class="bi bi-cloud-upload text-success me-1"></i> Kirim Berkas Pengerjaan</h6>
                        <small class="text-muted" style="font-size: 11px;">Formulir Submisi Mandiri</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Komentar Siswa (Opsional) <span class="float-end">Maks. 500 Karakter</span></label>
                        <textarea name="komentar" class="form-control form-control-sm" rows="2" placeholder="Tuliskan catatan mengenai tugas yang dikumpulkan..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted fw-bold">Unggah Laporan / Berkas Arsip (.ZIP / .PDF / Gambar) * <span class="float-end text-muted fw-normal">Maks. 25 MB</span></label>
                        <div class="border border-2 border-dashed rounded-3 p-4 text-center bg-light position-relative upload-drop-zone">
                            <i class="bi bi-cloud-arrow-up fs-2 text-success mb-2 d-block"></i>
                            <span class="text-dark small fw-semibold file-label-text">Tarik & lepas file ke sini, atau <label for="fileUpload-{{ $tugas->id_tugas }}" class="text-success text-decoration-underline" style="cursor: pointer;">pilih dari perangkat</label></span>
                            <input type="file" id="fileUpload-{{ $tugas->id_tugas }}" name="berkas" class="d-none file-input-hidden" accept=".zip,.rar,.pdf,.jpg,.jpeg,.png" required>
                        </div>
                    </div>

                    <div class="modal-footer px-0 pb-0 pt-3 border-top d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light btn-sm px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success btn-sm px-4 rounded-pill" style="background-color: #0d8a4e;">
                            <i class="bi bi-send me-1"></i> Kirim Tugas Sekarang
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>
@endforeach

<!-- Script Interaktif Drag & Drop dan File Selector -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.upload-drop-zone').forEach(dropZone => {
        const fileInput = dropZone.querySelector('.file-input-hidden');
        const textSpan = dropZone.querySelector('.file-label-text');

        // Ketika file dipilih lewat tombol "pilih dari perangkat"
        fileInput.addEventListener('change', function () {
            if (this.files && this.files.length > 0) {
                textSpan.innerHTML = `<i class="bi bi-file-earmark-check text-success me-1"></i> File dipilih: <strong>${this.files[0].name}</strong>`;
            }
        });

        // Mencegah default behavior saat file ditarik di atas zona
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropZone.classList.add('bg-success-subtle', 'border-success');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                dropZone.classList.remove('bg-success-subtle', 'border-success');
            }, false);
        });

        // Menangani aksi saat file dilepas (dropped) ke dalam kotak
        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                fileInput.files = files;
                textSpan.innerHTML = `<i class="bi bi-file-earmark-check text-success me-1"></i> File dipilih: <strong>${files[0].name}</strong>`;
            }
        });
    });
});
</script>

@endsection