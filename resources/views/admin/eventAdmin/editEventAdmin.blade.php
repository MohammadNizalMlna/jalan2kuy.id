<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jalan2Kuy.id - Edit Event</title>
    
    <link rel="stylesheet" href="{{ asset('css/event/addEvent.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="page-wrapper"> 

    @include('partials.navbarAdmin')

    <section class="form-container">
        <h1 class="title">Edit Event</h1>

        @if(session('error'))
            <div style="background: #ffcccc; color: red; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                {{ session('error') }}
            </div>
        @endif
        
        <form id="eventForm" class="event-form" action="{{ url('/admin/Event/Update/' . $event->eventID) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') 

            <div class="form-item item-image">
                @if($event->imagePath)
                    <img src="{{ asset('storage/' . $event->imagePath) }}" id="imgPreview" class="image-preview-bg">
                @else
                    <img src="" id="imgPreview" class="image-preview-bg" style="display: none;">
                @endif

                <div class="upload-content">
                    <i class="fas fa-pen" id="uploadIcon"></i>
                    <p id="uploadText">{{ $event->imagePath ? 'Ganti Gambar' : 'Add Image' }}</p>
                </div>

                <input type="file" name="image" id="eventImage" accept="image/*" class="file-input" onchange="previewNewImage(this)">
                @error('image') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div class="form-item item-info">
                <label>Judul Event</label>
                <input type="text" name="name" placeholder="Masukkan Judul Event..." required value="{{ old('name', $event->name) }}">
                @error('name') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div class="form-item item-info">
                <label>Lokasi Event</label>
                <input type="text" name="location" placeholder="Masukkan Lokasi Event..." required value="{{ old('location', $event->location) }}">
            </div>

            <div class="form-item item-info">
                <label>Social Media (ketikkan tanpa @)</label>
                <input type="text" name="socialMedia" placeholder="Akun Sosmen Event..." required value="{{ old('socialMedia', $event->socialMedia) }}">
            </div>

            <div class="form-item item-full">
                <label>Deskripsi Lengkap</label>
                <textarea name="description" placeholder="Masukkan Deskripsi Lengkap Event..." style="height: 150px;" required>{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="form-item item-info">
                <label>Waktu Pelaksanaan</label>
                
                <div class="split-inputs">
                    <div>
                        <label style="font-size: 12px;">Tanggal Mulai</label>
                        <input type="date" name="startDate" required value="{{ old('startDate', $event->startDate ? \Carbon\Carbon::parse($event->startDate)->format('Y-m-d') : '') }}">
                    </div>
                    <div>
                        <label style="font-size: 12px;">Tanggal Selesai</label>
                        <input type="date" name="endDate" required value="{{ old('endDate', $event->endDate ? \Carbon\Carbon::parse($event->endDate)->format('Y-m-d') : '') }}">
                    </div>
                </div>

                <div class="split-inputs">
                    <div>
                        <label style="font-size: 12px;">Jam Mulai</label>
                        <input type="time" name="startTime" required value="{{ old('startTime', \Carbon\Carbon::parse($event->startTime)->format('H:i')) }}">
                    </div>
                    <div>
                        <label style="font-size: 12px;">Jam Selesai</label>
                        <input type="time" name="endTime" required value="{{ old('endTime', \Carbon\Carbon::parse($event->endTime)->format('H:i')) }}">
                    </div>
                </div>
            </div>

            <div class="form-item item-info">
                <label>Harga Tiket (Rp)</label>
                <input type="number" name="entranceFee" placeholder="0" required value="{{ old('entranceFee', $event->entranceFee) }}">
            </div>

            <button type="submit" class="submit-btn">Update Event</button>
        </form>
    </section>
</div> 

@include('partials.footer')

<script>
    // Script untuk mengganti preview gambar saat user memilih file baru
    function previewNewImage(input) {
        const preview = document.getElementById('imgPreview');
        const text = document.getElementById('uploadText');
        const icon = document.getElementById('uploadIcon');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block'; // Munculkan gambar
                preview.style.opacity = '0.5';   // Agak transparan biar teks kelihatan
                
                text.innerText = input.files[0].name;
                icon.className = "fas fa-check-circle";
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>
</html>