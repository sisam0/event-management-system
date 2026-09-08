<?php
session_start();
include 'connect2.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: http://localhost:8081/login.php");
    exit();
}

$hallName = $_GET['hall'] ?? '';
$price = 0;
$booked = "";

$query = "select * from hall where hall_name = ?";
$_SESSION['show_hall'] = $hallName;
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $hallName);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

if ($row) {
    $hall_id = $row['hall_id'];
    $price = $row['price'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // to confirm booking 
        if (isset($_POST['confirmBtn'])) {
            $user_id = $_SESSION['user_id'];
            $service_id = $_POST['service_id'];
            $booking_date = $_POST['booking_date'];
            $message = $_POST['message'];
            $guest_count = $_POST['guest_count'];
            $needed_ser = $_POST['needed-ser'];

            // echo $service_id;
            // Calculate total
            $total = $price;

            // Insert into booking table
            $booking_query = "INSERT INTO booking (user_id, date, guest_count, status, message, total) 
                          VALUES (?, ?, ?, 'pending', ?, ?)";
            $stmt = $conn->prepare($booking_query);
            $stmt->bind_param("isisi", $user_id, $booking_date, $guest_count, $message, $total);
            $stmt->execute();
            $booking_id = $conn->insert_id;

             //to check if we want to direct to the menu list or not 
            if($needed_ser == 'yes'){
                $_SESSION['directed_from_halls'] = true;
                

            }

            // Insert into booking_service table
            $booking_service_query = "INSERT INTO booking_service (booking_id, service_id, price, date) 
                                  VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($booking_service_query);
            $stmt->bind_param("iiis", $booking_id, $service_id, $price, $booking_date);
            $stmt->execute();

            // Insert into unavailable table to mark date as unavailable
            $unavailable_query = "INSERT INTO unavailable (service_id, date) VALUES (?, ?)";
            $stmt = $conn->prepare($unavailable_query);
            $stmt->bind_param("is", $service_id, $booking_date);
            $stmt->execute();

            $_SESSION["booked"] = true;
            $booked = true;

            if($stmt && $_SESSION['directed_from_halls'] == true){
                //then go to the different page to book menus

            }
        }
    }
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="show-hall.css">
        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/main.min.css" rel="stylesheet">

        <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" integrity="sha512-ApSLB1Pd3/bZN8fWB/RG9YhN/7bd9Hkf3AGaE2mPfebjrxagjuBtx2GcgdqIlJkUzwylBo61r9Xa9NmgBI0swA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <title>Halls</title>
    </head>

    <body>
        <div class="parent">

            <div id="cardInfo">
                
                <div class="top-info">
                    <button class="bookBtn" id="bookBtn">Book hall!</button>
                    <h1 style="text-align: center;" class="main-close"><?php echo $hallName; ?></h1>
                    <span class="closeCard close" onclick="closeService()">&times;</span>
                </div>

                <div class="show-middle">
                    <div>
                        <table class="info-table" data-service-id="<?php echo $hall_id; ?>">
                            <tr>
                                <td class="ser-tbl"><i class="fa-solid fa-person"></i> Seat Capacity</td>
                                <td class="ser-tbl"><?php echo $row['seat_capacity']; ?></td>
                            </tr>
                            <tr>
                                <td class="ser-tbl"><i class="fa-solid fa-building"></i> Type</td>
                                <td class="ser-tbl"><?php echo $row['space_type']; ?></td>
                            </tr>
                            <tr>
                                <td><i class="fa-solid fa-money-bill"></i> Price</td>
                                <td><?php echo $row['price']; ?></td>
                            </tr>
                            <tr>
                                <td><i class="fa-solid fa-bowl-food"></i> Can you bring outside food?</td>
                                <td><?php echo $row['without_f']; ?></td>
                            </tr>
                            <tr>
                                <td><i class="fa-solid fa-music"></i> Is there a DJ service?</td>
                                <td><?php echo $row['dj']; ?></td>
                            </tr>
                            <tr>
                                <td><i class="fa-solid fa-door-open"></i> Number of private rooms</td>
                                <td><?php echo $row['pvt_room']; ?></td>
                            </tr>

                        </table>

                        <p class="describe"><?php echo $row['description']; ?></p>
                        <br>
                    </div>

                    <div class="gallery">
                        <div>
                            <?php
                            $sql = "select photo from photos where hall_id = ?";
                            $get = $conn->prepare($sql);
                            $get->bind_param("i", $row['hall_id']);
                            $get->execute();
                            $hallPhoto = $get->get_result();
                            $i = 1;

                            while ($pic = $hallPhoto->fetch_assoc()) {
                            ?>
                                <img class="imageCard" height="225px" width="340px" src="<?php echo "/" . $pic['photo']; ?>" onclick="fullView(this.src)">
                            <?php
                                if ($i % 2 == 0) {
                                    echo "<br>";
                                }
                                $i++;
                            }

                            ?>
                        </div>

                        <div id="full-image-view">
                            <img id="full-image"></img>
                            <span id="closeBtn" onclick="closeFullView()">&times;</span>
                        </div>
                    </div>

                    <div id="calendar"></div>
                </div>
            </div>
            <!-- $row ends here  -->
        <?php } ?>


        <div class="book-overlay<?php echo $booked ? ' active' : ''; ?>" id="book-overlay">
            <div class="bookCard">

                <div class="book-popup" id="bookingPopup" style="<?php echo $booked ? 'display:none;' : 'display:block;'; ?>">
                    <span class="close-book close" onclick="closeBooking()">&times;</span>
                    <h2>Book This Hall</h2>

                    <form id="bookingForm" method="POST">
                        <input class="book-input" type="hidden" name="service_id" id="service_id" value="<?php echo $hall_id; ?>">
                        <input type="hidden" class="book-input" name="hall_name" value="<?php echo htmlspecialchars($hallName); ?>">

                        <div class="form-group">
                            <label>Selected Date:</label>
                            <input type="text" class="book-input" name="booking_date_display" id="booking_date_display" readonly>
                            <input type="hidden" name="booking_date" id="booking_date">
                        </div>

                        <div class="form-group">
                            <label>Do you want to book catering services for that day?:</label>
                            <div><input type="checkbox" name="needed-ser" value="yes">Yes 
                                <input type="checkbox" name="needed-ser" value="no">No</div>
                        </div>

                        <div class="form-group">
                            <label>Special Requests (Optional):</label>
                            <textarea name="message" id="message" rows="3" class="book-input" ></textarea>
                        </div>

                        <div class="form-group">
                            <label>Total Price:</label>
                            <div>Rs. <input type="number" class="book-input" name="price" value="<?php echo $price; ?>" readonly></div>
                        </div>

                        <div class="form-group">
                            <label>Approximate Guest Count:</label>
                            <input type="number" class="book-input" name="guest_count">
                        </div>

                        <button type="submit" class="submit-btn" name="confirmBtn">Book!</button>

                    </form>
                </div>


                <!-- success ko pop up  -->
                <div class="success-popup" id="successPopup" style="<?php echo $booked ? 'display:block;' : 'display:none;'; ?>">

                    <span class="close-book close" onclick="closeBooking()">&times;</span>

                    <h2>You successfully booked the venue!</h2>
                    <p>Your booking has been submitted successfully.</p>
                </div>

            </div>
        </div>

        </div>
        <script>
            console.log("this is the show in firskdjcs  service page ");
        </script>

        <!-- for calnder -->
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.js"></script>


        <script src="show-service.js"></script>
    </body>

    </html>