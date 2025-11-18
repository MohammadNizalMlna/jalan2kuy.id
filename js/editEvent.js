const params = new URLSearchParams(window.location.search);
const eventId = params.get("id");


// Ambil dari localStorage
let storedEvents = JSON.parse(localStorage.getItem("events"));

if (!storedEvents || storedEvents.length === 0) {
  storedEvents = [...events];  
}

// Cari event berdasarkan ID
let eventData = storedEvents.find(e => e.id === eventId);


// Ambil elemen-elemen form
const imageBox = document.getElementById("imageBox");
const eventImage = document.getElementById("eventImage");


// ===============================
// ISI FORM DENGAN DATA EVENT
// ===============================
if (eventData) {
  document.getElementById("title").value = eventData.title;
  document.getElementById("location").value = eventData.location;
  document.getElementById("date").value = eventData.date;
  document.getElementById("desc").value = eventData.description;

  // Convert <br> ke newline agar masuk textarea dengan rapi
  document.getElementById("fullDesc").value =
    eventData.fullDescription
      ? eventData.fullDescription.replace(/<br>/g, "\n")
      : "";

  document.getElementById("sideInfo").value =
    eventData.sideInfo
      ? eventData.sideInfo.replace(/<br>/g, "\n")
      : "";

  // Tampilkan gambar jika ada
  if (eventData.image) {
    imageBox.style.backgroundImage = `url('${eventData.image}')`;
    imageBox.style.backgroundSize = "cover";
    imageBox.style.backgroundPosition = "center";

    imageBox.querySelector("i").style.display = "none";
    imageBox.querySelector("p").style.display = "none";
  }
}



// ===============================
// GANTI GAMBAR EVENT
// ===============================
eventImage.addEventListener("change", function () {
  const file = this.files[0];
  if (!file) return;

  const reader = new FileReader();

  reader.onload = e => {
    const imgURL = e.target.result;

    imageBox.style.backgroundImage = `url('${imgURL}')`;
    imageBox.style.backgroundSize = "cover";
    imageBox.style.backgroundPosition = "center";

    imageBox.querySelector("i").style.display = "none";
    imageBox.querySelector("p").style.display = "none";

    // Simpan sementara ke objek event
    eventData.image = imgURL;
  };

  reader.readAsDataURL(file);
});

document.getElementById("saveBtn").onclick = () => {

  // Update event
  eventData.title = document.getElementById("title").value;
  eventData.location = document.getElementById("location").value;
  eventData.date = document.getElementById("date").value;
  eventData.description = document.getElementById("desc").value;

  eventData.fullDescription =
    document.getElementById("fullDesc").value.replace(/\n/g, "<br>");

  eventData.sideInfo =
    document.getElementById("sideInfo").value.replace(/\n/g, "<br>");

  // Simpan seluruh event ke localStorage
  localStorage.setItem("events", JSON.stringify(storedEvents));

  alert("Event berhasil diperbarui!");
  window.location.href = "/view/admin/eventAdmin/eventAdmin.html";
};
