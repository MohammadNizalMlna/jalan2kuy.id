const bulan = {
  januari: 1, februari: 2, maret: 3, april: 4,
  mei: 5, juni: 6, juli: 7, agustus: 8,
  september: 9, oktober: 10, november: 11, desember: 12
};

function parseTanggal(str, fallbackYear = null) {
  const p = str.trim().split(" ");

  const day = parseInt(p[0]);
  const month = bulan[p[1].toLowerCase()] || 1;

  // bila ada tahun
  if (p.length === 3) {
    return new Date(parseInt(p[2]), month - 1, day);
  }

  // bila tanpa tahun → gunakan fallback
  return new Date(fallbackYear, month - 1, day);
}

function parseEventDate(range) {

  let clean = range.replace("–", "-"); // ganti dash aneh
  let [startStr, endStr] = clean.split("-").map(s => s.trim());

  let endDate = parseTanggal(endStr);
  let year = endDate.getFullYear();

  let startDate = parseTanggal(startStr, year);

  return { start: startDate, end: endDate };
}

function getSortedEvents() {
  return events.slice().sort((a, b) => {
    const A = parseEventDate(a.date).start;
    const B = parseEventDate(b.date).start;
    return A - B;
  });
}

function renderEvents(list) {
  const container = document.getElementById("eventList");

  if (!list || list.length === 0) {
    container.innerHTML = `
      <p style="color:white;text-align:center;font-size:20px;margin-top:40px;">
        Tidak ada event dalam rentang tanggal ini
      </p>`;
    return;
  }

  container.innerHTML = list.map(ev => `
      <a class="event-card" href="/view/admin/eventAdmin/allEventAdmin.html?id=${ev.id}">
        
        <div class="event-image-container">
          <img src="${ev.image}" class="event-img">
          <div class="event-date-tag">${ev.date}</div>
        </div>

        <div class="event-body">
          <h3>${ev.title}</h3>
          <p class="event-location">
            <i class="fa-solid fa-location-dot"></i> ${ev.location}
          </p>
          <p class="event-desc">${ev.description}</p>
        </div>

      </a>
  `).join("");
}
function applyDateFilter() {

  const s = document.getElementById("startDate").value;
  const e = document.getElementById("endDate").value;


  if (!s && !e) {
    renderEvents(getSortedEvents());
    return;
  }

  const start = s ? new Date(s) : new Date("1900-01-01");
  const end   = e ? new Date(e) : new Date("2100-12-31");

  const filtered = getSortedEvents().filter(ev => {
    const eventRange = parseEventDate(ev.date);
    return eventRange.end >= start && eventRange.start <= end;
  });

  renderEvents(filtered);
}
