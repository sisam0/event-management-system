const overlay = document.getElementById("overlay");
const closeCard = document.getElementById("closeCard");
console.log("admin.js loaded!");

window.addHall = function (event){
    event.preventDefault();
    if (overlay) {
        overlay.classList.remove("hidden");
        overlay.style.display = "flex";
        document.body.style.overflow = "hidden";
        console.log("Overlay displayed!");
    } else {
        console.error("Overlay element not found!");
    }
}

//to close add hall overlay
closeCard.addEventListener("click", function(event) {
    event.preventDefault();
    overlay.classList.add("hidden");
    document.body.style.overflow = "auto";
    overlay.style.display = "none";
});