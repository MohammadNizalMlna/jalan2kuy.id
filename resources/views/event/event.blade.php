<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Jalan2Kuy.id - Event</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/event/event.css') }}">

  <style>
      /* Style tambahan KHUSUS untuk card hasil loop Blade agar mirip JS render */
      /* Karena logicEventAdmin.js membuat HTML card secara dinamis, kita tiru struktur HTML-nya disini */
      .event-card {
          /* Pastikan style ini sesuai dengan apa yang ada di event.css untuk .event-card */
          position: relative;
          cursor: pointer;
          /* Jika perlu tambahkan style inline jika css asli belum ke-load sempurna */
      }
      
      /* Tombol aksi kecil di dalam card (jika layout asli tidak punya tombol edit/delete di luar) */
      .card-actions {
          margin-top: 10px;
          display: flex;
          gap: 5px;
      }
  </style>
</head>
<body>

@include('partials.navbar')

<!-- Section utama untuk menampilkan daftar event -->
<section class="event-section">
  
  <!-- Header berisi judul halaman dan filter tanggal event -->
  <div class="event-header">
    <h2>Date Event</h2>
    
    <!-- Form filter event berdasarkan rentang tanggal -->
    <form action="{{ url('/Event') }}" method="GET" class="date-inputs">
      
      <input type="text" name="start_date" class="date-field" placeholder="DD/MM/YYYY"
            onfocus="(this.type='date')" 
            onblur="if(!this.value)this.type='text'"
            value="{{ request('start_date') }}" required>

      <span class="dash">-</span>
      
      <input type="text" name="end_date" class="date-field" placeholder="DD/MM/YYYY"
            onfocus="(this.type='date')" 
            onblur="if(!this.value)this.type='text'"
            value="{{ request('end_date') }}" required>
      
      <!-- Tombol submit filter -->
      <button type="submit" style="background: transparent; border: none; cursor: pointer; color: #15514A; font-weight: bold; font-size: 16px;">
          <i class="fa-solid fa-filter"></i>
      </button>
      <!-- Tombol reset filter -->
      @if(request('start_date'))
        <a href="{{ url('/Event') }}" style="color: red; margin-left: 5px; font-size: 12px; text-decoration: none;">Reset</a>
      @endif
    </form>
  </div>

  <!-- Menampilkan pesan sukses dari session Laravel -->
  @if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 10px; margin: 10px auto; width: 90%; border-radius: 5px; text-align: center;">
        {{ session('success') }}
    </div>
  @endif
      <!-- Grid daftar event yang diambil dari database -->
  <div class="event-cards" id="eventList" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
    <!-- Loop data event dari controller -->
      @forelse($events as $event)
        
        <div class="modern-card" onclick="window.location.href='{{ url('/Event/Detail/' . $event->eventID) }}'">
            
          <!-- Gambar dan tanggal event -->
            <div class="card-image-wrapper">
                <img src="{{ asset('storage/' . $event->imagePath) }}" 
                    alt="{{ $event->name }}">
                
                <div class="date-badge">
                    {{-- Format: 22 Agustus 2025 - 28 Desember 2025 --}}
                    {{-- Gunakan translatedFormat('d F Y') agar nama bulan Bahasa Indonesia (jika locale ID diaktifkan), atau format('d F Y') biasa --}}
                    {{ $event->startDate ? $event->startDate->format('d F Y') : '-' }} - 
                    {{ $event->endDate ? $event->endDate->format('d F Y') : '-' }}
                </div>
            </div>

            <!-- Konten utama card -->
            <div class="card-content">
                <h3 class="card-title">{{ $event->name }}</h3>
                
                <div class="card-location">
                    <i class="fa-solid fa-location-dot"></i> 
                    {{ $event->location }}
                </div>
                
                <div class="card-desc">
                    {{ Str::limit($event->description, 100) }}
                </div>

                <div class="card-price">
                    Rp {{ number_format($event->entranceFee, 0, ',', '.') }}
                </div>
            </div>

        </div>
            <!-- Tampilan jika tidak ada event -->
      @empty
        <div style="grid-column: 1 / -1; text-align: center; color: #ffff; margin-top: 50px;">
            <i class="fa-regular fa-calendar-xmark" style="font-size: 40px; margin-bottom: 10px;"></i>
            <p>Belum ada event yang tersedia.</p>
        </div>
      @endforelse
  </div>

</section>

@include('partials.footer')

</body>
</html>