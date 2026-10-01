<?php
session_start();
if (isset($_SESSION['isLoggedin'])) {
    $userPic = $_SESSION['userPic'];
}
// print_r($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="homepg.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" integrity="sha512-ApSLB1Pd3/bZN8fWB/RG9YhN/7bd9Hkf3AGaE2mPfebjrxagjuBtx2GcgdqIlJkUzwylBo61r9Xa9NmgBI0swA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="parent">
        <div class="main-bg">
            <div class="nav-bar">
                <a class="nav-contents" href="#about">About</a>
                <a class="nav-contents" href="#gallery">Gallery</a>
                <a class="nav-contents" href="service\service.php">Services</a>
                <a class="nav-contents" href="#footer">Contact</a>
                <a class="nav-contents" id="login" href="http://localhost:8081/login.php" style="display:<?php echo isset($_SESSION['isLoggedin']) ? 'none' : 'inline-block'; ?>">Login</a>
                <a href="logout.php" class="nav-contents" style="display:<?php echo isset($_SESSION['isLoggedin']) ? 'inline-block' : 'none'; ?>">Log out</a>
                <img style="display:<?php echo isset($_SESSION['isLoggedin']) ? 'inline-block' : 'none'; ?>"
                    src="<?php echo htmlspecialchars($userPic); ?>"
                    alt="" height="50px" width="50px" class="nav-contents">
            </div>
            <div class="name-contents">
                <img src="logo.png" alt="" height="85" style="opacity: 1;">
                <span style="font-family: var(--main-font);" id="text">laliguras</span>
                <button class="main-btn">Contact us!</button>
            </div>
        </div>
        <span id="about"></span>

        <div class="about">
            <h1>About us</h1>
            <p class="abt-text"> Welcome to <span style="font-family: var(--main-font);">laliguras</span>, where authentic flavors,
                warm hospitality, and unforgettable experiences come together.
                Inspired by Nepal's rich culinary heritage, we prepare every dish
                using fresh ingredients, traditional recipes, and a passion for
                quality. <br><br>
                Whether you're joining us for a family dinner, a celebration with
                friends, or a quiet meal after a long day, our goal is to make
                every visit feel special. From classic Nepali favorites to carefully
                crafted contemporary dishes, we strive to serve food that feels like
                home. <br><br>
                At Laliguras, we believe that great food brings people together.
                Thank you for letting us be part of your memorable moments—we look
                forward to welcoming you. </p>
        </div>

        <div id="gallery">
            <h1 style="margin-left: 20px;">Gallery</h1>
            <div class="gallery">
                <div class="pics"></div>
                <div class="pics"></div>
                <div class="pics"></div>
                <div class="pics"></div>
                <div class="pics"></div>
                <div class="pics"></div>
                <div class="pics"></div>
                <div class="pics"></div>
            </div>
        </div>

        <div class="footer" id="footer">
            <div class="footer-content">
                <div class="footer-col">
                    <span class="footer-logo" style="font-family: var(--main-font);">laliguras</span>
                    <p>Authentic Nepali flavors, warm hospitality, and unforgettable
                        moments — serving Kathmandu since 2015.</p>
                </div>

                <div class="footer-col">
                    <h3>Contact</h3>
                    <p><i class="fa-solid fa-location-dot"></i> Jhamsikhel, Lalitpur, Kathmandu</p>
                    <p><i class="fa-solid fa-phone"></i> +977 01-5551234</p>
                    <p><i class="fa-solid fa-envelope"></i> hello@laliguras.com</p>
                </div>

                <div class="footer-col">
                    <h3>Hours</h3>
                    <p>Sun – Fri: 11:00 AM – 9:30 PM</p>
                    <p>Saturday: 12:00 PM – 10:00 PM</p>
                </div>

                <div class="footer-col">
                    <h3>Follow us</h3>
                    <div class="footer-socials">
                        <a href="#"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                &copy; <?= date("Y") ?> Laliguras Restaurant. All rights reserved.
            </div>
        </div>

    </div>

    <script>

    </script>

</body>

</html>