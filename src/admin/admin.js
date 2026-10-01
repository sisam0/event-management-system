
//to display certain part from packages
document.addEventListener("DOMContentLoaded", () => {
    const params = new URLSearchParams(window.location.search);
    const tab = params.get("tab");

    if (tab) {
        const btn = document.querySelector(`.tab-btn[data-tab="${tab}"]`);
        if (btn) {
            showService(tab, btn);
        } else {
            // fallback if there's no matching button element
            showService("hall");
        }
    }
});


const overlay = document.getElementById("overlay");
const closeCard = document.getElementById("closeCard");
console.log("admin.js loaded!");

const hall = document.getElementById("hall");
const catering = document.getElementById("catering");


function addHall(event){
    event.preventDefault();
    window.location.href = "/admin/add-hall.php";
}

function showService(id, btnElement) {
    showContent(id);
    console.log(id);
    changeBtnColor(btnElement);    
}

//to show content
function showContent(id){
    document.querySelectorAll(".service").forEach(function(service) {
        service.classList.remove("active");
    });
    document.getElementById(id).classList.add("active");
}

//to change botton color
function changeBtnColor(btnElement) {
    document.querySelectorAll(".name").forEach(btn => {
        btn.classList.remove("active-btn");
    });
    btnElement.classList.add("active-btn");

}