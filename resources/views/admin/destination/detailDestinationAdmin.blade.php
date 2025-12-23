<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jalan2Kuy.id - {{ $destination->name }}</title>

  {{-- Asset CSS --}}
  <link rel="stylesheet" href="{{ asset('css/destination/detaildestination.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Skranji:wght@400;700&display=swap" rel="stylesheet">

  <style>
      /* Pastikan body memuat background image */
      body {
          background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('{{ asset("storage/" . ($destination->imagePath ?? $destination->thumbnailImagePath)) }}');
          background-size: cover;
          background-position: center;
          background-attachment: fixed;
          background-repeat: no-repeat;
          min-height: 100vh;
          margin: 0;
      }
      .event-link {
          text-decoration: none;
          color: #333;
          display: block;
          padding: 10px;
          border: 1px solid #ddd;
          border-radius: 5px;
          background: #f9f9f9;
          transition: 0.3s;
      }
      .event-link:hover {
          background: #eee;
          border-color: #bbb;
      }
  </style>
</head>

<body>

  @include('partials.navbarAdmin')

  <!-- Section utama yang menampilkan detail lengkap destinasi -->
  <section class="hero">
    
    <!-- Menampilkan notifikasi sukses dari session Laravel -->
    {{-- Notifikasi Sukses/Error --}}
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
            {{ session('success') }}
        </div>
    @endif

    <div class="hero-top">
      <h1 id="title">{{ $destination->name }}</h1>
      
      <!-- Menampilkan nama destinasi dan tombol edit/delete -->
      <div class="action-btn">
        {{-- Tombol Edit: Mengarah ke Route Edit (Perlu dibuat nanti) --}}
        <a href="{{ url('/admin/Destination/Edit/' . $destination->destinationID) }}" class="edit-btn" style="text-decoration: none; display:inline-block; text-align:center;">Edit</a>
        
        <button class="delete-btn" onclick="showDeletePopup()">Delete</button>
      </div>
    </div>

    <!-- Container berisi deskripsi, gambar, info destinasi, dan event terkait -->
    <div class="content-box">
      
      <!-- Menampilkan deskripsi dengan format baris tetap -->
      <div class="description">
        <h2>Deskripsi</h2>
        {{-- nl2br agar enter/baris baru di textarea tetap muncul --}}
        <p>{!! nl2br(e($destination->description)) !!}</p>
      </div>

      <!-- Menampilkan gambar utama dan informasi destinasi -->
      <div class="image-box">
        <img id="img" src="{{ asset('storage/' . $destination->imagePath) }}" alt="{{ $destination->name }}">
        <!-- lokasi -->
        <p id="loc">
            <i class="fas fa-map-marker-alt"></i> {{ $destination->location }}
        </p>
        <!-- jam operasional -->
        <p id="time">
            <i class="fas fa-clock"></i> 
            {{ $destination->openingDay }} - {{ $destination->closingDay }} <br>
            ({{ \Carbon\Carbon::parse($destination->openingHours)->format('H:i') }} - {{ \Carbon\Carbon::parse($destination->closingHours)->format('H:i') }} {{ $destination->timezone }})
        </p>
        <!-- harga tiket -->
        <p id="price">
            <i class="fas fa-tag"></i> 
            Rp {{ number_format($destination->entranceFee, 0, ',', '.') }}
        </p>

        <!-- Menampilkan daftar event yang berelasi dengan destinasi -->
        <h3>Event Terkait</h3>
        <div id="eventBox">
            {{-- Cek variabel $events yang dikirim Controller --}}
            @if($events->count() > 0)
                
                {{-- Loop variabel $events --}}
                @foreach($events as $event)
                    <a class="event-link" href="{{ url('/admin/Event/Detail/' . $event->eventID) }}" style="margin-bottom: 10px;">
                        <b>{{ $event->name }}</b><br>
                        <span>{{ \Carbon\Carbon::parse($event->startDate)->format('d M Y') }}</span>
                    </a>
                @endforeach

            @else
            <!-- Jika tidak ada event -->
                <p style="color:  #000000; font-style: italic;">Tidak ada event di destinasi ini.</p>
            @endif
        </div>
      </div>
    </div>

    <div id="deletePopup" class="popup-overlay" style="display: none;">
      <div class="popup-box">
        <p>Apakah anda yakin untuk menghapus wisata ini?</p>
        <div class="popup-buttons">
            {{-- Form Delete (Method DELETE) --}}
            <form action="{{ url('/admin/Destination/Delete/' . $destination->destinationID) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="yes-btn">Yes</button>
            </form>
            
            <button id="noDelete" class="no-btn" onclick="hideDeletePopup()">NO</button>
        </div>
      </div>
    </div>

  </section>

  @include('partials.footer')

  {{-- Script Sederhana untuk Popup --}}
  <script>
      function showDeletePopup() {
          document.getElementById('deletePopup').style.display = 'flex';
      }

      function hideDeletePopup() {
          document.getElementById('deletePopup').style.display = 'none';
      }
  </script>

</body>
</html>