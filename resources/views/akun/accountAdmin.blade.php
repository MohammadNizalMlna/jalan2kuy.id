<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jalan2kuy.id - Menu Akun</title>
    <link rel="stylesheet" href="{{ asset('css/akun/akunmenu.css') }}"> 
</head>
<body>

    <!-- ================= HEADER / TOP BAR ================= -->
    <header class="header-bar">
        <button class="icon-btn back-btn" onclick="window.location.href='{{ url('admin/Homepage') }}'">
            <img src="{{ asset('assets/gambar/icon/return.png') }}" alt="Kembali">
        </button>
        
        <div class="header-content-inner">
            <div class="title-container">
                <div class="account-icon">
                    <img src="{{ asset('assets/gambar/icon/acount.png') }}" alt="Ikon Akun">
                </div>
                <h1>Account</h1>
            </div>
        </div>
    </header>

    <!-- KONTEN UTAMA -->
    <div class="main-wrapper-content">
        <div class="content-area">
            
            <!-- menampilkan pesan sukses atau error dari session laravel -->
            @if(session('success'))
                <div style="color: green; text-align: center; margin-bottom: 15px;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div style="color: red; text-align: center; margin-bottom: 15px;">{{ session('error') }}</div>
            @endif
            
            <!-- Menampilkan data akun admin yang sedang login -->
            <div class="profile-card">
                <div class="info-item"><strong>Nama:</strong> {{ $admin->name }}</div>
                <div class="info-item"><strong>Username:</strong> {{ $admin->username }}</div>
                <div class="info-item"><strong>Email:</strong> {{ $admin->email }}</div>
                
                <div class="info-item"><strong>Password:</strong> ********</div>
                
                <div class="info-item"><strong>Jenis Kelamin:</strong> {{ $admin->gender ? 'Laki-laki' : 'Perempuan' }}</div>


                <!-- Tombol aksi akun -->
                <button class="action-btn edit-btn" id="editProfileButton" onclick="window.location.href='{{ url('/admin/Edit-Profile') }}'">Edit Profile</button>
                <button class="action-btn logout-btn" id="logoutButton">Logout</button>
                <button class="action-btn delete-btn" id="deleteButton">Delete Account</button>
            </div>
        </div>
    </div>

    <!-- Modal konfirmasi sebelum logout akun -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <p>Apakah anda yakin untuk logout Akun?</p>
            <div class="modal-actions">
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                
                <button id="confirmLogoutYes" class="modal-btn yes-btn">Yes</button>
                <button id="confirmLogoutNo" class="modal-btn no-btn">NO</button>
            </div>
        </div>
    </div>

    <!-- Modal konfirmasi penghapusan akun admin -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <p>Apakah anda yakin ingin menghapus akun anda?</p>
            <div class="modal-actions">
                <form id="delete-form" action="{{ url('/admin/delete-account') }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE') </form>

                <button id="confirmDeleteYes" class="modal-btn yes-btn">Yes</button>
                <button id="confirmDeleteNo" class="modal-btn no-btn">NO</button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal Elements
        const logoutButton = document.getElementById('logoutButton');
        const logoutModal = document.getElementById('logoutModal');
        const confirmLogoutYes = document.getElementById('confirmLogoutYes');
        const confirmLogoutNo = document.getElementById('confirmLogoutNo');
        
        const deleteButton = document.getElementById('deleteButton');
        const deleteModal = document.getElementById('deleteModal');
        const confirmDeleteYes = document.getElementById('confirmDeleteYes');
        const confirmDeleteNo = document.getElementById('confirmDeleteNo');

        // --- LOGIKA LOGOUT ---
        logoutButton.onclick = () => logoutModal.style.display = "block";
        confirmLogoutNo.onclick = () => logoutModal.style.display = "none";
        
        // Saat Yes diklik, submit form logout Laravel
        confirmLogoutYes.onclick = () => {
            document.getElementById('logout-form').submit();
        };

        // --- LOGIKA DELETE ---
        deleteButton.onclick = () => deleteModal.style.display = "block";
        confirmDeleteNo.onclick = () => deleteModal.style.display = "none";
        
        // Saat Yes diklik, submit form delete Laravel
        confirmDeleteYes.onclick = () => {
            document.getElementById('delete-form').submit();
        };

        // Klik luar modal untuk menutup
        window.onclick = function(event) {
            if (event.target === logoutModal) logoutModal.style.display = "none";
            if (event.target === deleteModal) deleteModal.style.display = "none";
        }
    });
    </script>

</body>
</html>