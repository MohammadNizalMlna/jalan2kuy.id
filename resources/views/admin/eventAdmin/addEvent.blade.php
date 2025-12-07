<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>jalan2kuy.id - Add Event</title>
    
    <link rel="stylesheet" href="{{ asset('css/event/addEvent.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        .split-inputs {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        .split-inputs > div {
            flex: 1;
        }
        .file-input {
            padding: 10px;
        }
        .error-text {
            color: red;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>

<body>

<div class="page-wrapper"> 

    @include('partials.navbarAdmin')

    <section class="form-container">
        <h1 class="title">Add Event</h1>

        @if(session('error'))
            <div style="background: #ffcccc; color: red; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                {{ session('error') }}
            </div>
        @endif
        
        <form id="eventForm" class="event-form" action="{{ url('/admin/Event/store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-item item-image">
                <i class="fas fa-plus" id="uploadIcon"></i>
                <p id="uploadText">Add Image</p>
                <input type="file" name="image" id="eventImage" accept="image/*" class="file-input" required onchange="updateFileName()">
                @error('image') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div class="form-item item-info">
                <label>Judul Event</label>
                <input type="text" name="name" placeholder="Judul Event" required value="{{ old('name') }}">
                @error('name') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <div class="form-item item-info">
                <label>Lokasi Event</label>
                <input type="text" name="location" placeholder="Masukkan lokasi event..." required value="{{ old('location') }}">
            </div>

            <div class="form-item item-info">
                <label>Social Media</label>
                <input type="text" name="socialMedia" placeholder="Akun Sosial Media..." value="{{ old('socialMedia') }}">
            </div>

            <div class="form-item item-full">
                <label>Deskripsi Lengkap</label>
                <textarea name="description" placeholder="Masukkan deskripsi lengkap event..." style="height: 150px;" required>{{ old('description') }}</textarea>
            </div>

            <div class="form-item item-info">
                <label>Waktu Pelaksanaan</label>
                
                <div class="split-inputs">
                    <div>
                        <label style="font-size: 12px;">Tanggal Mulai</label>
                        <input type="date" name="startDate" required value="{{ old('startDate') }}">
                    </div>
                    <div>
                        <label style="font-size: 12px;">Tanggal Selesai</label>
                        <input type="date" name="endDate" required value="{{ old('endDate') }}">
                    </div>
                </div>

                <div class="split-inputs">
                    <div>
                        <label style="font-size: 12px;">Jam Mulai</label>
                        <input type="time" name="startTime" required value="{{ old('startTime') }}">
                    </div>
                    <div>
                        <label style="font-size: 12px;">Jam Selesai</label>
                        <input type="time" name="endTime" required value="{{ old('endTime') }}">
                    </div>
                </div>
            </div>

            <div class="form-item item-info">
                <label>Harga Tiket (Rp)</label>
                <input type="number" name="entranceFee" placeholder="0" required value="{{ old('entranceFee') }}">
            </div>

            <button type="submit" class="submit-btn">Simpan Event</button>
        </form>
    </section>
</div> 

@include('partials.footer')

<script>
    // Fungsi untuk mengubah tampilan saat gambar dipilih
    function updateFileName() {
        const input = document.getElementById('eventImage');
        const text = document.getElementById('uploadText');
        const icon = document.getElementById('uploadIcon');

        if (input.files && input.files[0]) {
            // Ambil nama file
            const fileName = input.files[0].name;
            
            // Ubah teks menjadi nama file
            text.innerText = fileName;
            text.style.fontWeight = "bold";
            text.style.color = "#349c8b";

            // Ubah icon menjadi icon gambar/check
            icon.className = "fas fa-check-circle";
            icon.style.color = "#349c8b";
        } else {
            // Reset jika batal pilih
            text.innerText = "Add Image";
            icon.className = "fas fa-plus";
            text.style.color = "black";
            icon.style.color = "black";
        }
    }
</script>

</body>
</html>