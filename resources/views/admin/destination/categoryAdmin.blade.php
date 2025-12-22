<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  {{-- Menampilkan Nama Kategori di Tab Browser --}}
  <title>Jalan2Kuy.id - {{ $categoryName ?? 'Category' }} Admin</title>

  {{-- CSS --}}
  <link rel="stylesheet" href="{{ asset('css/admin/desAdmin.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Skranji:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
  
  {{-- Navbar Admin --}}
  @include('partials.navbarAdmin')

  <!-- Section utama untuk menampilkan daftar destinasi berdasarkan kategori -->
  <section class="hero">
    
    {{-- Tampilkan Pesan Sukses jika ada (setelah add destination) --}}
    <!-- Menampilkan pesan sukses setelah menambah destinasi -->
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; margin: 10px auto; width: 80%; border-radius: 5px; text-align: center;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Header berisi judul kategori dan tombol tambah destinasi -->
    <div class="category-header">
      {{-- 1. JUDUL DINAMIS: Mengambil dari variable controller --}}
      <!-- Judul kategori diambil dari controller dan ditampilkan uppercase -->
      <h1 id="category-title">{{ strtoupper($categoryName) }}</h1>

      <!-- Tombol untuk menambahkan destinasi baru pada kategori ini -->
      {{-- 2. TOMBOL ADD: Link sudah benar --}}
      <a href="{{ url('/admin/Destination/AddDestination?Category=' . $destCategoryID) }}" id="addBtn" class="add-btn">
        + Add Destination
      </a>
    </div>

    <!-- Setiap card menuju halaman detail destinasi -->
    {{-- 3. RENDER KARTU --}}
    <div class="destination-container" id="destination-list">
        
        @forelse($destination as $dest)
            {{-- Link ke Detail (Nanti buat route detailnya) --}}
            <a class="destination-card" href="{{ url('/admin/Destination/Detail/' . $dest->destinationID) }}">
                
                {{-- Gambar Thumbnail --}}
                {{-- Pastikan nama kolom di DB sesuai (thumbnailImagePath) --}}
                <div class="card-img" style="background-image:url('{{ asset('storage/' . $dest->thumbnailImagePath) }}')">
                    <div class="overlay">{{ $dest->name }}</div>
                </div>
            </a>
            <!-- Tampilan jika belum ada destinasi pada kategori ini -->
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