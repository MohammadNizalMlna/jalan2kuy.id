const params = new URLSearchParams(window.location.search);
const category = params.get("category");

const title = document.getElementById("category-title");
const list = document.getElementById("destination-list");

function renderCategoryUser() {

  if (!category || !allDestinations[category]) {
    title.textContent = "KATEGORI TIDAK DITEMUKAN";
    list.innerHTML = `<p style="color:white;text-align:center;">Data tidak ditemukan.</p>`;
    return;
  }

  title.textContent = category.toUpperCase();

  list.innerHTML = allDestinations[category]
    .map(dest => `
      <a class="destination-card"
        href="/view/destination/destinationUserDetail.html?id=${dest.id}&category=${category}">
        <div class="card-img" style="background-image:url('${dest.thumbnail || dest.image}')">
          <div class="overlay">${dest.name}</div>
        </div>
      </a>
    `)
    .join("");
}

document.addEventListener("DOMContentLoaded", renderCategoryUser);
