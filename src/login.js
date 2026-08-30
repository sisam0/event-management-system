password = document.getElementById("Mpassword");
confirm_pass = document.getElementById("Cpassword");
error = document.getElementById("error2");

//for adding event to display login or register form
document.getElementById("showRegister").addEventListener("click", function (e) {
    e.preventDefault();//preventDefault() is used as href has "#"
    document.getElementById("register").style.display = "block";
    document.getElementById("login").style.display = "none";
});

document.getElementById("showLogin").addEventListener("click", function (e) {
    e.preventDefault();
    document.getElementById("register").style.display = "none";
    document.getElementById("login").style.display = "block";
});

//display the error in login form if anything arises
confirm_pass.addEventListener("input", function(event) {
    if (confirm_pass.value !== password.value) {
        error.innerHTML = "Both password should be the same!";
        document.getElementById("error2").style.display = "block";
        error.style.color = "beige";
    } else {
        error.innerHTML = "Passwords are correct!";
    }
});

//to remove error2 (login error) if the input feild is not focused
document.getElementById("Cpassword").addEventListener("blur", () => {
    error.style.display = "none";
});
