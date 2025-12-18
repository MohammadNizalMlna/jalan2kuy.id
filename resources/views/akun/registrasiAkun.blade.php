<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jalan2kuy.id - Buat Akun Baru</title>
    <link rel="stylesheet" href="{{ asset('css/akun/register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .error-msg {
            color: #ff4d4d;
            font-size: 12px;
            text-align: left;
            margin-top: -10px;
            margin-bottom: 10px;
            display: block;
        }
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background: #fff;
            color: #757575;
        }
        
        /* 2. Tambahkan CSS untuk posisi ikon mata */
        .password-container {
            position: relative;
            width: 100%;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%; /* Sesuaikan agar pas di tengah vertikal input */
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
        }
    </style>
</head>
<body>

<!-- Navbar berisi tombol kembali ke halaman login dan logo -->
<header class="navbar">
    <button class="back-btn" id="backButton" type="button">
        <img src="{{ asset('assets/gambar/icon/return.png') }}" alt="kembali">
    </button>
    <div class="logo">
        <img src="{{ asset('assets/gambar/icon/logo.png') }}" alt="Logo jalan2kuy.id">
    </div>
</header>

<!-- Container utama untuk form pembuatan akun baru -->
<div class="form-container">
    <h1>Buat Akun Baru</h1>

    <!-- Menampilkan pesan error dari session Laravel -->
    @if(session('error'))
        <div style="background-color: #ffcccc; color: red; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form registrasi akun admin baru -->
    <form id="registerForm" action="{{ url('/register-proses') }}" method="POST">
        @csrf

        <!-- input nama lengkap -->
        <input type="text" name="name" placeholder="Nama Lengkap" required value="{{ old('name') }}">
        @error('name') <span class="error-msg">{{ $message }}</span> @enderror

        <!-- Input email -->
        <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
        @error('email') <span class="error-msg">{{ $message }}</span> @enderror

        <!-- Input username -->
        <input type="text" name="username" placeholder="Username" required value="{{ old('username') }}">
        @error('username') <span class="error-msg">{{ $message }}</span> @enderror

        <!-- Dropdown untuk memilih jenis kelamin pengguna -->
        <select name="gender" required>
            <option value="" disabled selected>Pilih Jenis Kelamin</option>
            <option value="1" {{ old('gender') == '1' ? 'selected' : '' }}>Laki-laki</option>
            <option value="0" {{ old('gender') == '0' ? 'selected' : '' }}>Perempuan</option>
        </select>
        @error('gender') <span class="error-msg">{{ $message }}</span> @enderror

        <!-- Input password dengan fitur show/hide -->
        <div class="password-container">
            <input type="password" name="password" id="password" placeholder="Kata Sandi" required>
            <i class="fa fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
        </div>
        @error('password') <span class="error-msg">{{ $message }}</span> @enderror

        <!-- Input konfirmasi password dengan toggle -->
        <div class="password-container" style="margin-top: 15px; margin-bottom: 15px;">
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Kata Sandi" required>
            <i class="fa fa-eye toggle-password" onclick="togglePassword('password_confirmation', this)"></i>
        </div>

        <!-- Tombol submit registrasi -->
        <button type="submit">Daftar</button>
    </form>
</div>

<script>
    // Tombol kembali
    document.getElementById('backButton').addEventListener('click', function () {
        window.location.href = "{{ url('/login') }}";
    });

    // 4. Tambahkan Script Toggle Password
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash"); // Ganti ikon jadi mata dicoret
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye"); // Kembali ke ikon mata biasa
        }
    }
</script>

</body>
</html>