<?php
include 'connect.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" integrity="sha512-ApSLB1Pd3/bZN8fWB/RG9YhN/7bd9Hkf3AGaE2mPfebjrxagjuBtx2GcgdqIlJkUzwylBo61r9Xa9NmgBI0swA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="packages.css">
</head>

<body>
    <div class="parent">
        <div class="nav-bar">
            <div class="nav-left">
                <img src="logo.png" alt="" height="50px" width="50px" class="admin-pic">
            </div>

            <div class="right">
                <div class="nav-anchor">
                    <a href="user-details.php">User</a>
                </div>
                <div class="nav-anchor">
                    <a href="">Reports</a>
                </div>
                <div class="nav-anchor ser-active">
                    <a href="">Service</a>
                </div>
                <div class="nav-anchor">
                    <a href="admin-logout.php">Logout</a>
                </div>
            </div>
        </div>

        <div class="contents">
            <div class="service-names">
                <button class="name" onclick="goTo('hall')">Hall</button>
                <button class="name" onclick="goTo('catering')">Catering</button>
                <button class="name" style="background-color: rgb(255, 255, 159);">Package</button>
                <!-- <span class="name" id="hall">Hall</span>
                <span class="name" id="catering">Catering</span> -->
            </div>

            <div class="card">
                <div class="addingHall">
                    <i class="fa-solid fa-plus"></i> Add new package
                </div>
                <button class="btn" onclick="addHall()">Add new package</button>
            </div>

        </div>
</body>
<script>
    document.querySelector(".addingHall").addEventListener("click", addHall);

    function goTo(destination) {
        if (destination == "hall") {

        }
    }

    function addHall() {
        window.location.href = "http://localhost:8081/admin/add-packages.php";
    }
</script>

</html>