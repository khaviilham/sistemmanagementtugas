<aside class="admin-sidebar">
    <div class="sidebar-brand">
        <h4 class="brand-title">Todoist</h4>
        <span class="brand-subtitle">Sistem Manajemen Tugas</span>
    </div>

    <div class="sidebar-badge-role">
        <i class="bi bi-person-badge"></i> Guru
    </div>

    <div class="nav-section-title">NAVIGASI GURU</div>
    <ul class="sidebar-nav">
        <li>
            <a href="/admin/dashboard" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard Guru
            </a>
        </li>
        <li>
            <a href="/admin/proyek" class="nav-link {{ request()->is('admin/proyek*') ? 'active' : '' }}">
                <i class="bi bi-folder2-open"></i> Proyek & Tugas
            </a>
        </li>
        <li>
            <a href="/admin/penilaian" class="nav-link {{ request()->is('admin/penilaian*') ? 'active' : '' }}">
                <i class="bi bi-eye"></i> Pengumpulan & Penilaian
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <div class="academic-year">
            <i class="bi bi-shield-check"></i>
            <div>
                <strong>Tahun Ajaran 2026/2027</strong>
                <small>Semester Ganjil</small>
            </div>
        </div>
    </div>
</aside>