// for navigating main sections
showMainSection("service");
showSection("hall");

//for dark background behind the popup screen of services
const overlay = document.getElementById("overlay");
// Hide on page load
overlay.classList.add("hidden");

let calendar;

function showMainSection(id) {
    document.querySelectorAll(".main-section").forEach(section => {
        section.classList.remove("active");
    });
document.getElementById(id).classList.add("active");
}

function showSection(id) {
    document.querySelectorAll(".secondary-sec").forEach(section => {
        section.classList.remove("active");
    });
document.getElementById(id).classList.add("active");
}
        
function FullView(imgLink) {
    document.getElementById("full-image").src = imgLink;
    document.getElementById("full-image-view").style.display = "block";
    document.body.style.overflow = "hidden";
}

function closeFullView() {
    document.getElementById("full-image-view").style.display = "none";
    document.body.style.overflow = "auto";
}

function showService(card) {
    overlay.classList.remove("hidden");
    const img = card.querySelector("img");
    document.getElementById("imageCard").src = img.src;
    document.getElementById("cardInfo").style.display = "flex";
    document.getElementById("cardInfo").style.flexDirection = "column";
    document.body.style.overflow = "hidden";

    const calendarEl = document.querySelector('#calendar');
    new Calendar(calendarEl);
}

function closeService() {    
    overlay.classList.add("hidden");
    document.body.style.overflow = "auto";
}
