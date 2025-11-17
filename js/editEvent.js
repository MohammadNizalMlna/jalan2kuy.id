const params = new URLSearchParams(window.location.search);
const eventId = params.get("id");

let storedEvents = JSON.parse(localStorage.getItem("events")) || [];
let eventData = storedEvents.find(e => e.id === eventId);

const imageBox = document.getElementById("imageBox");
const eventImage = document.getElementById("eventImage");


if (eventData) {
  document.getElementById("title").value = eventData.title;
  document.getElementById("location").value = eventData.location;
  document.getElementById("date").value = eventData.date;
  document.getElementById("desc").value = eventData.description;

  document.getElementById("fullDesc").value =
    eventData.fullDescription.replace(/<br>/g, "\n");

  document.getElementById("sideInfo").value =
    eventData.sideInfo.replace(/<br>/g, "\n");

  if (eventData.image) {
    imageBox.style.backgroundImage = `url('${eventData.image}')`;
    imageBox.style.backgroundSize = "cover";
    imageBox.style.backgroundPosition = "center";

    imageBox.querySelector("i").style.display = "none";
    imageBox.querySelector("p").style.display = "none";
  }
}


eventImage.addEventListener("change", function () {
  const file = this.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = e => {
    imageBox.style.backgroundImage = `url('${e.target.result}')`;
    imageBox.style.backgroundSize = "cover";
    imageBox.style.backgroundPosition = "center";

    imageBox.querySelector("i").style.display = "none";
    imageBox.querySelector("p").style.display = "none";

    eventData.image = e.target.result;
  };
  reader.readAsDataURL(file);
});


document.getElementById("saveBtn").onclick = () => {
  eventData.title = document.getElementById("title").value;
  eventData.location = document.getElementById("location").value;
  eventData.date = document.getElementById("date").value;
  eventData.description = document.getElementById("desc").value;

  eventData.fullDescription =
    document.getElementById("fullDesc").value.replace(/\n/g, "<br>");

  eventData.sideInfo =
    document.getElementById("sideInfo").value.replace(/\n/g, "<br>");

  localStorage.setItem("events", JSON.stringify(storedEvents));

  alert("Event berhasil diperbarui!");
  window.location.href = "/view/admin/eventAdmin/eventAdmin.html";
};