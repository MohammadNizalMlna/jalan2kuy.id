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
    @include('partials.navbarAdmin')

    <section class="hero">
        <form action="{{ url('/admin/Destination') }}" method="GET" class="search">
            <input type="text" name="search" placeholder="Cari destinasi..." value="{{ request('search') }}">
            <button type="submit">
                <img src="{{ asset('assets/gambar/icon/search.png') }}" alt="search">
            </button>
        </form>
        
        <div class="categori-container">
            
            @foreach($categories as $cat)
                <a href="{{ url('/admin/Destination/Category?Category=' . $cat->destCategoryID) }}" 
                class="categori"
                {{-- PERUBAHAN DISINI --}}
                style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('{{ asset('storage/' . $cat->categoryImage) }}');">
                
                <span>{{ $cat->categoryName }}</span>
                </a>
            @endforeach

        </div>
    </section>

    {{-- Footer Partial --}}
    @include('partials.footer')

</body>
</html>