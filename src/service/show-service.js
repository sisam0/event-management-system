let selectedDate = null;
let selectedDayEl = null;
let unavailableDates = [];

const stripe = Stripe("pk_test_51TPznPEPFzNtpaUAvyHCj4vgYUnAr1BDGgzmhUUyQa0d7QpB67fX7dfuzMAWEiwyPT0YT3G6N2OTnmTzsQBp93uM00hiLVxX6W");
let elements;
let bookingData = {};
let packageId = null;

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

        dateClick: function (info) {
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
        bookBtn.addEventListener("click", function () {
            if (!selectedDate) {
                alert("Please select a date first.");
                return;
            }

            showBookingPopup();
        });
    }

    // payment setup
    document.getElementById("bookingForm").addEventListener("submit", onFormSubmit);
    document.querySelector(".stripePayment").style.display = "none";
    document.querySelector("#totalAmt button").addEventListener("click", startPayment);
});

// to show first pop up
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

// ---------- booking form submitted: save data only, no database insert ----------
function onFormSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const choice = form.querySelector('input[name="needed-ser"]:checked');

    if (!selectedDate) {
        alert("Please select a date first.");
        return;
    }
    if (!choice) {
        alert("Please choose whether you want catering.");
        return;
    }

    bookingData = {
        hall_id: form.service_id.value,
        booking_date: selectedDate,
        guest_count: form.guest_count.value,
        message: form.message.value
    };
    packageId = null;

    document.getElementById("bookingPopup").style.display = "none";

    if (choice.value === "yes") {
        document.querySelector(".bookCard").classList.add("catering-mode");
        document.querySelector(".book-popup-catering").style.display = "block";
    } else {
        showTotal(Number(document.getElementById("hall-price").value));
    }
}

// ---------- catering package chosen ----------
function showAmt(id, price) {
    packageId = id;
    document.querySelector(".book-popup-catering").style.display = "none";
    document.querySelector(".bookCard").classList.remove("catering-mode");
    showTotal(Number(document.getElementById("hall-price").value) + Number(price));
}

// ---------- total block (display only, server recalculates the real amount) ----------
function showTotal(total) {
    const box = document.getElementById("totalAmt");
    box.querySelector("h2").textContent = "Your total amount will be Rs. " + total;
    box.querySelector("h1").textContent = "Rs. " + total * 0.2;
    box.style.display = "block";
}

// ---------- Pay clicked: ask server for a Stripe payment, then show the card form ----------
async function startPayment() {
    this.style.display = "none";
    const stripeBox = document.querySelector(".stripePayment");
    stripeBox.style.display = "block";

    const res = await fetch("checkout.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ ...bookingData, package_id: packageId })
    });
    const data = await res.json();
    if (data.error) return alert(data.error);

    stripeBox.innerHTML = `
        <form id="payment-form">
            <div id="payment-element"></div>
            <button type="submit">Pay now</button>
            <div id="payment-message"></div>
        </form>`;

    elements = stripe.elements({ clientSecret: data.clientSecret });
    elements.create("payment").mount("#payment-element");

    document.getElementById("payment-form").addEventListener("submit", async (e) => {
        e.preventDefault();
        const { error } = await stripe.confirmPayment({
            elements,
            confirmParams: { return_url: new URL("checkout.php", window.location.href).href }
        });
        // only reached if payment failed (success redirects to payment.php)
        document.getElementById("payment-message").textContent = error.message;
    });
}