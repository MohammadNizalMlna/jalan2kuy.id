<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jalan2kuy.id - Login</title>
    
    <link rel="stylesheet" href="{{ asset('css/akun/login.css') }}">
</head>
<body>

<div class="container">
    <header>
        <button class="back-btn" id="backButton" type="button">
            <img src="{{ asset('assets/gambar/icon/return.png') }}" alt="kembali">
        </button>
        <div class="icon">
            <img src="{{ asset('assets/gambar/icon/acount.png') }}" alt="akun">
            <h1>ACCOUNT</h1>
        </div>
    </header>

    <main class="login-box">
        
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

        <form action="{{ url('/login-proses') }}" method="POST">
            
            @csrf

            <input type="text" name="username" id="username" placeholder="Username" required value="{{ old('username') }}">

            <input type="password" name="password" id="password" placeholder="Password" required>

            <button type="submit" class="btn-primary" id="loginButton">Masuk</button>
        </form>

        <hr>

        <a href="{{ url('/register') }}" class="btn-secondary" style="text-decoration: none; display: block; text-align: center; line-height: normal;">
            Buat Akun Baru
        </a>
        
        <p class="note">*Login hanya untuk admin jalan2kuy.id</p>
    </main>
</div>

<script>
    // Logika tombol kembali tetap dipertahankan
    document.getElementById('backButton').addEventListener('click', function() {
        if (document.referrer) {
            window.history.back();
        } else {
            // Mengembalikan ke homepage utama Laravel
            window.location.href = "{{ url('/') }}";
        }
    });

    // Note: Script validasi login JS dihapus karena sudah digantikan oleh validasi HTML (required) 
    // dan validasi Server-side (Laravel Controller).
</script>

</body>
</html>