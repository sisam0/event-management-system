<?php
include "connect.php";
session_start();

if (empty($_SESSION['admin_id'])) {
    header("Location: /admin/admin-login.php");
}
$msg = "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" integrity="sha512-ApSLB1Pd3/bZN8fWB/RG9YhN/7bd9Hkf3AGaE2mPfebjrxagjuBtx2GcgdqIlJkUzwylBo61r9Xa9NmgBI0swA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="admin.css">
    <title>Admin Dashboard</title>
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
                <button class="name" onclick="showService('hall',this)">Hall</button>
                <button class="name" onclick="showService('catering',this)">Catering</button>
                <a class="name" href="packages.php">Package</a>
                <!-- <span class="name" id="hall">Hall</span>
                <span class="name" id="catering">Catering</span> -->
            </div>

            <div id="render">
                <div id="hall" class="service">
                    <!-- to display hall cards -->
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

                    <!-- adding new hall card -->
                    <div class="card">
                        <div class="addingHall">
                            <i class="fa-solid fa-plus"></i> Add new hall photos
                        </div>
                        <button class="btn" onclick="addHall(event)">Add new hall</button>
                    </div>

                </div>

                <div id="catering" class="service">


                    <div class="menu">

                        <div class="food-cols">
                            <?php
                            $query = "select type from menu group by type";
                            $types = $conn->query($query);

                            while ($row = $types->fetch_assoc()) {
                                $counter = 1;
                                $food_type = $row['type'];

                                //to get each food name of each type rn named as $food_type
                                $stmt = $conn->prepare("SELECT * FROM menu WHERE type = ?");
                                $stmt->bind_param("s", $food_type);
                                $stmt->execute();
                                $getFood = $stmt->get_result();
                            ?>
                                <div class="food-list">
                                    <h2 class="food-name" style="text-align: center;"><?php echo $food_type; ?></h2>
                                    <table cellspacing="5px">
                                        <tr>
                                            <td>S. No</td>
                                            <td><?php echo $food_type; ?></td>
                                            <td>Rate</td>
                                        </tr>
                                        <?php
                                        while ($each_name = $getFood->fetch_assoc()) {
                                        ?>
                                            <tr>
                                                <td><?php echo $counter++; ?></td>
                                                <td><?php echo $each_name['name']; ?></td>
                                                <td><?php echo $each_name['rate']; ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </table>
                                    <button class="btn">Edit menu</button>
                                </div>
                            <?php
                                $stmt->close();
                            }
                            ?>

                            <!-- demo display -->
                            <!-- <div class="food-list">
                            <h2 class="food-name" style="text-align: center;">Veg Main Course</h2>
                            <table>
                                <tr>
                                    <td>S. No</td>
                                    <td>Veg Main Course</td>
                                    <td>Rs.</td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Paneer Butter Masala</td>
                                    <td>300</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Dal Makhani</td>
                                    <td>250</td>
                                </tr>
                            </table>
                        </div> -->


                            <!-- to show while editing menu -->
                            <!-- <div id="overlay">
                                <form action="" method="post">
                                    <table>
                                        <tr>
                                            <td>Type</td>
                                            <td><select name="type" id="">
                                                    <?php
                                                    $query = "select type from menu group by type";


                                                    ?>
                                                </select></td>
                                        </tr>
                                    </table>
                                </form> -->

                        </div>

                    </div>
                </div>
            </div>

        </div>






    </div>
    </div>
    </div>
    <script>
        console.log("forst script");
    </script>
    <script src="admin.js"></script>


</body>

</html>