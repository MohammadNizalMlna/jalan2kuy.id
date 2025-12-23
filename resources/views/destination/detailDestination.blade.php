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

  @include('partials.navbar')
  
  <!-- Section utama untuk menampilkan detail lengkap destinasi -->
  <section class="hero">
    
      <!-- Menampilkan notifikasi sukses atau error dari session -->
    {{-- Notifikasi Sukses/Error --}}
    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
            {{ session('success') }}
        </div>
    @endif

    <!-- Menampilkan nama destinasi sebagai judul utama -->
    <div class="hero-top">
      <h1 id="title">{{ $destination->name }}</h1>
    </div>

    <!-- Container berisi deskripsi, gambar, info, dan event terkait -->
    <div class="content-box">
      
      <div class="description">
        <h2>Deskripsi</h2>
        {{-- nl2br agar enter/baris baru di textarea tetap muncul --}}
        <p>{!! nl2br(e($destination->description)) !!}</p>
      </div>

      <div class="image-box">
        <img id="img" src="{{ asset('storage/' . $destination->imagePath) }}" alt="{{ $destination->name }}">
        
        <p id="loc">
            <i class="fas fa-map-marker-alt"></i> {{ $destination->location }}
        </p>
        
        <p id="time">
            <i class="fas fa-clock"></i> 
            {{ $destination->openingDay }} - {{ $destination->closingDay }} <br>
            ({{ \Carbon\Carbon::parse($destination->openingHours)->format('H:i') }} - {{ \Carbon\Carbon::parse($destination->closingHours)->format('H:i') }} {{ $destination->timezone }})
        </p>
        
        <p id="price">
            <i class="fas fa-tag"></i> 
            Rp {{ number_format($destination->entranceFee, 0, ',', '.') }}
        </p>

        <!-- Menampilkan daftar event yang terkait dengan destinasi ini -->
        <h3>Event Terkait</h3>
        <div id="eventBox">
            {{-- Cek variabel $events yang dikirim Controller --}}
            <!-- Jika terdapat event terkait -->
            @if($events->count() > 0)
                
                {{-- Loop variabel $events --}}
                @foreach($events as $event)
                    <a class="event-link" href="{{ url('/Event/Detail/' . $event->eventID) }}" style="margin-bottom: 10px;">
                        <b>{{ $event->name }}</b><br>
                        <span>{{ \Carbon\Carbon::parse($event->startDate)->format('d M Y') }}</span>
                    </a>
                @endforeach

            <!-- Jika tidak ada event terkait -->
            @else
                <p style="color:  #000000; font-style: italic;">Tidak ada event di destinasi ini.</p>
            @endif
        </div>
      </div>
    </div>

  </section>

  @include('partials.footer')

</body>
</html>