<style>
*,
*::before,
*::after {
    box-sizing: border-box;
}

html, body {
    margin: 0;
    padding: 0;
}

/* ================= BODY ================= */
body {
    font-family: 'Poppins', Arial, sans-serif;
    background-color: #15514A;  
    padding-top: 85px;           
}

/* ================= NAVBAR ================= */
.navbar {
    background-color: #15514A;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 40px;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 85px;                
    z-index: 1000;

    /* 🔥 HILANGKAN GARIS */
    box-shadow: none;
    border: none;
}

/* ================= LOGO ================= */
.logo img {
    height: 55px;
    width: auto;
    display: block;
    filter: brightness(0) invert(1);
}

/* ================= NAV LINKS ================= */
.nav-links {
    display: flex;
    gap: 45px;
}

.nav-links a,
.admin a {
    color: white;
    text-decoration: none;
    font-size: 20px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: color 0.3s ease;
}

.nav-links a:hover,
.admin a:hover {
    color: #b7f7d8;
}

/* ================= ICONS ================= */
.home, .destinasi, .event, .gallery, .akun {
    width: 18px;
    height: 18px;
    display: inline-block;
    background-size: cover;
}

/* ================= RESPONSIVE ================= */
@media (max-width: 900px) {
    .nav-links { gap: 30px; }
    .nav-links a, .admin a { font-size: 18px; }
}

@media (max-width: 700px) {
    .navbar {
        padding: 10px 20px;
        height: 75px;
    }

    body {
        padding-top: 75px;
    }

    .logo img { height: 45px; }
    .nav-links { gap: 20px; }
    .nav-links a, .admin a { font-size: 16px; }
}

@media (max-width: 550px) {
    body {
        padding-top: 130px;
    }

    .navbar {
        height: auto;
        flex-direction: column;
        gap: 12px;
        padding: 12px 20px;
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
        <a href="{{ url('/admin/Homepage') }}">
            <img src="{{ asset('assets/gambar/icon/logo.png') }}" alt="jalan2kuy.id logo">
        </a>
    </div>

    <div class="nav-links">
        <a href="{{ url('/admin/Homepage') }}"><i class="home"></i> Home</a>
        <a href="{{ url('/admin/Destination') }}"><i class="destinasi"></i> Destination</a>
        <a href="{{ url('/admin/Event') }}"><i class="event"></i> Event</a>
        <a href="{{ url('/admin/Gallery') }}"><i class="gallery"></i> Gallery</a>
    </div>

    <div class="admin">
        <a href="{{ url('/admin/Account') }}"><i class="akun"></i> Admin</a>
    </div>

</nav>

</body>
</html>
