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
    <title>Services Offered</title>
    <link rel="stylesheet" href="service.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" integrity="sha512-ApSLB1Pd3/bZN8fWB/RG9YhN/7bd9Hkf3AGaE2mPfebjrxagjuBtx2GcgdqIlJkUzwylBo61r9Xa9NmgBI0swA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">

    <style>
        .food-list table tr td:nth :nth-child(even) {
            width: 200px;
        }
    </style>
</head>

<body>
    <?php
    include "connect2.php";
    // Define a constant for your image base URL
    define('BASE_URL', 'http://localhost/event-mgt/src/');
    define('IMAGE_PATH', '/admin/ser-photos/');

    if(isset($_GET['login="success'])){
        ?>
        <script>
            Swal.fire({
                title: 'Success!',
                text: 'You have logged in successfully.',
                icon: 'success',
                confirmButtonText: 'Close'
            });
        </script>

        <?php
    }

    ?>
    <div class="parent">
        <div class="nav-bar">
            <a class="nav-contents">About</a>
            <a class="nav-contents">Gallery</a>
            <a class="nav-contents">Services</a>
            <a class="nav-contents">Contact</a>
            <a class="nav-contents" href="http://localhost:8081/login.php" style="display:<?php echo isset($_SESSION['isLoggedin']) ? 'none' : 'inline-block'; ?>">Login</a>
            <a href="ser-logout.php" class="nav-contents" style="display:<?php echo isset($_SESSION['isLoggedin']) ? 'inline-block' : 'none'; ?>">Log out</a>
            <img style="display:<?php echo isset($_SESSION['isLoggedin']) ? 'inline-block' : 'none'; ?>"
                src="<?php echo htmlspecialchars($userPic); ?>"
                alt="" height="25px" width="25px" class="nav-contents">
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

                            $result = $conn->query($query);
                            if (!$result) {
                                die("Query failed: " . $conn->error);
                            }

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $photoPath = $row['photo']; // This already has the full path: admin/ser-photos/filename.jpg

                                    // If path doesn't start with /, add it
                                    if (strpos($photoPath, '/') !== 0) {
                                        $photoPath = '/' . $photoPath;
                                    }

                            ?>
                                    <form action="" method="get" onsubmit="return false;">
                                        <div class="card" onclick="showService(event, '<?php echo htmlspecialchars($row['hall_name']); ?>')">
                                            <img src="<?php echo htmlspecialchars($photoPath); ?>" alt="" width="420px" height="270px">
                                            <h3><?php echo htmlspecialchars($row['hall_name']); ?></h3>
                                            <p>Seating Capacity : <?php echo $row['seat_capacity']; ?></p>
                                            <p>Type : <?php echo $row['space_type']; ?></p>
                                        </div>
                                    </form>
                                    <!-- <div class="card" onclick="showService(event)">
                                        <?php $_SESSION['thisService'] = $row['hall_name']; ?>
                                        <img src="<?php echo $photoPath; ?>" alt="" width="420px" height="270px">
                                        <h3><?php echo $row['hall_name']; ?></h3>
                                        <p><?php echo "Seating Capacity : " . $row['seat_capacity']; ?></p>
                                        <p><?php echo "Type : " . $row['space_type']; ?></p>
                                    </div> -->
                            <?php
                                }
                            }
                            ?>
                        </div>

                        <div id="catering" class="secondary-sec">
                            <p class="menu-text">In laliguras, we have a variety of dished what we offer. You can customize your own menu or select a package that already exists.
                                You can view the images below can click the buttons when you are ready to place your order.
                            </p>

                            <div class="menu">
                                <button class="bookBtn">Book menu!</button>
                                <!-- the old format is the same as the one in admin page reference that if needed -->
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
                                        </div>
                                    <?php
                                        $stmt->close();
                                    }
                                    ?>
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