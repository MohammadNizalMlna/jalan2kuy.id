<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jalan2Kuy.id - Destination Category</title>

  {{-- Gunakan asset() untuk memanggil CSS --}}
  <link rel="stylesheet" href="{{ asset('css/admin/desAdmin.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Skranji:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
  
  {{-- Menggunakan Include Laravel --}}
  @include('partials.navbar')

  <section class="hero">
    <div class="category-header">
      {{-- Judul ini nanti bisa dinamis dari Controller ($categoryName) atau tetap dari JS --}}
      <h1 id="category-title">KATEGORI</h1>
    </div>

    <div class="destination-container" id="destination-list"></div>
  </section>

  @include('partials.footer')

  {{-- Script JS --}}
  <script src="{{ asset('js/destinations.js') }}"></script>
  <script src="{{ asset('js/categoryUser.js') }}"></script>

  {{-- Script fetch navbar/footer dihapus karena sudah pakai @include --}}

</body>
</html>