<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" integrity="sha512-ApSLB1Pd3/bZN8fWB/RG9YhN/7bd9Hkf3AGaE2mPfebjrxagjuBtx2GcgdqIlJkUzwylBo61r9Xa9NmgBI0swA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="admin.css">
    <title>Admin Dashboard</title>
</head>

<body>

    <?php
    include "connect.php";
    session_start();
    $msg = "";

    $token = rand();
    $_SESSION['form_token'] = $token;
    ?>

    <div class="parent">
        <div class="nav-bar">
            <div class="nav-left">
                <img src="" alt="" height="50px" width="50px" class="admin-pic">
            </div>

            <div class="right">
                <div class="nav-anchor">
                    <a href="">User</a>
                </div>
                <div class="nav-anchor">
                    <a href="">Reports</a>
                </div>
                <div class="nav-anchor">
                    <a href="">Service</a>
                </div>
                <div class="nav-anchor">
                    <a href="">Logout</a>
                </div>
            </div>
        </div>

        <div class="contents">
            <div class="service-names">
                <span class="name">Hall</span>
                <span class="name">Catering</span>
            </div>

            <div id="render">
                <div class="hall">
                    <div id="addHall" class="card">
                        <div class="addingHall">
                            <i class="fa-solid fa-plus"></i> Add new hall photos

                        </div>
                        <button class="btn" onclick="addHall(event)">Add new hall</button>
                    </div>


                    <?php
                    $query = "SELECT DISTINCT h.*, 
                            (SELECT photo FROM photos WHERE hall_id = h.hall_id LIMIT 1) as photo 
                            FROM hall h ORDER BY h.hall_id";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $filename = basename($row['photo']); // Gets: 1785512269_pic9.jpg
                            $photoPath = 'ser-photos/' . $filename;
                    ?>
                            <div class="card">
                                <img src="<?php echo $photoPath; ?>" alt="" width="350px" height="230px">
                                <div class="hall-contents">
                                    <div style="text-align: left;">
                                        <h3><?php echo $row['hall_name']; ?></h3>
                                        <p><?php echo "Seating Capacity : " . $row['seat_capacity']; ?></p>
                                        <p><?php echo "Type : " . $row['space_type']; ?></p>
                                    </div>
                                    <div style="padding: 15px 0px 0px 50px;">
                                        <button class="btn">Edit Event</button>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>

                    <!-- <div class="card">
                    <img src="hall1.jpg" alt="" width="350px" height="230px">
                    <div class="hall-contents">
                        <div style="text-align: left;">
                            <h3>Sagarmatha</h3>
                            <p>Seat Capacity: 450</p>
                            <p>Type: Indoor</p>
                        </div>
                        <div style="padding: 15px 0px 0px 60px;">
                            <button class="btn">Edit Event</button>
                        </div>
                    </div>
                </div> -->

                </div>
            </div>

            <div id="overlay" class="hidden">
                <div id="cardInfo">
                    <div class="cardTop">
                        <h2>Add Hall</h2>
                        <span id="closeCard">&times;</span>
                    </div>

                    <div class="cardBottom">
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="token" value="<?php echo $token; ?>">
                            <table>
                                <tr>
                                    <td>Name:</td>
                                    <td><input type="text" name="name"></td>
                                </tr>
                                <tr>
                                    <td>Seat Capacity</td>
                                    <td><input type="number" name="seat"></td>
                                </tr>
                                <tr>
                                    <td>Seat Indoor</td>
                                    <td><input type="radio" name="seat_type" value="indoor">Indoor <br><input type="radio" name="seat_type" value="outdoor">Outdoor <br><input type="radio" name="seat_type" value="both">Both</td>
                                </tr>
                                <tr>
                                    <td>Description</td>
                                    <td><textarea name="description" cols="50px" rows="5px"></textarea></td>
                                </tr>
                                <tr>
                                    <td>Can bring outdoor food?</td>
                                    <td><input type="radio" name="food" value="yes">Yes <br><input type="radio" name="food" value="no"> No</td>
                                </tr>
                                <tr>
                                    <td>Number of private rooms</td>
                                    <td><input type="number" name="pvt_room"></td>
                                </tr>
                                <tr>
                                    <td>Is there DJ service?</td>
                                    <td><input type="radio" name="dj" value="yes">Yes <br><input type="radio" name="dj" value="no">No</td>
                                </tr>
                                <tr>
                                    <td>Add photos:</td>
                                    <td><input type="file" name="photos[]" multiple accept="image/*"></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><input type="submit" name="hallReg" value="Add hall!"></td>
                                </tr>
                            </table>
                        </form>
                    </div>

                    <?php

                    $_SESSION['formToken'] = rand();

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // if hall register is clicked
                        if (isset($_POST['hallReg'])) {

                            //Check if token matches
                            if ($_POST['token'] !== $_SESSION['form_token']) {
                                die("Invalid form submission");
                            }
                            // Clear token after use
                            unset($_SESSION['form_token']);
                            
                            $name = $_POST['name'];
                            $seat = $_POST['seat'];
                            $type = $_POST['seat_type'];
                            $desc = $_POST['description'];
                            $out_food = $_POST['food'];
                            $room = $_POST['pvt_room'];
                            $dj = $_POST['dj'];

                            $stmt = $conn->prepare("INSERT INTO hall (hall_name, seat_capacity, space_type, without_f, dj, pvt_room, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("sisssis", $name, $seat, $type, $out_food, $dj, $room, $desc);
                            if ($stmt->execute()) {
                                $hall_id = $conn->insert_id;
                                $folder = $folder = $_SERVER['DOCUMENT_ROOT'] . "/event-mgt/admin/ser-photos/"; //this is the absoulete path for reliability

                                foreach ($_FILES['photos']['tmp_name'] as $key => $value) {
                                    $filename = time() . "_" . $_FILES['photos']['name'][$key]; //creates a unique file name using original filenames
                                    $target_file = $folder . $filename;
                                    if (move_uploaded_file($_FILES['photos']['tmp_name'][$key], $target_file)) {
                                        //to sent photos in photo table
                                        $pic_stmt = $conn->prepare("insert into photos(photo, hall_id) values(?,?)");
                                        $pic_stmt->bind_param("si", $target_file, $hall_id);
                                        $pic_stmt->execute();
                                        $pic_stmt->close();
                                    }
                                }
                            }
                        }
                    }

                    ?>

                </div>

            </div>

        </div>
    </div>
    </div>
    <script src="admin.js"></script>

</body>

</html>