<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>jalan2kuy.id - Add Event</title>
    
    <link rel="stylesheet" href="{{ asset('css/event/addEvent.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<!-- Wrapper utama halaman admin -->
<div class="page-wrapper"> 

    @include('partials.navbarAdmin')

    <!-- Section utama berisi form penambahan event baru -->
    <section class="form-container">
        <h1 class="title">Add Event</h1>
    
        <!-- Menampilkan pesan error dari session Laravel (jika ada) -->
        @if(session('error'))
            <div style="background: #ffcccc; color: red; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
                {{ session('error') }}
            </div>
        @endif
        <!-- Form untuk menyimpan data event baru ke database -->
        <form id="eventForm" class="event-form" action="{{ url('/admin/Event/store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Area upload gambar utama event -->
            <div class="form-item item-image area-image">
                <i class="fas fa-plus" id="uploadIcon"></i>
                <p id="uploadText">Add Image</p>
                <input type="file" name="image" id="eventImage" accept="image/*" class="file-input" required onchange="updateFileName()">
                @error('image') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <!-- Input judul/nama event -->
            <div class="form-item item-info area-judul">
                <label>Judul Event</label>
                <input type="text" name="name" placeholder="Judul Event" required value="{{ old('name') }}">
                @error('name') <div class="error-text">{{ $message }}</div> @enderror
            </div>

            <!-- Input lokasi event -->
            <div class="form-item item-info area-lokasi">
                <label>Lokasi Event</label>
                <input type="text" name="location" placeholder="Masukkan lokasi event..." required value="{{ old('location') }}">
            </div>

            <!-- Input harga tiket masuk event -->
            <div class="form-item item-info area-harga">
                <label>Harga Tiket (Rp)</label>
                <input type="number" name="entranceFee" placeholder="0" required value="{{ old('entranceFee') }}">
            </div>

            <!-- Input akun sosial media event -->
            <div class="form-item item-info area-sosmed">
                <label>Social Media (ketikkan tanpa @)</label>
                <input type="text" name="socialMedia" placeholder="Akun Sosial Media..." required value="{{ old('socialMedia') }}">
            </div>

            <!-- Textarea untuk deskripsi lengkap event -->
            <div class="form-item item-full area-deskripsi">
                <label>Deskripsi Lengkap</label>
                <textarea name="description" placeholder="Masukkan deskripsi lengkap event..." required>{{ old('description') }}</textarea>
            </div>

            <!-- Input tanggal dan jam pelaksanaan event -->
            <div class="form-item item-info area-waktu">
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

            <!-- Tombol untuk menyimpan event -->
            <button type="submit" class="submit-btn">Simpan Event</button>
        </form>
    </section>
</div> 

@include('partials.footer')

<script>
    function updateFileName() {
        const input = document.getElementById('eventImage');
        const text = document.getElementById('uploadText');
        const icon = document.getElementById('uploadIcon');

        if (input.files && input.files[0]) {
            const fileName = input.files[0].name;
            text.innerText = fileName;
            text.style.fontWeight = "bold";
            text.style.color = "#349c8b";
            icon.className = "fas fa-check-circle";
            icon.style.color = "#349c8b";
        } else {
            text.innerText = "Add Image";
            icon.className = "fas fa-plus";
            text.style.color = "black";
            icon.style.color = "black";
        }
    }
</script>

</body>
</html>