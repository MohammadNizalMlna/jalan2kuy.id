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
    
    <!-- {{-- Tampilkan Pesan Sukses jika ada (setelah add destination) --}}
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; margin: 10px auto; width: 80%; border-radius: 5px; text-align: center;">
            {{ session('success') }}
        </div>
    @endif -->

    <div class="category-header">
      {{-- 1. JUDUL DINAMIS: Mengambil dari variable controller --}}
      <h1 id="category-title">{{ strtoupper($categoryName) }}</h1>

      <!-- {{-- 2. TOMBOL ADD: Link sudah benar --}}
      <a href="{{ url('/admin/Destination/AddDestination?Category=' . $destCategoryID) }}" id="addBtn" class="add-btn">
        + Add Destination
      </a>
    </div> -->

    {{-- 3. RENDER KARTU DENGAN PHP BLADE (Bukan JS lagi) --}}
    <div class="destination-container" id="destination-list">
        
        @forelse($destinations as $dest)
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

  <!-- {{-- Script JS --}}
  {{-- HAPUS category.js KARENA SUDAH DIGANTI BLADE --}}
  {{-- <script src="{{ asset('js/category.js') }}"></script> --}} -->

</body> 
</html>