<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jalan2kuy.id - Gallery Admin</title>
    
    <link rel="stylesheet" href="{{ asset('css/galeri/galery.css') }}">
</head>

<body>

    {{-- Include Navbar dari partials Laravel --}}
    @include('partials.navbarAdmin')

    <section class="gallery-container" id="gallery">
        
        {{-- Loop data destinasi dari database --}}
        @foreach($destinations as $destination)
            <div class="gallery-card">
                {{-- 
                    Mengambil gambar dari storage/app/public/destinations/image 
                    Pastikan Anda sudah menjalankan command: php artisan storage:link
                --}}
                <img src="{{ asset('storage/' . $destination->imagePath) }}" alt="{{ $destination->name }}">

                <p>{{ $destination->name }}</p>
            </div>
        @endforeach

    </section>

    {{-- Include Footer dari partials Laravel --}}
    @include('partials.footer')

</body>
</html>