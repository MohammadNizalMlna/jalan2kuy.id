<style>
    *, *::before, *::after { box-sizing: border-box; }

    /* Note: Body margin dihapus dari sini karena sudah ada di homepage utama */
    
    .navbar {
        background-color: #15514A;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 40px;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1000;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    .logo img {
        height: 55px;
        width: auto;
        display: block;
        filter: brightness(0) invert(1);
    }

    .nav-links { display: flex; gap: 45px; }

    .nav-links a, .account a {
        color: #fff;
        text-decoration: none;
        font-size: 20px;
        font-weight: 500;
        transition: 0.3s ease;
    }

    .nav-links a:hover, .account a:hover { color: #b7f7d8; }

    /* ICON BASE - Menggunakan asset() agar jalur gambar benar */
    .home, .destinasi, .event, .gallery, .akun {
        width: 18px;
        height: 18px;
        display: inline-block;
        background-size: cover;
        background-position: center;
    }

    /* Perhatikan penggunaan asset() di dalam url CSS */
    .home { background-image: url('{{ asset("assets/gambar/icon/home.png") }}'); }
    .destinasi { background-image: url('{{ asset("assets/gambar/icon/destinasi.png") }}'); }
    .event { background-image: url('{{ asset("assets/gambar/icon/event.png") }}'); }
    .gallery { background-image: url('{{ asset("assets/gambar/icon/gallery.png") }}'); }
    .akun { background-image: url('{{ asset("assets/gambar/icon/account.png") }}'); }

    /* RESPONSIVE */
    @media (max-width: 900px) {
        .nav-links { gap: 25px; }
        .nav-links a, .account a { font-size: 18px; }
    }

    @media (max-width: 700px) {
        .navbar { padding: 10px 20px; }
        .logo img { height: 45px; }
        .nav-links { gap: 15px; }
        .nav-links a, .account a { font-size: 16px; }
    }

    @media (max-width: 550px) {
        .navbar { flex-direction: column; gap: 10px; padding: 12px; }
        .nav-links { order: 3; flex-wrap: wrap; justify-content: center; gap: 18px; }
    }
</style>

<nav class="navbar">

    <div class="logo">
        <a href="{{ url('/') }}">
            <img src="{{ asset('assets/gambar/icon/logo.png') }}" alt="jalan2kuy.id logo">
        </a>
    </div>

    <div class="nav-links">
        <a href="{{ url('/') }}"><i class="home"></i> Home</a>
        <a href="{{ url('/Destination') }}"><i class="destinasi"></i> Destination</a>
        <a href="{{ url('/Event') }}"><i class="event"></i> Event</a>
        <a href="{{ url('/Gallery') }}"><i class="gallery"></i> Gallery</a>
    </div>

    <div class="account">
        <a href="{{ url('/login') }}"><i class="akun"></i> Account</a>
    </div>

</nav>