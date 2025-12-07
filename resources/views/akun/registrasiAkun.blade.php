<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jalan2kuy.id - Buat Akun Baru</title>
    <link rel="stylesheet" href="{{ asset('css/akun/register.css') }}">
    
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
            color: #757575; /* Agar mirip placeholder */
        }
    </style>
</head>
<body>

<header class="navbar">
    <button class="back-btn" id="backButton" type="button">
        <img src="{{ asset('assets/gambar/icon/return.png') }}" alt="kembali">
    </button>
    <div class="logo">
        <img src="{{ asset('assets/gambar/icon/logo.png') }}" alt="Logo jalan2kuy.id">
    </div>
</header>

<div class="form-container">
    <h1>Buat Akun Baru</h1>

    @if(session('error'))
        <div style="background-color: #ffcccc; color: red; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
            {{ session('error') }}
        </div>
    @endif

    <form id="registerForm" action="{{ url('/register-proses') }}" method="POST">
        @csrf

        <input type="text" name="name" placeholder="Nama Lengkap" required value="{{ old('name') }}">
        @error('name') <span class="error-msg">{{ $message }}</span> @enderror

        <input type="email" name="email" placeholder="Email" required value="{{ old('email') }}">
        @error('email') <span class="error-msg">{{ $message }}</span> @enderror

        <input type="text" name="username" placeholder="Username" required value="{{ old('username') }}">
        @error('username') <span class="error-msg">{{ $message }}</span> @enderror

        <select name="gender" required>
            <option value="" disabled selected>Pilih Jenis Kelamin</option>
            <option value="1" {{ old('gender') == '1' ? 'selected' : '' }}>Laki-laki</option>
            <option value="0" {{ old('gender') == '0' ? 'selected' : '' }}>Perempuan</option>
        </select>
        @error('gender') <span class="error-msg">{{ $message }}</span> @enderror

        <input type="password" name="password" placeholder="Kata Sandi" required>
        @error('password') <span class="error-msg">{{ $message }}</span> @enderror

        <input type="password" name="password_confirmation" placeholder="Konfirmasi Kata Sandi" required>

        <button type="submit">Daftar</button>
    </form>
</div>

<script>
    // Tombol kembali
    document.getElementById('backButton').addEventListener('click', function () {
        // Menggunakan URL Laravel agar lebih aman
        window.location.href = "{{ url('/login') }}";
    });

    // NOTE: Script submit event listener dihapus.
    // Biarkan form melakukan submit secara alami (POST) ke server Laravel.
    // Jangan di-prevent default, karena kita butuh data dikirim ke Controller.
</script>

</body>
</html>