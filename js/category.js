const params = new URLSearchParams(window.location.search);
const category = params.get("category");

const title = document.getElementById("category-title");
const list = document.getElementById("destination-list");
const addBtn = document.getElementById("addBtn");

const destinations = {
  nature: [
    { id: "weekuri", name: "Danau Weekuri", img: "/assets/destinasi/nature/weekuri.jpg" },
    { id: "rinjani", name: "Gunung Rinjani", img: "/assets/destinasi/nature/rinjani.jpg" },
    { id: "merese",name: "Bukit Merese", img: "/assets/destinasi/nature/mandalika.jpg" }
  ],
  history: [
    { id: "prambanan", name: "Candi Prambanan", img: "/assets/destinasi/history/prambanan.jpg" },
    { id: "borobudur", id: "borobudur",name: "Candi Borobudur", img: "/assets/destinasi/history/borobudur.jpg" },
    { id: "monas", name: "Monumen Nasional (Monas)", img: "/assets/destinasi/history/monas.jpg" }
  ],
  ecotourism: [
    { id: "tnkomodo", name: "Taman Nasional Komodo", img: "/assets/destinasi/ecouturism/tnKomodo.jpg" },
    { id: "tnwaykambas", name: "Taman Nasional Way Kambas", img: "/assets/destinasi/ecouturism/tnWaskambas.jpg" },
    { id: "tnleuser", name: "Taman Nasional Gunung Leuser", img: "/assets/destinasi/ecouturism/tnleuser.jpg" }
  ],
  beach: [
    { id: "pantaiora", name: "Pantai Ora", img: "/assets/destinasi/beach/pantai_ora.jpg" },
    { id: "pantaigatra", name: "Pantai Gatra", img: "/assets/destinasi/beach/pantai-gatra.jpg" },
    { id: "pantaitanjungaan", name: "Pantai Tanjung Aan", img: "/assets/destinasi/beach/Pantai-Tanjung-Aan.jpg" }
  ],
  culture: [
    { id: "floatingmarket", name: "Floating Market Lembang", img: "/assets/destinasi/culture/floating-market-lembang.jpg" },
    { id: "puratanahlot", name: "Pura Tanah Lot", img: "/assets/destinasi/culture/tanahLot.jpg" },
    { id: "kcjakarta", name: "Kampung Cina Jakarta", img: "/assets/destinasi/culture/kampung-cina.jpg" }
  ],
  education: [
    { id: "tmii", name: "Taman Mini Indonesia Indah", img: "/assets/destinasi/education/tmii.jpg" },
    { id: "tamanim", name: "Taman Ismail Marzuki", img: "/assets/destinasi/education/Taman-Ismail-Marzuki.jpg" },
    { id: "museumpki", name: "Museum Pengkhianatan PKI", img: "/assets/destinasi/education/museumPki.jpg" }
  ]
};

function renderCategory() {
  if (category && destinations[category]) {
    title.textContent = category.toUpperCase();
    list.innerHTML = destinations[category]
      .map(dest => `
        <div class="destination-card" 
            onclick="window.location.href='/view/admin/destinasi/destinationDetail.html?id=${dest.id}&category=${category}'">
          <div class="card-img" style="background-image: url('${dest.img}')">
            <div class="overlay">${dest.name}</div>
          </div>
        </div>`).join('');
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
