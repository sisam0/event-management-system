// for navigating main sections
showMainSection("service");
showSection("hall",null);
//for dark background behind the popup screen of services
const overlay = document.getElementById("bg-overlay");
// Hide on page load
overlay.classList.add("hidden");

//show package or service on click
function showMainSection(id) {
    document.querySelectorAll(".main-section").forEach(section => {
        section.classList.remove("active");
    });
    document.getElementById(id).classList.add("active");
}

function displayServiceSection(id, btnElement) {
    showSection(id);
    colorServiceBtn(btnElement);
}

//show halls or catering on click
function showSection(id) {
    document.querySelectorAll(".secondary-sec").forEach(section => {
        section.classList.remove("active");
    });
    document.getElementById(id).classList.add("active");    
}

//to color different service btn clicked
function colorServiceBtn(btnElement){
    // for button color change
    document.querySelectorAll(".each-service").forEach(btn => {
        btn.classList.remove("active-btn");
    });
    btnElement.classList.add("active-btn");
}
   
//view full photo os each clicked photo in catering
function FullView(imgLink) {
    document.getElementById("full-image").src = imgLink;
    document.getElementById("full-image-view").style.display = "block";
    document.body.style.overflow = "hidden";
}

//close full menu image
function closeFullView() {
    document.getElementById("full-image-view").style.display = "none";
    document.body.style.overflow = "auto";
}

//show each card of hall
function showService(event, hallName) {
    event.preventDefault();
    // alert("clicked hall is ", hallName);
    window.location.href = "/service/show-service.php?hall=" + encodeURIComponent(hallName);
}
    
    // console.log(overlay)
    // overlay.classList.remove("hidden");
    // console.log(overlay)
    // const img = card.querySelector("img");
    // document.getElementById("imageCard").src = img.src;
    // document.getElementById("cardInfo").style.display = "flex";
    // document.getElementById("cardInfo").style.flexDirection = "column";
    // document.body.style.overflow = "hidden";


function closeService() {    
    overlay.classList.add("hidden");
    document.body.style.overflow = "auto";
}
