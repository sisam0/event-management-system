document.querySelectorAll(".packageType").forEach((radio) => {
    radio.addEventListener("change", () => {     
        const packageType = document.querySelector('input[name="type"]:checked').value;

        if(packageType === "catering"){
            document.getElementById("displayMenu").style.display = "table-row";
            document.getElementById("displayHall").style.display = "none";

        }
        else if(packageType === "hall"){
            document.getElementById("displayHall").style.display = "table-row";
            document.getElementById("displayMenu").style.display = "none";
        }
        else{
            document.getElementById("displayHall").style.display = "table-row";
            document.getElementById("displayMenu").style.display = "table-row";    
        }
    });
});