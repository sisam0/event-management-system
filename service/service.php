<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services Offered</title>
    <link rel="stylesheet" href="service.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" integrity="sha512-ApSLB1Pd3/bZN8fWB/RG9YhN/7bd9Hkf3AGaE2mPfebjrxagjuBtx2GcgdqIlJkUzwylBo61r9Xa9NmgBI0swA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="parent">
        <div class="nav-bar">
            <a class="nav-contents">About</a>
            <a class="nav-contents">Gallery</a>
            <a class="nav-contents">Services</a>
            <a class="nav-contents">Contact</a>
        </div>
        <!-- here is display flex -->
        <div class="contents">
            <div class="service-category">
                <button class="name-ser" onclick="showMainSection('service')">Services</button>
                <button class="name-ser" onclick="showMainSection('package')">Packages</button>
            </div>
            <div class="details">
                <div id="service" class="main-section">
                    <div class="top">
                        <button onclick="showSection('hall')" class="each-service">Hall</button>
                        <button onclick="showSection('catering')" class="each-service">Catering</button>
                    </div>
                    <div class="service-info">
                        <div id="hall" class="secondary-sec">
                            <div class="card" onclick="showService(this)">
                                <img src="hall1.jpg" alt="" width="410px">
                                <h3>Sagarmatha</h3>
                                <p>Seat Capacity: 450</p>
                                <p>Type: Indoor</p>
                            </div>
                            <div class="card" onclick="showService(this)">
                                <img src="hall1.jpg" alt="" width="410px">
                                <h3>Sagarmatha</h3>
                                <p>Seat Capacity: 450</p>
                                <p>Type: Indoor</p>
                            </div>
                            <div class="card" onclick="showService(this)">
                                <img src="hall1.jpg" alt="" width="410px">
                                <h3>Sagarmatha</h3>
                                <p>Seat Capacity: 450</p>
                                <p>Type: Indoor</p>
                            </div>
                            <div class="card" onclick="showService(this)">
                                <img src="hall1.jpg" alt="" width="410px">
                                <h3>Sagarmatha</h3>
                                <p>Seat Capacity: 450</p>
                                <p>Type: Indoor</p>
                            </div>
                        </div>

                        <div id="overlay">
                            <div id="cardInfo">
                                <span class="closeCard" onclick="closeService()">&times;</span>
                                <button class="bookBtn">Book hall!</button>
                                <div>
                                    <h2 style="text-align: center;">Sagarmatha Hall</h2>
                                    <table>
                                        <tr>
                                            <td><i class="fa-solid fa-person"></i>Seat Capacity</td>
                                            <td>450</td>
                                        </tr>
                                        <tr>
                                            <td><i class="fa-solid fa-building"></i>Type</td>
                                            <td>Indoor</td>
                                        </tr>
                                    </table>
                                    <br>
                                </div>

                                <div>
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Doloremque tempora labore distinctio dicta laborum. Repudiandae, dignissimos magni fugit reiciendis tenetur rem perferendis praesentium? Minima natus cupiditate cum debitis similique tempore?</p>
                                </div>

                                <div>
                                    <img id="imageCard" height="300px" width="400">
                                    <img id="imageCard" height="300px" width="400">
                                </div>

                                <div id="calendar"></div>

                            </div>
                        </div>

                        <div id="catering" class="secondary-sec">
                            <p class="menu-text">In laliguras, we have a variety of dished what we offer. You can customize your own menu or select a package that already exists.
                                You can view the images below can click the buttons when you are ready to place your order.
                            </p>
                            <div class="menu">
                                <button class="bookBtn">Book menu!</button>
                                <h2>Snacks</h2>
                                <div class="menu-gallery">
                                    <img src="snack.jpg" alt="" height="300px" width="250px" class="photo-menu" onclick="FullView(this.src)">
                                    <img src="snack.jpg" alt="" height="300px" width="250px" class="photo-menu" onclick="FullView(this.src)">
                                    <img src="snack.jpg" alt="" height="300px" width="250px" class="photo-menu" onclick="FullView(this.src)">
                                </div>

                                <h2>Dessert</h2>
                                <div class="menu-gallery">
                                    <img src="dessert.jpg" alt="" height="300px" width="250px" class="photo-menu" onclick="FullView(this.src)">
                                    <img src="dessert.jpg" alt="" height="300px" width="250px" class="photo-menu" onclick="FullView(this.src)">
                                    <img src="dessert.jpg" alt="" height="300px" width="250px" class="photo-menu" onclick="FullView(this.src)">
                                </div>
                            </div>

                            <div id="full-image-view">
                                <img id="full-image"></img>
                                <span id="closeBtn" onclick="closeFullView()">&times;</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="package" class="main-section">
                    <div class="service-info">
                        <div class="card">
                            <img src="venue.jpg" alt="" width="400px">
                            <h3>Golden Package</h3>
                            <p>Includes Sagarmatha hall and menu for 600 guests!</p>
                        </div>
                        <div class="card">
                            <img src="venue.jpg" alt="" width="400px">
                            <h3>Golden Package</h3>
                            <p>Includes Sagarmatha hall and menu for 600 guests!</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="service.js"></script>
</body>

</html>