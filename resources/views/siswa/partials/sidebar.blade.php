<aside class="siswa-sidebar">
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
            <a href="{{ route('siswa.dashboard') }}" class="nav-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
                <i class="bi bi-journal-richtext"></i> Tugas Saya
            </a>
        </li>
        <li>
            <a href="{{ route('siswa.nilai') }}" class="nav-link {{ request()->routeIs('siswa.nilai') ? 'active' : '' }}">
                <i class="bi bi-star"></i> Nilai Tugas
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST" class="logout-form"
              onsubmit="return confirm('Yakin ingin keluar dari akun?')">
            @csrf
            <button type="submit" class="btn-logout w-100 justify-content-center">
                <i class="bi bi-box-arrow-right"></i> Keluar Akun
            </button>
        </form>
    </div>
</aside>