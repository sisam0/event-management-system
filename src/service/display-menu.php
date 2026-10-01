<?php
session_start();
include 'connect2.php';

// print_r($_SESSION);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" integrity="sha512-ApSLB1Pd3/bZN8fWB/RG9YhN/7bd9Hkf3AGaE2mPfebjrxagjuBtx2GcgdqIlJkUzwylBo61r9Xa9NmgBI0swA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="display-menu.css">

<body>
    <div class="parent">
        <div class="nav-bar">
            <a class="nav-contents" href="http://localhost:8081/homepg.php#about">About</a>
            <a class="nav-contents" href="http://localhost:8081/homepg.php#gallery">Gallery</a>
            <a class="nav-contents" href="http://localhost:8081/service/service.php">Services</a>
            <a class="nav-contents" href="http://localhost:8081/homepg.php#footer">Contact</a>
            <a class="nav-contents" href="http://localhost:8081/login.php" style="display:<?php echo isset($_SESSION['isLoggedin']) ? 'none' : 'inline-block'; ?>">Login</a>
            <a href="ser-logout.php" class="nav-contents" style="display:<?php echo isset($_SESSION['isLoggedin']) ? 'inline-block' : 'none'; ?>">Log out</a>
            <?php if (isset($_SESSION['isLoggedin']) && !empty($_SESSION['userPic'])): ?>
                <img
                    src="<?php echo htmlspecialchars($_SESSION['userPic']); ?>" alt="Profile"
                    height="25px" width="25px" class="nav-contents">
            <?php endif; ?>
        </div>

        <div class="contents">
            <div class="service-category">
                <button class="name-ser" onclick="window.open('http://localhost:8081/service/service.php')">Services</button>
                <button class="name-ser" onclick="window.open('http://localhost:8081/service/service.php?isPackage=true')">Packages</button>
            </div>

            <div class="details">
                <div id="service" class="main-section">
                    <div class="top">
                        <button onclick="window.open('http://localhost:8081/service/service.php?isService=true&isHall=true')" class="each-service">Hall</button>
                        <button onclick="window.open('http://localhost:8081/service/service.php?isService=true&isCatering=true')" class="each-service">Catering</button>
                    </div>

                    <div class="service-info">
                        <!-- the old format is the same as the one in admin page reference that if needed -->
                        <div class="food-cols">
                            <?php
                            $query = "select type_id, type_name from food_types";
                            $types = $conn->query($query);

                            while ($row = $types->fetch_assoc()) {
                                $counter = 1;
                                $food_type = $row['type_name'];
                                $type_id = $row['type_id'];

                                //to get each food name of each type rn named as $food_type
                                $stmt = $conn->prepare("SELECT * FROM menu where type_id = ?");
                                $stmt->bind_param("i", $type_id);
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
                                </div>
                            <?php
                                $stmt->close();
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>