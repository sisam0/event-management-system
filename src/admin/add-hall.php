<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Hall</title>
    <link rel="stylesheet" href="add-hall.css">
</head>

<body>
    <?php
    include "connect.php";
    ?>

    <div id="overlay">
        <div id="cardInfo">
            <div class="cardTop">
                <h2>Add New Hall</h2>
                <span id="closeCard">&times;</span>
            </div>

            <div class="cardBottom">
                <form action="" method="post" enctype="multipart/form-data">
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
                            <td>Price estimate</td>
                            <td><input type="number" name="price"></td>
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
        </div>
    </div>

    <?php


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // if hall register is clicked
        if (isset($_POST['hallReg'])) {
            // if(empty)

            $name = $_POST['name'];
            $seat = $_POST['seat'];
            $type = $_POST['seat_type'];
            $desc = $_POST['description'];
            $out_food = $_POST['food'];
            $room = $_POST['pvt_room'];
            $dj = $_POST['dj'];
            $price = $_POST['price'];

            $stmt = $conn->prepare("INSERT INTO hall (hall_name, seat_capacity, space_type, without_f, dj, pvt_room, description, price) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sisssis", $name, $seat, $type, $out_food, $dj, $room, $desc, $price);
            if ($stmt->execute()) {
                $hall_id = $conn->insert_id;
                // docker ma, /var/www/html/ is the document root not normal one
                $folder = "/var/www/html/admin/ser-photos/";

                // $folder = $folder = $_SERVER['DOCUMENT_ROOT'] . "/event-mgt/admin/ser-photos/"; //this is the absoulete path for reliability

                foreach ($_FILES['photos']['tmp_name'] as $key => $value) {
                    $filename = time() . "_" . $_FILES['photos']['name'][$key]; //creates a unique file name using original filenames
                    $target_file = $folder . $filename;
                    if (move_uploaded_file($_FILES['photos']['tmp_name'][$key], $target_file)) {
                        //to sent photos in photo table
                        $pic_stmt = $conn->prepare("insert into photos(photo, hall_id) values(?,?)");
                        $relative_path = "admin/ser-photos/" . $filename;
                        $pic_stmt->bind_param("si", $relative_path, $hall_id);
                        if ($pic_stmt->execute()) {
                            $pic_stmt->close();
                        }
                    }
                }
    ?>
                <script>
                    window.location.href = "/admin/admin.php";
                </script>
    <?php
                exit();
            }
        }
    }

    ?>

    <script>
        closeCard = document.getElementById("closeCard").addEventListener("click", closePage);

        function closePage() {
            window.location.href = "/admin/admin.php";
        }
    </script>
</body>

</html>