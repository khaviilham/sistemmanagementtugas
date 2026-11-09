<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Manajemen Tugas</title>
    <!-- Memanggil file style.css dari folder public/css/style.css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Bootstrap Icons untuk ikon amplop, kunci, dan mata -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>

<body class="auth-page">

    <div class="login-container">
        <div class="login-card">

            <!-- Logo / Gambar Brand Todoist -->
            <div class="text-center mb-3">
                <img src="{{ asset('img/todoist.png') }}" alt="Logo Todoist" style="max-height: 45px; width: auto;">
            </div>

            <!-- Judul & Subjudul -->
            <h2 class="login-title">Masuk ke Todoist</h2>
            <p class="login-subtitle">Platform Manajemen Proyek & Tugas Akademik Terpadu</p>

            <!-- Notifikasi Error jika login gagal -->
            @if($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
            @endif

            <!-- Form Login -->
            <form action="/login" method="POST">
                @csrf

                <!-- Alamat Email -->
                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="bi bi-envelope"></i></span>
                        <input type="email" id="email" name="email" placeholder="" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <!-- Kata Sandi -->
                <div class="form-group">
                    <label for="kata_sandi">Kata Sandi</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="bi bi-lock"></i></span>
                        <input type="password" id="kata_sandi" name="kata_sandi" placeholder="••••••••" required>
                        <button type="button" id="togglePassword" class="btn-toggle-password">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Tombol Masuk -->
                <button type="submit" class="btn-submit">
                    Masuk <i class="bi bi-arrow-right"></i>
                </button>
            </form>

        </div>
    </div>

    <!-- Script JavaScript untuk Toggle Lihat/Sembunyikan Sandi -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#kata_sandi'); // Disesuaikan dengan id="kata_sandi"
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    </script>
</body>

</html>