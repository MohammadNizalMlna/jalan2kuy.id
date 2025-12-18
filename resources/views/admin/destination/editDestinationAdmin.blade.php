<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jalan2kuy.id - Edit Destination</title>

    {{-- Asset CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin/editDestination.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        .error-text { color: red; font-size: 12px; display: block; margin-top: 5px; }
        .split-day { display: flex; gap: 10px; margin-bottom: 10px; }
        .split-day input { width: 50%; }

        /* STYLE KHUSUS DYNAMIC DROPDOWN */
        .dynamic-event-wrapper {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .dynamic-event-wrapper select {
            flex: 1; /* Dropdown memenuhi ruang */
        }
        .remove-event-btn {
            background: #ff4d4d;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: 0.2s;
        }
        .remove-event-btn:hover {
            background: #cc0000;
        }
    </style>
</head>
<body>

@include('partials.navbarAdmin')

<main>
    <!-- Menampilkan pesan error dari session Laravel -->
    @if(session('error'))
        <div style="background: #ffcccc; color: red; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center; width: 80%; margin: 0 auto;">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form update destinasi menggunakan method PUT -->
    <form action="{{ url('/admin/Destination/Update/' . $destination->destinationID) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Edit nama destinasi secara langsung -->
        <h1 class="edit-title">
            Edit<br>
            <div class="title-edit-box">
                <input type="text" name="name" id="titleField" class="title-input" value="{{ old('name', $destination->name) }}" required>
                <i class="fas fa-pen title-icon"></i>
            </div>
            @error('name') <small class="error-text" style="font-size: 14px; font-weight: normal;">{{ $message }}</small> @enderror
        </h1>

        <!-- Layout utama edit destinasi -->
        <section class="edit-grid">
            <!-- Edit dan preview thumbnail destinasi -->
            <div class="thumb-box">
                <img id="thumbPreview" class="thumb-img" src="{{ asset('storage/' . $destination->thumbnailImagePath) }}">
                <label class="edit-thumb-btn">
                    <i class="fas fa-pen"></i> Change Thumbnail
                    <input type="file" name="thumbnailImage" id="thumbInput" accept="image/*" onchange="previewImage(this, 'thumbPreview')">
                </label>
            </div>

            <!-- Edit deskripsi destinasi -->
            <div class="desc-box">
                <label>Deskripsi</label>
                <textarea name="description" id="descField" required>{{ old('description', $destination->description) }}</textarea>
            </div>

            <!-- Edit dan preview gambar utama destinasi -->
            <div class="image-box">
                <img id="imagePreview" class="main-img" src="{{ asset('storage/' . $destination->imagePath) }}">
                <label class="edit-img-btn">
                    <i class="fas fa-pen"></i> Change Image
                    <input type="file" name="image" id="imgInput" accept="image/*" onchange="previewImage(this, 'imagePreview')">
                </label>
            </div>

            <!-- Lokasi, hari buka, jam operasional, zona waktu, dan harga -->
            <div class="info-box">
                <label>Lokasi Tempat</label>
                <input type="text" name="location" id="locField" value="{{ old('location', $destination->location) }}" required>

                <label>Hari Buka</label>
                <div class="split-day">
                    <input type="text" name="openingDay" placeholder="Hari Mulai" value="{{ old('openingDay', $destination->openingDay) }}" required>
                    <input type="text" name="closingDay" placeholder="Hari Selesai" value="{{ old('closingDay', $destination->closingDay) }}" required>
                </div>

                <label>Jam Operasional</label>
                <div class="jam-operasional">
                    <input type="time" name="openingHours" id="openField" value="{{ old('openingHours', \Carbon\Carbon::parse($destination->openingHours)->format('H:i')) }}" required>
                    <span>-</span>
                    <input type="time" name="closingHours" id="closeField" value="{{ old('closingHours', \Carbon\Carbon::parse($destination->closingHours)->format('H:i')) }}" required>
                </div>

                <label>Zona Waktu</label>
                <input type="text" name="timezone" id="timezoneField" value="{{ old('timezone', $destination->timezone) }}" required>

                <label>Harga Tiket Masuk (Rp)</label>
                <input type="number" name="entranceFee" id="priceField" value="{{ old('entranceFee', $destination->entranceFee) }}" required>
            </div>

            <!-- Mengelola relasi event: edit, hapus, dan tambah event -->
            <div class="event-box">
                <label>Event Terkait</label>
                <div id="event-container">
                    
                    {{-- 1. LOOPING EVENT YANG SUDAH ADA (EXISTING) --}}
                    {{-- UBAH DISINI: Pakai $currentEvents, JANGAN $destination->events --}}
                    @foreach($currentEvents as $existingEvent)
                        <div class="dynamic-event-wrapper">
                            {{-- Dropdown Event --}}
                            <select name="eventID[]" class="event-select">
                                <option value="{{ $existingEvent->eventID }}" selected>
                                    {{ $existingEvent->name }}
                                </option>
                                {{-- Opsi event lain untuk diganti --}}
                                @foreach($events as $ev)
                                    @if($ev->eventID !== $existingEvent->eventID)
                                        <option value="{{ $ev->eventID }}">{{ $ev->name }}</option>
                                    @endif
                                @endforeach
                            </select>

                            <button type="button" class="remove-event-btn" onclick="removeEvent(this, '{{ $existingEvent->eventID }}')">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    @endforeach

                    {{-- 2. DROPDOWN KOSONG UNTUK NAMBAH BARU (Sama seperti Add Page) --}}
                    <!-- Dropdown kosong untuk menambah event baru -->
                    <div class="dynamic-event-wrapper" id="template-dropdown">
                        <select name="eventID[]" class="event-select" onchange="addNewDropdown(this)">
                            <option value="">-- Tambah Event Baru --</option>
                            @foreach($events as $event)
                                {{-- Hanya tampilkan event yang belum dipilih destinasi ini (opsional filter di controller lebih baik) --}}
                                <option value="{{ $event->eventID }}">{{ $event->name }}</option>
                            @endforeach
                        </select>
                        {{-- Dropdown kosong tidak butuh tombol hapus di awal --}}
                    </div>

                </div>
                
                <small style="color: #ffff; font-size: 11px; display:block; margin-top:5px;">
                    *Klik ikon (-) untuk menghapus relasi event. Pilih event baru di bawah untuk menambah.
                </small>
            </div>
            <!-- Simpan perubahan destinasi -->
            <button type="submit" class="save-button" id="saveBtn">Simpan Perubahan</button>

        </section>
    </form>
</main>

@include('partials.footer')

<script>
    // Preview Gambar
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) { preview.src = e.target.result; }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Fungsi Tambah Dropdown Baru
    function addNewDropdown(selectElement) {
        if (selectElement.value !== "") {
            const container = document.getElementById('event-container');
            const allSelects = container.querySelectorAll('select');
            const lastSelect = allSelects[allSelects.length - 1];

            if (selectElement === lastSelect) {
                const newWrapper = document.createElement('div');
                newWrapper.className = 'dynamic-event-wrapper';

                // Clone dropdown "template" (yang paling bawah)
                // Kita ambil list options lengkap dari variabel PHP di server, tapi di client kita clone element select terakhir
                const templateSelect = document.getElementById('template-dropdown').querySelector('select');
                const newSelect = templateSelect.cloneNode(true);
                
                newSelect.value = ""; // Reset value
                newSelect.onchange = function() { addNewDropdown(this) };

                // Tambahkan tombol Hapus juga untuk dropdown baru ini (biar bisa di-cancel)
                const removeBtn = document.createElement('button');
                removeBtn.type = "button";
                removeBtn.className = "remove-event-btn";
                removeBtn.innerHTML = '<i class="fas fa-minus"></i>';
                removeBtn.onclick = function() { newWrapper.remove(); };

                newWrapper.appendChild(newSelect);
                newWrapper.appendChild(removeBtn);
                container.appendChild(newWrapper);
            }
        }
    }

    // Fungsi Hapus Event Existing
    function removeEvent(button, eventID) {
        // Hapus elemen visual dari DOM
        const wrapper = button.parentElement;
        wrapper.remove();

        // Optional: Jika ingin benar-benar menghapus relasi di database saat itu juga (AJAX) bisa disini.
        // Tapi karena kita pakai Form Submit, kita perlu trik:
        // Controller perlu tahu event mana yang "hilang" dari list submission.
        // Logic Update Controller akan menangani sinkronisasi ID yang dikirim.
    }
</script>

</body>
</html>