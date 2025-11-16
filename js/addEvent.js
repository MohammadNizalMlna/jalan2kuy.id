// Cek event lama dari localStorage
let storedEvents = JSON.parse(localStorage.getItem("events")) || [];

// Input File Preview
const imageInput = document.getElementById("eventImage");
const imageBox = document.querySelector(".item-image");

imageInput.addEventListener("change", function() {
    const file = this.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        imageBox.style.backgroundImage = `url('${e.target.result}')`;
        imageBox.style.backgroundSize = "cover";
        imageBox.style.backgroundPosition = "center";
        imageBox.querySelector("i").style.display = "none";
        imageBox.querySelector("p").style.display = "none";
    };
    reader.readAsDataURL(file);
});

// FORM SUBMIT
document.getElementById("eventForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const file = document.getElementById("eventImage").files[0];
    if (!file) {
        alert("Gambar event wajib diupload!");
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {

        const newEvent = {
            id: document.getElementById("title").value.toLowerCase().replace(/\s+/g, ""),
            title: document.getElementById("title").value,
            location: document.getElementById("location").value,
            date: document.getElementById("date").value,
            image: e.target.result,  // Base64 string
            description: document.getElementById("desc").value,
            fullDescription: document.getElementById("fullDesc").value.replace(/\n/g, "<br>"),
            sideInfo: document.getElementById("sideInfo").value.replace(/\n/g, "<br>")
        };

        storedEvents.push(newEvent);
        localStorage.setItem("events", JSON.stringify(storedEvents));

        alert("Event berhasil ditambahkan!");
        window.location.href = "/view/admin/eventAdmin/eventAdmin.html";
    };

    reader.readAsDataURL(file);
});
