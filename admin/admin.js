const overlay = document.getElementById("overlay");
const closeCard = document.getElementById("closeCard");
console.log("admin.js loaded!");

const hall = document.getElementById("hall");
const catering = document.getElementById("catering");

window.addHall = function (event){
    event.preventDefault();
    window.location.href = "http://localhost/event-mgt/admin/add-hall.php";
}

catering.addEventListener("click", showCatering);
hall.addEventListener("click", showHall);

function showCatering() {
    

}

function showHall() {

}