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
    <?php
    include "connect2.php";
    // Define a constant for your image base URL
    define('BASE_URL', 'http://localhost/event-mgt/');
    define('IMAGE_PATH', '/admin/ser-photos/');

    ?>
    <div class="parent">
        <div class="nav-bar">
            <a class="nav-contents">About</a>
            <a class="nav-contents">Gallery</a>
            <a class="nav-contents">Services</a>
            <a class="nav-contents">Contact</a>
            <a href="http://localhost/event-mgt/login.php" class="nav-contents">Login</a>
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
                        <button onclick="displayServiceSection('hall',this)" class="each-service">Hall</button>
                        <button onclick="displayServiceSection('catering',this)" class="each-service">Catering</button>
                    </div>
                    <div class="service-info">
                        <div id="hall" class="secondary-sec">
                            <!-- to display hall cards -->
                            <?php
                            $query = "SELECT DISTINCT h.*, 
                            (SELECT photo FROM photos WHERE hall_id = h.hall_id LIMIT 1) as photo 
                            FROM hall h ORDER BY h.hall_id";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $filename = basename($row['photo']); // Gets: 1785512269_pic9.jpg
                                    $photoPath = BASE_URL . 'admin/ser-photos/' . $filename;

                            ?>
                                    <div class="card" onclick="showService(this)">
                                        <img src="<?php echo $photoPath; ?>" alt="" width="420px" height="270px">
                                        <h3><?php echo $row['hall_name']; ?></h3>
                                        <p><?php echo "Seating Capacity : " . $row['seat_capacity']; ?></p>
                                        <p><?php echo "Type : " . $row['space_type']; ?></p>
                                    </div>
                            <?php
                                }
                            }
                            ?>

                            <!-- a demo hall display card
                            <div class="card" onclick="showService(this)">
                                <img src="hall1.jpg" alt="" width="410px">
                                <h3>Sagarmatha</h3>
                                <p>Seat Capacity: 450</p>
                                <p>Type: Indoor</p>
                            </div> -->

                            <div id="bg-overlay">
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
                        </div>

                        <div id="catering" class="secondary-sec">
                            <p class="menu-text">In laliguras, we have a variety of dished what we offer. You can customize your own menu or select a package that already exists.
                                You can view the images below can click the buttons when you are ready to place your order.
                            </p>

                            <div class="menu">
                                <button class="bookBtn">Book menu!</button>
                                <div class="upr-cols">
                                    <h2>Snacks</h2>
                                    <table>
                                        <tr>
                                            <th>S. No</th>
                                            <th>Items</th>
                                            <th>Rs.</th>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>Potato Chips</td>
                                            <td>30</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Chocolate Bar</td>
                                            <td>50</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Cookies</td>
                                            <td>40</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>French Fries</td>
                                            <td>80</td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Popcorn</td>
                                            <td>60</td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>Samosa</td>
                                            <td>35</td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>Sandwich</td>
                                            <td>70</td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>Nachos with Cheese</td>
                                            <td>90</td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>Muffin</td>
                                            <td>55</td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>Veg Puff</td>
                                            <td>45</td>
                                        </tr>
                                        <tr>
                                            <td>11</td>
                                            <td>Spring Roll</td>
                                            <td>65</td>
                                        </tr>
                                        <tr>
                                            <td>12</td>
                                            <td>Doughnut</td>
                                            <td>50</td>
                                        </tr>
                                        <tr>
                                            <td>13</td>
                                            <td>Bread Pakora</td>
                                            <td>40</td>
                                        </tr>
                                        <tr>
                                            <td>14</td>
                                            <td>Fruit Juice</td>
                                            <td>75</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>



                            <!-- tthis is the old format
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
                            </div> -->

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

        <script src="service.js"></script>
</body>

</html>