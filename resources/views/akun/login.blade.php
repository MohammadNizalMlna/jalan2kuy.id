<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jalan2kuy.id - Login</title>
    
    <link rel="stylesheet" href="{{ asset('css/akun/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Style tambahan untuk toggle show/hide password -->
    <style>
        .password-wrapper { position: relative; width: 100%; }
        .toggle-pass-login {
            position: absolute;
            right: 15px;
            top: 35%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #777;
        }
    </style>
</head>
<body>

<!-- Wrapper utama halaman login -->
<div class="container">
    <!-- Header berisi tombol kembali dan ikon akun -->
    <header>
        <button class="back-btn" id="backButton" type="button">
            <img src="{{ asset('assets/gambar/icon/return.png') }}" alt="kembali">
        </button>
        <div class="icon">
            <img src="{{ asset('assets/gambar/icon/acount.png') }}" alt="akun">
            <h1>ACCOUNT</h1>
        </div>
    </header>
    
    <!-- Area utama form login admin -->
    <main class="login-box">
        
        <!-- Menampilkan pesan error atau sukses dari session Laravel -->
        @if(session('error'))
            <div style="color: red; text-align: center; margin-bottom: 10px; font-size: 14px;">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div style="color: green; text-align: center; margin-bottom: 10px; font-size: 14px;">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form login admin menggunakan username dan password -->
        <form action="{{ url('/login-proses') }}" method="POST">
            @csrf
            <!-- Input username -->
            <input type="text" name="username" id="username" placeholder="Username" required value="{{ old('username') }}">
            
            <!-- Input password dengan fitur show/hide -->
            <div class="password-wrapper">
                <input type="password" name="password" id="passwordLogin" placeholder="Password" required style="width: 100%;">
                <i class="fa fa-eye toggle-pass-login" onclick="togglePasswordLogin()"></i>
            </div>

            <!-- Tombol login -->
            <button type="submit" class="btn-primary" id="loginButton">Masuk</button>
        </form>

        <hr>

        <!-- Tombol menuju halaman pendaftaran akun baru -->
        <a href="{{ url('/register') }}" class="btn-secondary" style="text-decoration: none; display: block; text-align: center; line-height: normal;">
            Buat Akun Baru
        </a>
        
        <!-- Catatan khusus -->
        <p class="note">*Login hanya untuk admin jalan2kuy.id</p>
    </main>
</div>

<script>
    // Logika tombol kembali tetap dipertahankan
// Langsung arahkan ke homepage ("/") saat tombol diklik
    document.getElementById('backButton').addEventListener('click', function() {
        window.location.href = "{{ url('/') }}";
    });

    function togglePasswordLogin() {
        const passInput = document.getElementById('passwordLogin');
        const icon = document.querySelector('.toggle-pass-login');
        if (passInput.type === "password") {
            passInput.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            passInput.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>

</body>
</html>