<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jalan2Kuy.id - Deskripsi Event</title>
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/event/detailEvent.css') }}">

  <style>
      body {
          zoom: 70%;
          -moz-transform: scale(0.7);
          -moz-transform-origin: top center;
          width: 142.8%;
      }
      /* Pastikan gambar tidak error jika kosong */
      .event-side img {
          max-width: 100%;
          height: auto;
          object-fit: cover;
          border-radius: 8px;
      }
  </style>
</head>
<body>

@include('partials.navbarAdmin')

<section class="event-detail">
  <div class="event-box" id="eventBox">
    
    <a href="{{ url('/admin/event') }}" style="display:inline-block; margin-bottom:15px; text-decoration:none; color: #333;">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke List
    </a>

    <div class="event-actions-floating">
      <button class="edit-btn" onclick="window.location.href='{{ url('/admin/event/edit/' . $event->eventID) }}'">Edit</button>
      
      <button class="delete-btn" id="deleteBtn">Delete</button>
    </div>

    <h2 class="event-title">{{ $event->name }}</h2>

    <div id="eventDetailContent">
      <div class="event-content">
        
        <div class="event-text">
            <p>{!! nl2br(e($event->description)) !!}</p>
        </div>

        <div class="event-side">
            <img src="{{ asset('assets/gambar/raja-ampat-dikeruk.jpeg') }}" alt="Event Image">
            
            <div class="event-info">
                <ul>
                    <li>
                        <strong>Start:</strong> {{ $event->startDate ? $event->startDate->format('d M Y') : '-' }} <br>
                        <strong>End:</strong> {{ $event->endDate ? $event->endDate->format('d M Y') : '-' }}
                    </li>
                    
                    <li>
                        <strong>Jam:</strong> {{ $event->startTime }} - {{ $event->endTime }} WIB
                    </li>

                    <li>
                        <strong>Harga:</strong> Rp {{ number_format($event->entranceFee, 0, ',', '.') }}
                    </li>
                </ul>
                
                <p><i class="fa-solid fa-location-dot"></i> {{ $event->location }}</p>
            </div>
        </div>

      </div>
    </div>
  
  </div>
</section>

@include('partials.footer')

<div class="popup-overlay" id="popupOverlay">
  <div class="popup-box">
    <p>Apakah Anda yakin ingin menghapus event <strong>"{{ $event->name }}"</strong>?</p>
    
    <div class="popup-buttons">
        <button class="yes-btn" id="confirmDelete">Ya</button>
        <button class="no-btn" id="cancelDelete">Tidak</button>
    </div>
  </div>
</div>

<form id="deleteForm" action="{{ url('/admin/event/delete/' . $event->eventID) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
  // Logika Popup Delete
  const popup = document.getElementById("popupOverlay");
  const deleteBtn = document.getElementById("deleteBtn");
  const cancelDelete = document.getElementById("cancelDelete");
  const confirmDelete = document.getElementById("confirmDelete");
  const deleteForm = document.getElementById("deleteForm");

  // Buka Popup
  deleteBtn.onclick = () => {
    popup.style.display = "flex";
  };

  // Tutup Popup (Cancel)
  cancelDelete.onclick = () => {
    popup.style.display = "none";
  };

  // Konfirmasi Delete (Submit Form ke Laravel)
  confirmDelete.onclick = () => {
    popup.style.display = "none";
    deleteForm.submit(); // <--- INI KUNCINYA
  };

  // Script load JS event lama dihapus karena data sudah dirender server-side
</script>

</body>
</html>