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

    <!-- Section utama berisi fitur pencarian dan daftar kategori destinasi -->
    <section class="hero">
        <!-- Form pencarian destinasi berdasarkan keyword -->
        <form action="{{ url('/Destination') }}" method="GET" class="search">
            <input type="text" name="search" placeholder="Cari destinasi..." value="{{ request('search') }}">
            <button type="submit">
                <img src="{{ asset('assets/gambar/icon/search.png') }}" alt="search">
            </button>
        </form>
        
        <!-- Container untuk menampilkan kategori destinasi -->
        <div class="categori-container">
            
            <!-- Loop data kategori destinasi dari database -->
            @foreach($categories as $cat)
                <a href="{{ url('/Destination/Category?Category=' . $cat->destCategoryID) }}" 
                class="categori"
                {{-- PERUBAHAN DISINI --}}
                style="background-image: url('{{ asset('storage/' . $cat->categoryImage) }}');">
                
                <span>{{ $cat->categoryName }}</span>
                </a>
            @endforeach

        </div>
    </section>

    {{-- Footer Partial --}}
    @include('partials.footer')

</body>
</html>