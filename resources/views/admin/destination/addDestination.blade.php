<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jalan2Kuy.id - Add Destination (Admin)</title>

    {{-- Asset CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin/addDestinasi.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        /* CSS Tambahan untuk Preview Gambar */
        .image-preview-box {
            width: 100%;
            height: 150px;
            border: 2px dashed #ccc;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
            background: #f9f9f9;
            margin-bottom: 10px;
        }
        .image-preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none; 
        }
        .error-text {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
    </style>
</head>

<body>

@include('partials.navbarAdmin')

{{-- SCRIPT PENCARI NAMA KATEGORI --}}
@php
    $catName = '';
    // Jika ada parameter Category di URL (isinya ID)
    if(request('Category')){
        // Cari data kategori berdasarkan ID tersebut
        $catData = \App\Models\DestCategory::find(request('Category'));
        // Jika ketemu, ambil namanya. Jika tidak, kosongkan.
        $catName = $catData ? $catData->categoryName : '';
    }
@endphp

<h1 class="page-title">Add Destination @if($catName) - {{ $catName }} @endif</h1>

<div class="form-container">
    @if(session('error'))
        <div style="background: #ffcccc; color: red; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center;">
            {{ session('error') }}
        </div>
    @endif

    <form class="destination-form" action="{{ url('/admin/Destination/Store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        {{-- Input Hidden tetap menyimpan ID untuk dikirim ke Controller --}}
        <input type="hidden" name="category" value="{{ request('Category') }}">

        <div class="form-item item-thumbnail">
            <label>Thumbnail Image</label>
            <div class="image-preview-box">
                <img id="previewThumbnail" src="#" alt="Preview">
                <div id="placeholderThumbnail" style="text-align: center; color: #888;">
                    <i class="fas fa-image fa-2x"></i><br>Preview
                </div>
            </div>
            
            <input type="file" name="thumbnailImage" accept="image/*" class="file-input" required onchange="previewImage(this, 'previewThumbnail', 'placeholderThumbnail')">
            @error('thumbnailImage') <small class="error-text">{{ $message }}</small> @enderror
        </div>

        <div class="form-item">
            <label for="name">Nama Destinasi</label>
            <input type="text" name="name" id="name" placeholder="Masukkan nama destinasi..." required value="{{ old('name') }}" 
                   style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-top: 5px; box-sizing: border-box;">
            @error('name') <small class="error-text">{{ $message }}</small> @enderror
        </div>

        <div class="form-item item-deskripsi">
            <label for="description">Deskripsi</label>
            <textarea name="description" id="description" placeholder="Masukkan deskripsi panjang destinasi..." required>{{ old('description') }}</textarea>
            @error('description') <small class="error-text">{{ $message }}</small> @enderror
        </div>

        <div class="form-item item-add-image">
             <label>Main Image</label>
             <div class="image-preview-box">
                <img id="previewMain" src="#" alt="Preview">
                <div id="placeholderMain" style="text-align: center; color: #888;">
                    <i class="fas fa-image fa-2x"></i><br>Preview
                </div>
            </div>

            <input type="file" name="image" id="galleryImg" accept="image/*" class="file-input" required onchange="previewImage(this, 'previewMain', 'placeholderMain')">
            @error('image') <small class="error-text">{{ $message }}</small> @enderror
        </div>

        <div class="form-item item-info">
            
            <div class="info-row">
                <label for="location">Lokasi Tempat</label>
                <input type="text" name="location" id="lokasi" placeholder="Masukkan lokasi destinasi..." required value="{{ old('location') }}">
                @error('location') <small class="error-text">{{ $message }}</small> @enderror
            </div>

            <div class="info-row">
                <label>Hari Operasional</label>
                <div style="display: flex; gap: 10px;">
                    <div style="flex:1">
                        <input type="text" name="openingDay" placeholder="Hari Mulai (ex: Senin)" required value="{{ old('openingDay') }}">
                    </div>
                    <div style="flex:1">
                        <input type="text" name="closingDay" placeholder="Hari Selesai (ex: Minggu)" required value="{{ old('closingDay') }}">
                    </div>
                </div>
                @error('openingDay') <small class="error-text">{{ $message }}</small> @enderror
                @error('closingDay') <small class="error-text">{{ $message }}</small> @enderror
            </div>

            <div class="info-row">
                <label>Jam Operasional</label>
                <div class="jam-operasional">
                    <input type="time" name="openingHours" id="jam-buka" required value="{{ old('openingHours') }}">
                    <span>-</span>
                    <input type="time" name="closingHours" id="jam-tutup" required value="{{ old('closingHours') }}">
                </div>
                @error('openingHours') <small class="error-text">{{ $message }}</small> @enderror
            </div>

            <div class="info-row">
                <label>Zona Waktu</label>
                <input type="text" name="timezone" id="timezone" placeholder="WIB / WITA / WIT" required value="{{ old('timezone') }}">
                @error('timezone') <small class="error-text">{{ $message }}</small> @enderror
            </div>

            <div class="info-row">
                <label for="harga">Harga Tiket Masuk (Rp)</label>
                <input type="number" name="entranceFee" id="harga" placeholder="contoh: 25000" min="0" required value="{{ old('entranceFee') }}">
                @error('entranceFee') <small class="error-text">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-item item-event">
            <label for="eventSelect">Event Terkait (Opsional)</label>
            <div class="event-row">
                <select name="eventID" id="eventSelect">
                    <option value="">-- Pilih Event (Jika Ada) --</option>
                    @foreach($events as $event)
                        <option value="{{ $event->eventID }}">{{ $event->name }}</option>
                    @endforeach
                </select>
                
                <a href="{{ url('/admin/event/create') }}" class="add-event-btn" title="Buat Event Baru" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>

        <button type="submit" class="submit-button">Tambah Destinasi</button>
    </form>
</div>

@include('partials.footer')

<script>
    function previewImage(input, imgId, placeholderId) {
        const preview = document.getElementById(imgId);
        const placeholder = document.getElementById(placeholderId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>
</html>