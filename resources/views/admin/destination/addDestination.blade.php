<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jalan2Kuy.id - Add Destination</title>

    <link rel="stylesheet" href="{{ asset('css/admin/addDestination.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

@include('partials.navbarAdmin')
<!-- Mengambil nama kategori berdasarkan parameter Category dari URL -->
@php
    $catName = '';
    if(request('Category')){
        $catData = \App\Models\DestCategory::find(request('Category'));
        $catName = $catData ? $catData->categoryName : '';
    }
@endphp

<main>
    <!-- Menampilkan pesan error jika proses sebelumnya gagal -->
    @if(session('error'))
        <div style="background: #ffcccc; color: red; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center; width: 80%; margin: 0 auto;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ url('/admin/Destination/Store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="destCategoryID" value="{{ request('Category') }}">

        <!-- Input nama destinasi + tampilkan nama kategori jika ada -->
        <h1 class="edit-title">
            Add Destination @if($catName) - {{ $catName }} @endif<br>
            <div class="title-edit-box">
                <input type="text" name="name" id="titleField" class="title-input" placeholder="Nama Destinasi..." value="{{ old('name') }}" required>
                <i class="fas fa-pen title-icon"></i>
            </div>
            @error('name') <small class="error-text" style="text-align: center;">{{ $message }}</small> @enderror
        </h1>
        <!-- Grid utama berisi thumbnail, deskripsi, gambar utama, info, dan event terkait -->
        <section class="edit-grid">
            <!-- Upload dan preview thumbnail destinasi -->
            <div class="thumb-box">
                <label class="image-label">Thumbnail Image</label>
                {{-- Pakai class box-thumbnail --}}
                <div class="image-upload-box box-thumbnail">
                    <div id="thumbPlaceholder" class="placeholder">
                        <i class="far fa-image"></i>
                        <span>Preview</span>
                    </div>
                    <img id="thumbPreview" class="preview-img">
                    <input type="file" name="thumbnailImage" class="file-input-overlay" accept="image/*" onchange="previewImage(this, 'thumbPreview', 'thumbPlaceholder')" required>
                </div>
                    @error('thumbnailImage') <small class="error-text">{{ $message }}</small> @enderror
            </div>
            <!-- Textarea untuk deskripsi lengkap destinasi -->
            <div class="desc-box">
                <label>Deskripsi</label>
                <textarea name="description" id="descField" placeholder="Masukkan deskripsi lengkap..." required>{{ old('description') }}</textarea>
                @error('description') <small class="error-text">{{ $message }}</small> @enderror
            </div>

            <!-- Upload dan preview gambar utama destinasi -->
            <div class="image-box">
                <label class="image-label">Main Image</label>
                    {{-- Pakai class box-main-image --}}
                    <div class="image-upload-box box-main-image">
                    <div id="mainPlaceholder" class="placeholder">
                        <i class="far fa-image" style="font-size: 50px;"></i>
                        <span>Preview Main Image</span>
                    </div>
                    <img id="mainPreview" class="preview-img">
                    <input type="file" name="image" class="file-input-overlay" accept="image/*" onchange="previewImage(this, 'mainPreview', 'mainPlaceholder')" required>
                </div>
                @error('image') <small class="error-text">{{ $message }}</small> @enderror
            </div>

            <!-- Lokasi, hari buka, jam operasional, zona waktu, dan harga tiket -->
            <div class="info-box">
                <label>Lokasi Tempat</label>
                <input type="text" name="location" id="locField" placeholder="Alamat lengkap..." value="{{ old('location') }}" required>
                
                <label>Hari Buka</label>
                <div class="split-day">
                    <input type="text" name="openingDay" placeholder="Hari Mulai" value="{{ old('openingDay') }}" required>
                    <input type="text" name="closingDay" placeholder="Hari Selesai" value="{{ old('closingDay') }}" required>
                </div>

                <label>Jam Operasional</label>
                <div class="jam-operasional">
                    <input type="time" name="openingHours" id="openField" value="{{ old('openingHours') }}" required>
                    <span>-</span>
                    <input type="time" name="closingHours" id="closeField" value="{{ old('closingHours') }}" required>
                </div>

                <label>Zona Waktu</label>
                <input type="text" name="timezone" id="timezoneField" placeholder="WIB / WITA / WIT" value="{{ old('timezone') }}" required>

                <label>Harga Tiket Masuk (Rp)</label>
                <input type="number" name="entranceFee" id="priceField" placeholder="0" value="{{ old('entranceFee') }}" required>
            </div>

            <!-- Pilihan event terkait (bisa lebih dari satu, dropdown dinamis) -->
            <div class="event-box">
                <label>Event Terkait (Opsional)</label>
                
                <div id="event-container">
                    <div class="dynamic-event-wrapper">
                        {{-- Perhatikan name="eventID[]" menggunakan array agar bisa kirim banyak --}}
                        <select name="eventID[]" class="event-select" onchange="addNewDropdown(this)">
                            <option value="">-- Pilih Event --</option>
                            @foreach($events as $event)
                                <option value="{{ $event->eventID }}">{{ $event->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div style="text-align: right; margin-top: 5px;">
                    <a href="{{ url('/admin/event/create') }}" style="color: #3aa6a6; text-decoration: none; font-size: 12px;">
                        <i class="fas fa-plus-circle"></i> Buat Event Baru
                    </a>
                </div>
            </div>

            <!-- Tombol simpan destinasi -->
            <button type="submit" class="save-button" id="saveBtn">Tambah Destinasi</button>

        </section>
    </form>
</main>

@include('partials.footer')

<script>
    // FUNGSI PREVIEW GAMBAR
    function previewImage(input, imgId, placeholderId) {
        const preview = document.getElementById(imgId);
        const placeholder = document.getElementById(placeholderId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block'; 
                if(placeholder) placeholder.style.display = 'none'; 
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // FUNGSI NAMBAH DROPDOWN EVENT OTOMATIS
    function addNewDropdown(selectElement) {
        // Cek apakah user memilih value (bukan kosong)
        if (selectElement.value !== "") {
            // Cek apakah ini dropdown terakhir? Kita cuma mau nambah kalau yg terakhir dipilih
            const container = document.getElementById('event-container');
            const allSelects = container.querySelectorAll('select');
            const lastSelect = allSelects[allSelects.length - 1];

            // Jika elemen yang diubah adalah yang terakhir, baru tambah bawahnya
            if (selectElement === lastSelect) {
                
                // Buat wrapper baru
                const newWrapper = document.createElement('div');
                newWrapper.className = 'dynamic-event-wrapper';

                // Clone dropdown pertama
                // Kita clone node pertama biar opsinya sama persis
                const firstSelect = allSelects[0];
                const newSelect = firstSelect.cloneNode(true);

                // Reset valuenya jadi kosong
                newSelect.value = "";
                
                // Tambahkan event listener lagi ke clone yang baru
                newSelect.onchange = function() { addNewDropdown(this) };

                // Masukkan ke DOM
                newWrapper.appendChild(newSelect);
                container.appendChild(newWrapper);
            }
        }
    }
</script>

</body>
</html>