// for navigating main sections


document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);

    const isHall = params.get("isHall");
    const isCatering = params.get("isCatering");
    const isService = params.get("isService");
    const isPackage = params.get("isPackage");

    if( isService && isHall){
        //show service section and hall sub-section
        showMainSection("service");
        showSection("hall",null);
    }
    else if(isService && isCatering){
        //show service section and catering sub-section
        showMainSection("service");
        showSection("catering",null);        
    }
    else if(isPackage){
        //show only packae section
        showMainSection("package");
    }
    else{
        //normal loading the just show hall
        showMainSection("service");
        showSection("hall",null);
    }


})

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

function closeService() {    
    overlay.classList.add("hidden");
    document.body.style.overflow = "auto";
}

function goTo(destination, id){
    if(destination === "book-package"){
        window.location.href = "http://localhost:8081/service/book-menu.php?package_id=" + id;
    }
    else if(destination === "view"){
        window.location.href = "http://localhost:8081/service/display-menu.php";

    }
}