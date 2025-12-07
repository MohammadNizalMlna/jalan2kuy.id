<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jalan2kuy.id - Verifikasi Login</title>
    <link rel="stylesheet" href="{{ asset('css/akun/passkey.css') }}">
    <style>
        /* Tambahan style untuk pesan error */
        .alert-error {
            background-color: #ffcccc; 
            color: red; 
            padding: 10px; 
            border-radius: 5px; 
            margin-bottom: 15px; 
            text-align: center;
        }
    </style>
</head>
<body>

<header class="navbar">
    <a href="{{ url('/login') }}" class="back-btn">
        <img src="{{ asset('assets/gambar/icon/return.png') }}" alt="kembali">
    </a>
    <div class="logo">
        <img src="{{ asset('assets/gambar/icon/logo.png') }}" alt="Logo jalan2kuy.id">
    </div>
</header>

<div class="container">
    <h2>Verifikasi Login</h2>
    <p style="text-align: center; color: #666; font-size: 14px;">Masukkan 6 digit kode keamanan.</p>

    @if(session('error'))
        <div class="alert-error">
            {{ session('error') }}
        </div>
    @endif

    <form id="passkeyForm" action="{{ url('/verifikasi-login-proses') }}" method="POST">
        @csrf
        
        <input type="hidden" name="passkey_code" id="full_code">

        <div class="input-group">
            <input type="text" maxlength="1" class="otp-input" required>
            <input type="text" maxlength="1" class="otp-input" required>
            <input type="text" maxlength="1" class="otp-input" required>
            <input type="text" maxlength="1" class="otp-input" required>
            <input type="text" maxlength="1" class="otp-input" required>
            <input type="text" maxlength="1" class="otp-input" required>
        </div>

        <button type="button" class="submit-btn" id="submitBtn">Verifikasi</button>
    </form>
</div>

<script>
    // OTP Input System
    const inputs = document.querySelectorAll(".otp-input");
    const submitBtn = document.getElementById("submitBtn");
    const fullCodeInput = document.getElementById("full_code");
    const form = document.getElementById("passkeyForm");

    inputs[0].focus();

    inputs.forEach((input, index) => {
        input.addEventListener("input", () => {
            input.value = input.value.replace(/[^0-9]/g, ""); 
            if (input.value && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
            // Izinkan submit dengan enter
            if (e.key === "Enter") {
                submitBtn.click();
            }
        });
    });

    submitBtn.addEventListener("click", () => {
        // 1. Gabungkan semua angka dari kotak input
        const code = [...inputs].map(i => i.value).join("");

        // 2. Validasi panjang
        if (code.length !== 6) {
            alert("Kode belum lengkap!");
            return;
        }

        // 3. Masukkan ke input hidden
        fullCodeInput.value = code;

        // 4. Ubah tombol jadi loading (Opsional UI)
        submitBtn.innerText = "Memproses...";
        submitBtn.style.background = "#888";

        // 5. Submit Form ke Laravel
        form.submit();
    });
</script>

</body>
</html>