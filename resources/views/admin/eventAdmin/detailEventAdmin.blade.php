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
@include('partials.navbarAdmin')

<!-- Section utama untuk menampilkan detail event versi admin -->
<section class="event-detail">
  <div class="event-box" id="eventBox">
    
    <!-- Tombol edit dan delete event (floating button) -->
    {{-- Tombol Edit & Delete (Floating) --}}
    <div class="event-actions-floating">
      <button class="edit-btn" onclick="window.location.href='{{ url('/admin/Event/Edit/' . $event->eventID) }}'">Edit</button>
      <button class="delete-btn" id="deleteBtn">Delete</button>
    </div>

    <!-- Menampilkan nama event -->
    {{-- Judul Event --}}
    <h2 class="event-title">{{ $event->name }}</h2>

    <!-- Container isi detail event (deskripsi, gambar, dan informasi) -->
    {{-- Konten Detail Event --}}
    <div id="eventDetailContent">
      <div class="event-content">
        
        <!-- Bagian kiri berisi deskripsi event -->
        {{-- Bagian Kiri: Deskripsi Teks --}}
        <div class="event-text">
            {{-- Menampilkan deskripsi dengan format baris baru --}}
            <p>{!! nl2br(e($event->description)) !!}</p>
        </div>

        <!-- Bagian kanan berisi gambar event dan informasi singkat -->
        {{-- Bagian Kanan: Gambar & Info Singkat --}}
        <div class="event-side">
          <img src="{{ asset('storage/' . $event->imagePath) }}" alt="{{ $event->name }}">
          <div class="event-info">
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

{{-- POPUP KONFIRMASI DELETE --}}
<!-- Popup konfirmasi sebelum event dihapus -->
<div class="popup-overlay" id="popupOverlay">
  <div class="popup-box">
    <p>Apakah Anda yakin ingin menghapus event <strong>"{{ $event->name }}"</strong>?</p>
    <div class="popup-buttons">
      <button class="yes-btn" id="confirmDelete">Ya</button>
      <button class="no-btn" id="cancelDelete">Tidak</button>
    </div>
  </div>
</div>

{{-- HIDDEN FORM UNTUK DELETE (Diperlukan Laravel) --}}
<form id="deleteForm" action="{{ url('/admin/Event/Delete/' . $event->eventID) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
  // Logika Popup
  const popup = document.getElementById("popupOverlay");
  const deleteBtn = document.getElementById("deleteBtn");
  const cancelDelete = document.getElementById("cancelDelete");
  const confirmDelete = document.getElementById("confirmDelete");
  const deleteForm = document.getElementById("deleteForm");

  // Tampilkan Popup
  deleteBtn.onclick = () => {
    popup.style.display = "flex";
  };

  // Tutup Popup
  cancelDelete.onclick = () => {
    popup.style.display = "none";
  };

  // Jika tombol "Ya" diklik, submit form delete Laravel
  confirmDelete.onclick = () => {
    popup.style.display = "none";
    deleteForm.submit(); 
  };
</script>

</body>
</html>