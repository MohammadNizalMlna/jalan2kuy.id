<style>

    *, *::before, *::after {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: 'Poppins', Arial, sans-serif;
        background-color: #f4f4f9;
        padding-top: 85px;
    }

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

    .nav-links {
        display: flex;
        gap: 45px;
    }

    .nav-links a {
        color: white;
        text-decoration: none;
        font-size: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s ease;
    }

    .nav-links a:hover {
        color: #b7f7d8;
    }

    .admin a {
        color: white;
        text-decoration: none;
        font-size: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: 0.3s ease;
    }

    .admin a:hover {
        color: #b7f7d8;
    }

    .home, .destinasi, .event, .gallery, .akun {
        width: 18px;
        height: 18px;
        display: inline-block;
        background-size: cover;
    }

    .home { background-image: url('{{ asset("/assets/gambar/icon/home.png") }}'); }
    .destinasi { background-image: url('{{ asset("/assets/gambar/icon/destinasi.png") }}'); }
    .event { background-image: url('{{ asset("/assets/gambar/icon/event.png") }}'); }
    .gallery { background-image: url('{{ asset("/assets/gambar/icon/gallery.png") }}'); }
    .akun { background-image: url('{{ asset("/assets/gambar/icon/account.png") }}'); }


    @media (max-width: 900px) {
        .nav-links { gap: 30px; }
        .nav-links a, .admin a { font-size: 18px; }
    }

    @media (max-width: 700px) {
        .navbar {
            padding: 10px 20px;
        }
        .logo img { height: 45px; }
        .nav-links { gap: 20px; }
        .nav-links a, .admin a { font-size: 16px; }
    }

    @media (max-width: 550px) {
        body { padding-top: 130px; }

        .navbar {
            flex-direction: column;
            gap: 12px;
        }

        .nav-links {
            order: 3;
            flex-wrap: wrap;
            justify-content: center;
        }
    }

</style>
</head>

<body>

<nav class="navbar">

    <div class="logo">
        <a href="{{ url('/Homepage-Admin') }}">
            <img src="{{ asset('assets/gambar/icon/logo.png') }}" alt="jalan2kuy.id logo">
        </a>
    </div>

    <div class="nav-links">
        <a href="{{ url('/admin/Homepage') }}"><i class="home"></i> Home</a>
        <a href="{{ url('/admin/Destination') }}"><i class="destinasi"></i> Destination</a>
        <a href="{{ url('/admin/Event') }}"><i class="event"></i> Event</a>
        <a href="{{ url('/Gallery') }}"><i class="gallery"></i> Gallery</a>
    </div>

    <div class="admin">
        <a href="{{ url('/admin/Account') }}"><i class="akun"></i> Admin</a>
    </div>

</nav>

</body>
</html>
