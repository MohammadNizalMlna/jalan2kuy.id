<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
    /* Variabel root CSS sebaiknya ada di CSS utama, tapi oke disimpan disini untuk sementara */
    .footer {
        width: 100%;
        background: #17423c; /* Hardcode warna jika variabel tidak terbaca */
        padding: 65px 20px 35px;
        color: #d6f5cf;
        font-family: 'Poppins', Arial, sans-serif;
    }

    .footer-content {
        max-width: 1200px;
        margin: auto;
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 50px;
        align-items: start;
    }

    .footer-about { display: flex; flex-direction: column; gap: 18px; }
    .footer-logo { display: flex; align-items: center; gap: 12px; }
    
    .footer-logo img {
        height: 40px;
        filter: brightness(0) invert(1);
    }

    .footer-about p {
        margin: 0;
        font-size: 0.95rem;
        line-height: 1.8;
        color: #ffffff;
    }

    .footer-contact h4 {
        margin: 0 0 20px 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: #ffffff;
    }

    .contact-item { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px; }
    .contact-item i { font-size: 18px; margin-top: 3px; color: #ffffff; }
    .contact-item span { color: #d6f5cf; font-size: 0.95rem; line-height: 1.5; }

    .footer-bottom {
        margin-top: 45px;
        text-align: center;
        border-top: 1px solid rgba(255,255,255,0.15);
        padding-top: 20px;
        font-size: 0.85rem;
        color: #ffffff;
    }

    @media (max-width: 900px) {
        .footer-content { grid-template-columns: 1fr; text-align: left; }
    }

    @media (max-width: 600px) {
        .footer { padding: 45px 20px 30px; }
        .footer-logo img { height: 32px; }
        .footer-bottom { margin-top: 30px; }
    }
</style>

<footer class="footer">
    <div class="footer-content">

        <div class="footer-about">
            <div class="footer-logo">
                <img src="{{ asset('assets/gambar/icon/logo.png') }}" alt="Logo Jalan2kuy">
            </div>

            <p>
                jalan2kuy.id adalah platform informasi wisata Indonesia yang menghadirkan inspirasi perjalanan, destinasi alam, budaya, dan ekowisata terbaik nusantara.
                Temukan rekomendasi wisata, panduan perjalanan, hingga event menarik untuk liburan yang lebih berkesan.
            </p>
        </div>

        <div class="footer-contact">
            <h4>Kontak</h4>

            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>Jl. DI Panjaitan No.128, Purwokerto Kidul</span>
            </div>

            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <span>(0281) 641 629</span>
            </div>

            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <span>info@telkomuniversity.ac.id</span>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        &copy; 2025 jalan2kuy.id — All rights reserved.
    </div>
</footer>