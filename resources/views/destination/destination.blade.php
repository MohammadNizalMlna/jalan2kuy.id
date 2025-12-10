<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jalan2kuy.id - Destination</title>
    
    {{-- Asset CSS --}}
    <link rel="stylesheet" href="{{ asset('css/destination/destinasi.css') }}">
</head>
<body>

    {{-- Navbar Partial --}}
    @include('partials.navbar')

    <section class="hero">
        <div class="search">
            <input type="text" placeholder="Searching....">
            <button>
                <img src="{{ asset('assets/gambar/icon/search.png') }}" alt="search">
            </button>
        </div>
        
        <div class="categori-container">
            {{-- 
               CATATAN ROUTE:
               Pastikan kamu membuat Route di web.php untuk menangani link ini.
               Contoh: Route::get('/destination/category', [DestinasiController::class, 'category']);
            --}}
            <a href="{{ url('/Destination/Category?Category=Nature') }}" class="categori nature"><span>Nature</span></a>
            <a href="{{ url('/Destination/Category?Category=History') }}" class="categori history"><span>History</span></a>
            <a href="{{ url('/Destination/Category?Category=Ecotourism') }}" class="categori ecotourism"><span>Ecotourism</span></a>
            <a href="{{ url('/Destination/Category?Category=Beach') }}" class="categori beach"><span>Beach</span></a>
            <a href="{{ url('/Destination/Category?Category=Culture') }}" class="categori culture"><span>Culture</span></a>
            <a href="{{ url('/Destination/Category?Category=Education') }}" class="categori education"><span>Education</span></a>
        </div>
    </section>

    {{-- Footer Partial --}}
    @include('partials.footer')

</body>
</html>