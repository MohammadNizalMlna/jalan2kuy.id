const params = new URLSearchParams(window.location.search);
const category = params.get("category");

const title = document.getElementById("category-title");
const list = document.getElementById("destination-list");
const addBtn = document.getElementById("addBtn");

const destinations = {
  nature: [
    { name: "Danau Weekuri", img: "/assets/destinasi/nature/weekuri.jpg" },
    { name: "Gunung Rinjani", img: "/assets/destinasi/nature/rinjani.jpg" },
    { name: "Bukit Merese", img: "/assets/destinasi/nature/mandalika.jpg" }
  ],
  history: [
    { name: "Candi Prambanan", img: "/assets/destinasi/history/prambanan.jpg" },
    { name: "Candi Borobudur", img: "/assets/destinasi/history/borobudur.jpg" },
    { name: "Monumen Nasional (Monas)", img: "/assets/destinasi/history/monas.jpg" }
  ],
  ecotourism: [
    { name: "Taman Nasional Komodo", img: "/assets/destinasi/ecouturism/tnKomodo.jpg" },
    { name: "Taman Nasional Way Kambas", img: "/assets/destinasi/ecouturism/tnWaskambas.jpg" },
    { name: "Taman Nasional Gunung Leuser", img: "/assets/destinasi/ecouturism/tnleuser.jpg" }
  ],
  beach: [
    { name: "Pantai Ora", img: "/assets/destinasi/beach/pantai_ora.jpg" },
    { name: "Pantai Gatra", img: "/assets/destinasi/beach/pantai-gatra.jpg" },
    { name: "Pantai Tanjung Aan", img: "/assets/destinasi/beach/Pantai-Tanjung-Aan.jpg" }
  ],
  culture: [
    { name: "Floating Market Lembang", img: "/assets/destinasi/culture/floating-market-lembang.jpg" },
    { name: "Pura Tanah Lot", img: "/assets/destinasi/culture/tanahLot.jpg" },
    { name: "Kampung Cina Jakarta", img: "/assets/destinasi/culture/kampung-cina.jpg" }
  ],
  education: [
    { name: "Taman Mini Indonesia Indah", img: "/assets/destinasi/education/tmii.jpg" },
    { name: "Taman Ismail Marzuki", img: "/assets/destinasi/education/Taman-Ismail-Marzuki.jpg" },
    { name: "Museum Pengkhianatan PKI", img: "/assets/destinasi/education/museumPki.jpg" }
  ]
};

function renderCategory() {
  // Jika kategori valid
  if (category && destinations[category]) {
    title.textContent = category.toUpperCase();
    list.innerHTML = destinations[category]
      .map(dest => `
        <div class="destination-card" 
             onclick="window.location.href='/view/destination/destination.html?id=${dest.name.toLowerCase().replace(/\s/g, '')}'">
          <div class="card-img" style="background-image: url('${dest.img}')">
            <div class="overlay">${dest.name}</div>
          </div>
        </div>
      `)
      .join('');
    if (addBtn) {
      addBtn.onclick = () => {
        window.location.href = `/view/admin/addDestination.html?category=${category}`;
      };
    }
  } 
  else {
    title.textContent = "KATEGORI TIDAK DITEMUKAN";
    list.innerHTML = `<p style="color:white; text-align:center;">Kategori ini belum memiliki data destinasi.</p>`;
  }
}

document.addEventListener("DOMContentLoaded", renderCategory);
