@extends('layouts.app')

@section('title', 'Kelola Proyek & Tugas Akademik')

@section('content')
<div class="admin-dashboard-wrapper">

    <!-- SIDEBAR KIRI -->
    @include('admin.partials.sidebar')

    <!-- KONTEN UTAMA KANAN -->
    <main class="admin-main-content">

        <!-- Header Topbar -->
        <header class="topbar-header">
            <div class="topbar-left">
                <span class="text-muted small"><i class="bi bi-book"></i> Sistem Management Tugas</span>
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
            </div>
        </header>

        <!-- Alert Sukses & Error -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show alert-flash" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show alert-flash" role="alert">
            <strong><i class="bi bi-exclamation-triangle-fill me-1"></i> Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1 ps-3">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Judul & Deskripsi Halaman -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="badge-semester mb-1">MANAJEMEN KURIKULUM & ASESMEN</span>
                <h1 class="welcome-title fs-3 mb-1">Kelola Proyek & Tugas Akademik</h1>
                <p class="text-muted small mb-0">Atur proyek kolaboratif, delegasikan tugas harian kepada individu siswa, dan pantau progres submission secara terpadu.</p>
            </div>
            <!-- Tombol Aksi (Tambah Tugas & Tambah Proyek) -->
            <div class="d-flex gap-2 flex-shrink-0">
                <button class="btn-create-task" data-bs-toggle="modal" data-bs-target="#modalTambahTugas">
                    <i class="bi bi-plus-lg"></i> Tambah Tugas
                </button>
                <button class="btn-create-task" data-bs-toggle="modal" data-bs-target="#modalTambahProyek">
                    <i class="bi bi-plus-lg"></i> Tambah Proyek
                </button>
            </div>
        </div>

        <!-- Bagian Proyek Aktif Semester Ini -->
        <section class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="section-heading mb-0">Proyek Aktif Semester Ini <span class="badge bg-light text-secondary border fw-normal ms-1">{{ count($proyekList) }} Utama</span></h5>
                <span class="text-muted small">Terakhir diperbarui: Hari ini, 08:30 WIB</span>
            </div>

            <div class="row g-3">
                @forelse($proyekList as $proyek)
                @php
                    $isSelected = ($selectedProyek && $selectedProyek->id_proyek == $proyek->id_proyek);
                @endphp
                <div class="col-md-6">
                    <div class="card project-card p-3 border {{ $isSelected ? 'border-success bg-white shadow-sm' : 'border-light bg-light' }}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-secondary-subtle text-dark fw-semibold mb-1" style="font-size: 10px;">Proyek Aktif</span>
                                <h5 class="fw-bold text-dark mb-1">{{ $proyek->judul_proyek }}</h5>
                            </div>
                            @if($isSelected)
                            <span class="badge bg-success text-white px-2 py-1" style="font-size: 11px;">Sedang Dipilih</span>
                            @else
                            <a href="{{ route('admin.proyek.index', ['proyek_id' => $proyek->id_proyek]) }}" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 11px;">Lihat Detail</a>
                            @endif
                        </div>
                        <p class="text-muted small mb-3 text-truncate">{{ $proyek->deskripsi_proyek ?? 'Tidak ada deskripsi.' }}</p>
                        <div class="text-muted" style="font-size: 12px;">
                            Total Penugasan: <strong>{{ $proyek->tugas->count() }} Tugas</strong>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="card p-4 text-center border-0 shadow-sm">
                        <p class="text-muted mb-0">Belum ada data proyek aktif di database.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </section>

        <!-- Bagian Daftar Tugas dalam Proyek yang Dipilih -->
        @if($selectedProyek)
        <section class="tasks-review-section p-4 bg-white rounded shadow-sm border">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <span class="badge bg-purple-soft text-purple mb-1" style="font-size: 10px;">FOKUS PROYEK AKTIF</span>
                    <h5 class="fw-bold text-dark mb-1">{{ $selectedProyek->judul_proyek }}</h5>
                    <p class="text-muted small mb-0">{{ $selectedProyek->deskripsi_proyek }}</p>
                </div>
                <button class="btn-create-task" data-bs-toggle="modal" data-bs-target="#modalTambahTugas">
                    <i class="bi bi-plus-lg"></i> Tambah Tugas
                </button>
            </div>

            <hr class="text-muted opacity-25 my-3">

            <!-- Loop Daftar Tugas -->
            <div class="d-flex flex-column gap-3">
                @forelse($tugasList as $index => $tugas)
                <div class="task-item p-3 border rounded-3 bg-light-subtle">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge bg-primary-subtle text-primary fw-bold mb-1" style="font-size: 10px;">Tugas 0{{ $index + 1 }}</span>
                            <h6 class="fw-bold text-dark mb-1">{{ $tugas->judul_tugas }}</h6>
                            <small class="text-muted">
                                <i class="bi bi-person"></i> {{ $tugas->siswa->nama ?? 'Belum ditugaskan' }}
                            </small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-light text-muted border mb-1" style="font-size: 11px;">
                                <i class="bi bi-clock"></i> Tenggat: {{ $tugas->tenggat_waktu ?? 'Tidak ditentukan' }}
                            </span>
                        </div>
                    </div>
                    <p class="text-muted small mb-3">{{ $tugas->deskripsi_tugas }}</p>

                    <!-- Statistik Submission & Tombol Aksi -->
                    <div class="task-action-bar">
                        <div class="task-stats">
                            <div>
                                <small class="text-muted d-block" style="font-size: 10px;">Sudah Kumpul</small>
                                <span class="fw-bold text-success" style="font-size: 13px;">{{ $tugas->status_tugas == 'selesai' ? '1' : '0' }} Siswa</span>
                            </div>
                            <div class="task-stats-divider">
                                <small class="text-muted d-block" style="font-size: 10px;">Status</small>
                                <span class="fw-bold text-primary" style="font-size: 13px;">{{ ucfirst(str_replace('_', ' ', $tugas->status_tugas ?? 'Aktif')) }}</span>
                            </div>
                        </div>

                        <div class="task-actions">
                            <a href="{{ route('admin.penilaian.show', $tugas->id_tugas) }}" class="btn-action-grade">
                                Beri Nilai <i class="bi bi-pencil-square"></i>
                            </a>

                            <!-- Tombol Edit -->
                            <button type="button" class="btn-action-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditTugas{{ $tugas->id_tugas }}">
                                <i class="bi bi-pencil"></i> Edit
                            </button>

                            <!-- Tombol Hapus -->
                            <form action="{{ route('admin.tugas.destroy', $tugas->id_tugas) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus tugas \'{{ addslashes($tugas->judul_tugas) }}\'? Tindakan ini tidak dapat dibatalkan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-delete">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-journal-x fs-2 d-block mb-1"></i>
                    Belum ada daftar tugas untuk proyek ini. Silakan klik tombol <strong>Tambah Tugas</strong> di atas.
                </div>
                @endforelse
            </div>
        </section>

        <!-- MODAL EDIT TUGAS (satu per tugas)          -->

        @foreach($tugasList as $tugas)
        @php
            $tenggat = $tugas->tenggat_waktu ? \Carbon\Carbon::parse($tugas->tenggat_waktu) : null;
        @endphp
        <div class="modal fade" id="modalEditTugas{{ $tugas->id_tugas }}" tabindex="-1" aria-labelledby="modalEditTugasLabel{{ $tugas->id_tugas }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">

                    <div class="modal-header">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary-subtle text-primary p-2 rounded-3 fs-5">
                                <i class="bi bi-pencil-square"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold text-dark mb-0" id="modalEditTugasLabel{{ $tugas->id_tugas }}">Edit Tugas</h5>
                                <p class="text-muted small mb-0">Perbarui detail tugas yang sudah dibuat</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('admin.tugas.update', $tugas->id_tugas) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body px-4 py-3">

                            <div class="mb-3">
                                <label class="form-label">Judul Tugas *</label>
                                <input type="text" class="form-control" name="judul_tugas" value="{{ $tugas->judul_tugas }}" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Pilih Proyek Terkait *</label>
                                    <select class="form-select" name="id_proyek" required>
                                        @foreach($proyekList as $p)
                                        <option value="{{ $p->id_proyek }}" {{ $tugas->id_proyek == $p->id_proyek ? 'selected' : '' }}>
                                            {{ $p->judul_proyek }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ditugaskan Kepada Siswa *</label>
                                    <select class="form-select" name="ditugaskan_kepada" required>
                                        @foreach($siswaList as $siswa)
                                        <option value="{{ $siswa->id_pengguna }}" {{ $tugas->ditugaskan_kepada == $siswa->id_pengguna ? 'selected' : '' }}>
                                            {{ $siswa->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi & Instruksi Pengerjaan *</label>
                                <textarea class="form-control" name="deskripsi_tugas" rows="4" required>{{ $tugas->deskripsi_tugas }}</textarea>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-6">
                                    <label class="form-label">Tenggat Tanggal *</label>
                                    <input type="date" class="form-control" name="tanggal_deadline"
                                           value="{{ $tenggat ? $tenggat->format('Y-m-d') : '' }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Jam Deadline *</label>
                                    <input type="time" class="form-control" name="jam_deadline"
                                           value="{{ $tenggat ? $tenggat->format('H:i') : '23:59' }}" required>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill small fw-semibold" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill small fw-semibold d-flex align-items-center gap-2">
                                <i class="bi bi-check-lg"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        @endforeach
        @endif

    </main>
</div>

<!-- MODAL POP-UP TAMBAH TUGAS BARU -->
<div class="modal fade" id="modalTambahTugas" tabindex="-1" aria-labelledby="modalTambahTugasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success-subtle text-success p-2 rounded-3 fs-5">
                        <i class="bi bi-journal-plus"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="modalTambahTugasLabel">Tambah Tugas Perorangan</h5>
                        <p class="text-muted small mb-0">Delegasikan penugasan mandiri ke siswa tertentu dalam proyek aktif</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.tugas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4 py-3">

                    <div class="mb-3">
                        <label for="judul_tugas" class="form-label">Judul Tugas *</label>
                        <input type="text" class="form-control" id="judul_tugas" name="judul_tugas" placeholder="Contoh: Pembuatan Desain UI/UX Landing Page" required>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="id_proyek" class="form-label">Pilih Proyek Terkait *</label>
                            <select class="form-select" id="id_proyek" name="id_proyek" required>
                                <option value="" selected disabled>Pilih proyek...</option>
                                @foreach($proyekList as $p)
                                <option value="{{ $p->id_proyek }}" {{ (isset($selectedProyek) && $selectedProyek && $selectedProyek->id_proyek == $p->id_proyek) ? 'selected' : '' }}>
                                    {{ $p->judul_proyek }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="ditugaskan_kepada" class="form-label">Ditugaskan Kepada Siswa *</label>
                            <select class="form-select" id="ditugaskan_kepada" name="ditugaskan_kepada" required>
                                <option value="" selected disabled>Pilih Siswa</option>
                                @if(isset($siswaList))
                                @foreach($siswaList as $siswa)
                                <option value="{{ $siswa->id_pengguna }}">{{ $siswa->nama }}</option>
                                @endforeach
                                @endif
                            </select>   
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi_tugas" class="form-label">Deskripsi & Instruksi Pengerjaan *</label>
                        <textarea class="form-control" id="deskripsi_tugas" name="deskripsi_tugas" rows="4" placeholder="Jelaskan detail instruksi, ketentuan file, dan panduan pengerjaan tugas di sini..." required></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tanggal_deadline" class="form-label">Tenggat Tanggal *</label>
                            <div class="input-group bg-light rounded overflow-hidden">
                                <input type="date" class="form-control" id="tanggal_deadline" name="tanggal_deadline" required>
                                <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="jam_deadline" class="form-label">Jam Deadline *</label>
                            <div class="input-group bg-light rounded overflow-hidden">
                                <input type="time" class="form-control" id="jam_deadline" name="jam_deadline" value="23:59" required>
                                <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-clock"></i></span>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="mb-2">
                        <label for="lampiran" class="form-label">Lampiran Rubrik / Materi Panduan (Opsional)</label>
                        <input type="file" class="form-control form-control-sm" id="lampiran" name="lampiran">
                        <small class="text-muted" style="font-size: 11px;">Format yang didukung: PDF, DOCX, ZIP, Max 10MB.</small>
                    </div> -->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill small fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4 py-2 rounded-pill small fw-semibold d-flex align-items-center gap-2">
                        <i class="bi bi-play-fill"></i> Simpan & Terbitkan Tugas
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- MODAL POP-UP TAMBAH PROYEK BARU -->
<div class="modal fade" id="modalTambahProyek" tabindex="-1" aria-labelledby="modalTambahProyekLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success-subtle text-success p-2 rounded-3 fs-5">
                        <i class="bi bi-folder-plus"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="modalTambahProyekLabel">Buat Proyek Akademik Baru</h5>
                        <p class="text-muted small mb-0">Buat wadah proyek utama sebelum mendelegasikan tugas-tugas di dalamnya</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.proyek.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4 py-3">

                    <div class="mb-3">
                        <label for="judul_proyek" class="form-label">Judul Proyek *</label>
                        <input type="text" class="form-control" id="judul_proyek" name="judul_proyek" placeholder="Contoh: Pengembangan Aplikasi Web Portofolio Mandiri" required>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi_proyek" class="form-label">Deskripsi Proyek & Tujuan *</label>
                        <textarea class="form-control" id="deskripsi_proyek" name="deskripsi_proyek" rows="4" placeholder="Jelaskan ringkasan dan capaian dari proyek ini..." required></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai Proyek *</label>
                            <div class="input-group bg-light rounded overflow-hidden">
                                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                                <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai Proyek *</label>
                            <div class="input-group bg-light rounded overflow-hidden">
                                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                                <span class="input-group-text bg-transparent border-0 text-muted"><i class="bi bi-calendar"></i></span>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="mb-2">
                        <label for="lampiran_proyek" class="form-label">Lampiran Panduan / Kerangka Acuan Proyek (Opsional)</label>
                        <input type="file" class="form-control form-control-sm" id="lampiran_proyek" name="lampiran_proyek">
                        <small class="text-muted" style="font-size: 11px;">Format yang didukung: PDF, DOCX, ZIP, Max 10MB.</small>
                    </div> -->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2 rounded-pill small fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4 py-2 rounded-pill small fw-semibold d-flex align-items-center gap-2">
                        <i class="bi bi-check-lg"></i> Simpan Proyek Baru
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection