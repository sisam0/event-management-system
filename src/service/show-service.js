let selectedDate = null;
let selectedDayEl = null;
let unavailableDates = [];

document.addEventListener("DOMContentLoaded", () => {
    const bookBtn = document.getElementById("bookBtn");
    const calendarEl = document.getElementById("calendar");

    if (!calendarEl) return;

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: "dayGridMonth",
        selectable: true,

        validRange: {
            start: new Date().toISOString().split("T")[0]
        },

        dateClick: function(info) {
            if (unavailableDates.includes(info.dateStr)) {
                alert("This date is unavailable.");
                return;
            }

            selectedDate = info.dateStr;

            if (selectedDayEl)
                selectedDayEl.classList.remove("selected-date");

            selectedDayEl = info.dayEl;
            selectedDayEl.classList.add("selected-date");
        }
    });

    calendar.render();

    // Get unavailable dates
    fetch("get_unavailable_dates.php?service_id=" + getServiceId())
        .then(response => response.json())
        .then(dates => {
            unavailableDates = dates;

            dates.forEach(date => {
                calendar.addEvent({
                    start: date,
                    allDay: true,
                    display: "background",
                    classNames: ["unavailable-date"]
                });
            });
        })
        .catch(error => console.error(error));

    if (bookBtn) {
        bookBtn.addEventListener("click", function() {
            if (!selectedDate) {
                alert("Please select a date first.");
                return;
            }

            showBookingPopup();
        });
    }
});

function showBookingPopup() {
    const overlay = document.getElementById("book-overlay");
    const serviceId = document.getElementById("service_id");
    const bookingDate = document.getElementById("booking_date");
    const bookingDisplay = document.getElementById("booking_date_display");

    if (!overlay || !bookingDate || !bookingDisplay) return;

    if (serviceId) serviceId.value = getServiceId();
    bookingDate.value = selectedDate;
    bookingDisplay.value = formatDate(selectedDate);

    overlay.classList.add("active");
    document.body.style.overflow = "hidden";
}

function getServiceId() {
    const input = document.querySelector('input[name="service_id"]');
    if (input?.value) return input.value;

    const info = document.querySelector(".info-table");
    return info?.dataset.serviceId || 1;
}

function formatDate(date) {
    return new Date(date + "T00:00:00").toLocaleDateString("en-US", {
        weekday: "long", year: "numeric", month: "long", day: "numeric"
    });
}

function closeBooking() {
    const overlay = document.getElementById("book-overlay");
    if (overlay) overlay.classList.remove("active");
    document.body.style.overflow = "auto";
}

function closeService() {
    window.location.href = "/service/service.php";
}

function fullView(imgLink) {
    const view = document.getElementById("full-image-view"), img = document.getElementById("full-image");
    if (view && img) {
        img.src = imgLink;
        view.style.display = "block";
        document.body.style.overflow = "hidden";
    }
}

function closeFullView() {
    const view = document.getElementById("full-image-view");
    if (view) view.style.display = "none";
    document.body.style.overflow = "auto";
}