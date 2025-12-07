const params = new URLSearchParams(window.location.search);
const category = params.get("category");

const title = document.getElementById("category-title");
const list = document.getElementById("destination-list");
const addBtn = document.getElementById("addBtn");

function renderCategory() {

  // cek kategori valid
  if (!category || !allDestinations[category]) {
    title.textContent = "KATEGORI TIDAK DITEMUKAN";
    list.innerHTML = `<p style="color:white;text-align:center;">Data tidak ditemukan.</p>`;
    return;
  }

  // set judul
  title.textContent = category.toUpperCase();

  // render card
  list.innerHTML = allDestinations[category]
    .map(dest => `
      <a class="destination-card"
        href="/view/admin/destinasi/destinationDetail.html?id=${dest.id}&category=${category}">
        <div class="card-img" style="background-image:url('${dest.thumbnail || dest.image}')">
          <div class="overlay">${dest.name}</div>
        </div>
      </a>
    `).join("");

  // set tombol add
  addBtn.href = `/view/admin/addDestination.html?category=${category}`;
}

document.addEventListener("DOMContentLoaded", renderCategory);
