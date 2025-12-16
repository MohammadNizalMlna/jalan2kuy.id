<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian - Jalan2kuy.id</title>
    
    {{-- Menggunakan CSS yang sama agar tampilan konsisten --}}
    <link rel="stylesheet" href="{{ asset('css/destination/destinasi.css') }}">
    <style>
        /* Tambahan CSS sedikit untuk pesan jika tidak ada hasil */
        .no-result {
            color: white;
            text-align: center;
            font-size: 1.5rem;
            margin-top: 20px;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: white;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    @include('partials.navbar')

    <section class="hero">
        {{-- Form Search (Tetap ditampilkan agar user bisa cari lagi) --}}
        <form action="{{ url('/Destination') }}" method="GET" class="search">
            <input type="text" name="search" placeholder="Cari destinasi..." value="{{ $keyword }}">
            <button type="submit">
                <img src="{{ asset('assets/gambar/icon/search.png') }}" alt="search">
            </button>
        </form>
        
        <h2 style="color: white; margin-bottom: 30px; text-shadow: 2px 2px 4px #000;">
            Menampilkan hasil untuk: "{{ $keyword }}"
        </h2>

        {{-- Container Hasil --}}
        <div class="categori-container">
            
            @if($destinations->count() > 0)
                @foreach($destinations as $dest)
                    {{-- Card Destinasi --}}
                    {{-- Menggunakan class 'categori' agar styling kotak dan gambarnya sama --}}
                    <a href="{{ url('/Destination/Detail/' . $dest->destinationID) }}" 
                       class="categori"
                       style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('{{ asset('storage/' . $dest->thumbnailImagePath) }}');">
                        
                        <span>{{ $dest->name }}</span>
                    </a>
                @endforeach
            @else
                {{-- Tampilan Jika Hasil Kosong --}}
            @endif

        </div>

        @if($destinations->count() == 0)
            <div class="no-result">
                Destinasi "{{ $keyword }}" tidak ditemukan.
            </div>
        @endif

    </section>

    @include('partials.footer')

</body>
</html>