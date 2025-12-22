<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  {{-- Menampilkan Nama Kategori di Tab Browser --}}
  <title>Jalan2Kuy.id - {{ $categoryName ?? 'Category' }}</title>

  {{-- CSS --}}
  <link rel="stylesheet" href="{{ asset('css/admin/desAdmin.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Skranji:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
  
  {{-- Navbar Admin --}}
  @include('partials.navbar')

  <section class="hero">
    <!-- Section utama untuk menampilkan destinasi berdasarkan kategori -->
    <div class="category-header">
      <!-- Header berisi judul kategori yang diambil dari controller -->
      {{-- 1. JUDUL DINAMIS: Mengambil dari variable controller --}}
      <h1 id="category-title">{{ strtoupper($categoryName) }}</h1>
    </div> 

    {{-- 3. RENDER KARTU DESTINASI --}}
    <div class="destination-container" id="destination-list">
        
        @forelse($destination as $dest)
            {{-- Link ke Detail (Nanti buat route detailnya) --}}
            <a class="destination-card" href="{{ url('/Destination/Detail/' . $dest->destinationID) }}">
                
                {{-- Gambar Thumbnail --}}
                {{-- Pastikan nama kolom di DB sesuai (thumbnailImagePath) --}}
                <div class="card-img" style="background-image:url('{{ asset('storage/' . $dest->thumbnailImagePath) }}')">
                    <div class="overlay">{{ $dest->name }}</div>
                </div>
            </a>
        @empty
            {{-- Tampilan jika belum ada data di database --}}
            <div style="width: 100%; text-align: center; color: white; margin-top: 50px;">
                <p>Belum ada destinasi di kategori ini.</p>
            </div>
        @endforelse

    </div>
  </section>

  {{-- Footer --}}
  @include('partials.footer')

</body> 
</html>