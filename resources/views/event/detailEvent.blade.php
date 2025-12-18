<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jalan2Kuy.id - {{ $event->name }}</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  
  <link rel="stylesheet" href="{{ asset('css/event/detailEvent.css') }}">
</head>
<body>

{{-- Navbar --}}
@include('partials.navbar')

<!-- Section utama untuk menampilkan detail lengkap event -->
<section class="event-detail">
  <div class="event-box" id="eventBox">

    <!-- Menampilkan nama event sebagai judul utama -->
    {{-- Judul Event --}}
    <h2 class="event-title">{{ $event->name }}</h2>

    <!-- Container isi detail event -->
    {{-- Konten Detail Event --}}
    <div id="eventDetailContent">
      <div class="event-content">
        
      <!-- Bagian kiri berisi deskripsi event dalam bentuk teks -->
        {{-- Bagian Kiri: Deskripsi Teks --}}
        <div class="event-text">
            {{-- Menampilkan deskripsi dengan format baris baru --}}
            <p>{!! nl2br(e($event->description)) !!}</p>
        </div>

        {{-- Bagian Kanan: Gambar & Info Singkat --}}
        <!-- Bagian kanan berisi gambar event dan informasi singkat -->
        <div class="event-side">
          <!-- Gambar event -->
          <img src="{{ asset('storage/' . $event->imagePath) }}" alt="{{ $event->name }}">
          <div class="event-info">
            <!-- Informasi detail seperti lokasi, media sosial, tanggal, jam, dan harga tiket -->
            {{-- Lokasi --}}
            <p><i class="fa-solid fa-location-dot"></i> {{ $event->location }}</p>
            <br>
            <ul>
              {{-- Media Social --}}
              <li>
                <strong>Social Media :</strong> 
                <a href="https://www.instagram.com/{{ $event->socialMedia }}" target="_blank" style="color: blue; text-decoration: none;">
                  {{ '@' . $event->socialMedia }}
                </a>
              {{-- Tanggal --}}
              <li>
                <strong>Tanggal :</strong> {{ $event->startDate ? \Carbon\Carbon::parse($event->startDate)->format('d M Y') : '-' }} - {{ $event->endDate ? \Carbon\Carbon::parse($event->endDate)->format('d M Y') : '-' }}
              </li>

              {{-- Jam --}}
              <li>
                 <strong>Jam :</strong> 
                 {{ \Carbon\Carbon::parse($event->startTime)->format('H:i') }} - 
                 {{ \Carbon\Carbon::parse($event->endTime)->format('H:i') }}
              </li>

              {{-- Harga --}}
              <li>
                 <strong>Tiket Masuk :</strong> Rp {{ number_format($event->entranceFee, 0, ',', '.') }}
              </li>
            </ul>
            
          </div>
        </div>

      </div>
    </div>
  
  </div>
</section>

{{-- Footer --}}
@include('partials.footer')

</body>
</html>