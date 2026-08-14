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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" integrity="sha512-ApSLB1Pd3/bZN8fWB/RG9YhN/7bd9Hkf3AGaE2mPfebjrxagjuBtx2GcgdqIlJkUzwylBo61r9Xa9NmgBI0swA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <title>Halls</title>
    </head>

    <body>
        <div class="parent">

            <div id="cardInfo">
                <div class="top-info">
                    <button class="bookBtn" id="bookBtn">Book hall!</button>
                    <h2 style="text-align: center;"><?php echo $hallName; ?></h2>
                    <span class="closeCard" onclick="closeService()">&times;</span>
                </div>

                <div>
                    <table class="info-table" data-service-id="<?php echo $hall_id; ?>">
                        <tr>
                            <td class="ser-tbl"><i class="fa-solid fa-person"></i>Seat Capacity</td>
                            <td class="ser-tbl"><?php echo $row['seat_capacity']; ?></td>
                        </tr>
                        <tr>
                            <td class="ser-tbl"><i class="fa-solid fa-building"></i>Type</td>
                            <td class="ser-tbl"><?php echo $row['space_type']; ?></td>
                        </tr>
                        <tr>
                            <td><i class="fa-solid fa-money-bill"></i>Price</td>
                            <td><?php echo $row['price']; ?></td>
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

                        while ($pic = $hallPhoto->fetch_assoc()) {
                        ?>
                            <img class="imageCard" width="340px" src="<?php echo "/" . $pic['photo']; ?>" onclick="fullView(this.src)">
                        <?php
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
            <!-- $row ends here  -->
        <?php } ?>


        <div class="book-overlay<?php echo $booked ? ' active' : ''; ?>" id="book-overlay">
            <div class="bookCard">

                <div class="book-popup" id="bookingPopup" style="<?php echo $booked ? 'display:none;' : 'display:block;'; ?>">
                    <span class="close-book" onclick="closeBooking()">&times;</span>
                    <h2>Book This Hall</h2>

                    <form id="bookingForm" method="POST">
                        <input type="hidden" name="service_id" id="service_id" value="<?php echo $hall_id; ?>">
                        <input type="hidden" name="hall_name" value="<?php echo htmlspecialchars($hallName); ?>">

                        <div class="form-group">
                            <label>Selected Date:</label>

                            <input type="text" name="booking_date_display" id="booking_date_display" readonly>

                            <input type="hidden" name="booking_date" id="booking_date">
                        </div>

                        <div class="form-group">
                            <label>Special Requests (Optional):</label>
                            <textarea name="message" id="message" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Total Price:</label>
                            <div>Rs. <input type="number" name="price" value="<?php echo $price; ?>" readonly></div>
                        </div>

                        <div class="form-group">
                            <label>Approximate Guest Count:</label>
                            <div>Rs. <input type="number" name="guest_count"></div>
                        </div>

                        <button type="submit" class="submit-btn" name="confirmBtn">
                            Confirm Booking
                        </button>

                    </form>
                </div>


                <!-- SUCCESS -->
                <div class="success-popup" id="successPopup" style="<?php echo $booked ? 'display:block;' : 'display:none;'; ?>">

                    <span class="close-book" onclick="closeBooking()">&times;</span>

                    <h2>You successfully booked the venue!</h2>
                    <p>Your booking has been submitted successfully.</p>
                </div>

            </div>
        </div>

        </div>
        <script>
            console.log("this is the show in firskdjcs  service page ");
        </script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.js"></script>
        <script src="show-service.js"></script>
    </body>

    </html>