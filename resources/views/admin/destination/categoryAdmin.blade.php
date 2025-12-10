<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jalan2Kuy.id - Destination Category Admin</title>

  {{-- CSS --}}
  <link rel="stylesheet" href="{{ asset('css/admin/desAdmin.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Skranji:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
  
  {{-- Navbar Admin --}}
  @include('partials.navbarAdmin')

  <section class="hero">
    <div class="category-header">
      {{-- Judul Kategori --}}
      <h1 id="category-title">KATEGORI</h1>

      {{-- Tombol Tambah Destinasi --}}
      {{-- Pastikan kamu sudah membuat route '/admin/destination/create' di web.php --}}
      <a href="{{ url('/admin/destination/create') }}" id="addBtn" class="add-btn">
        + Add Destination
      </a>
    </div>

    {{-- Container List Destinasi (Dirender oleh JS) --}}
    <div class="destination-container" id="destination-list"></div>
  </section>

  {{-- Footer --}}
  @include('partials.footer')

  {{-- Script JS --}}
  <script src="{{ asset('js/destinations.js') }}"></script>
  
  {{-- Pastikan menggunakan JS untuk Admin (category.js), bukan categoryUser.js --}}
  <script src="{{ asset('js/category.js') }}"></script>

</body>
</html>