<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jalan2Kuy.id</title>
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
</head>
<body>

    @include('partials.navbar')
    
    <!-- Section hero berisi slideshow utama sebagai highlight website -->
    <section class="hero slideshow-container">
        <!-- slide promosi destinasi wisata -->
        <div class="myslides" style="background-image: url('{{ asset('assets/gambar/bgfix.jpg') }}');">
            <div class="hero-content">
                <h1>Temukan Petualanganmu</h1>
                <h2>Bersama <span>Jalan2Kuy.id</span></h2>
                <button class="cta-btn" onclick="window.location.href='{{ url('/Destination') }}'">Mulai Jelajahi</button>
            </div>
        </div>
        
        <!-- slide promosi event -->
        <div class="myslides" style="background-image: url('{{ asset('assets/gambar/bgswap.jpg') }}');">
            <div class="hero-content">
                <h1>Jelajahi Budaya Lewat</h1>
                <h2>Event Unik Nusantara <span>Jalan2Kuy.id</span></h2>
                <button class="cta-btn" onclick="window.location.href='{{ url('/Event') }}'">Cari Event</button>
            </div>
        </div>
        
        <!--  Slide kampanye sosial dan edukasi lingkungan -->
        <div class="myslides" style="background-image: url('{{ asset('assets/gambar/raja-ampat-dikeruk.jpeg') }}');">
            <div class="hero-content">
                <h1>Ayo Kita Suarakan</h1>
                <h2>#SaveRajaAmpat di <span>Jalan2Kuy.id</span></h2>
                <a href="https://www.greenpeace.org/indonesia/petitions/save-raja-ampat/" target="_blank" class="cta-btn">Cek Berita Terkini</a>
            </div>
        </div>

        <!-- Tombol navigasi slide sebelumnya dan berikutnya -->
        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>

        <!-- Indicator titik untuk berpindah ke slide tertentu -->
        <div class="dot-indicator">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
        </div>
    </section>

    @include('partials.footer') 

    <script>
        
        var slideIndex = 1;
        var timer;

        showSlides(slideIndex); 

        // untuk pindah slide ke next atau prev
        function plusSlides(n) {
            clearTimeout(timer); 
            showSlides(slideIndex += n);
        }
        //untuk langsung loncat ke slide tertentu
        function currentSlide(n) {
            clearTimeout(timer);
            showSlides(slideIndex = n);
        }
        //mengatur logika slide entah manual atau otomatis
        function showSlides(n) {
            var i;
            var slides = document.getElementsByClassName("myslides");
            var dots = document.getElementsByClassName("dot");

            if (n === undefined) { 
                slideIndex++;
            } else { 
                slideIndex = n;
            }

            if(slideIndex > slides.length) {
                slideIndex = 1
            }
            if (slideIndex < 1) {
                slideIndex = slides.length
            }

            for (i = 0; i < slides.length; i++) {
                slides[i].style.transform = 'translateX(100%)'; 
                slides[i].style.display = 'none'; 
                
                // Pengecekan keamanan agar JS tidak error jika elemen dot belum ada
                if(dots[i]) {
                    dots[i].className = dots[i].className.replace(" active", "");
                }
            }
            
            // Pengecekan keamanan agar JS tidak error
            if(slides[slideIndex-1]) {
                slides[slideIndex-1].style.display = 'flex';
                slides[slideIndex-1].style.transform = 'translateX(0)'; 
            }
            
            if (dots.length > 0 && dots[slideIndex-1]) {
                dots[slideIndex-1].className += " active";
            }

            clearTimeout(timer); 
            timer = setTimeout(showSlides, 5000); 
        }
    </script>

</body>
</html>