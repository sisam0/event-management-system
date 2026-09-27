<?php
session_start();

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
        .food-list table tr td:nth-child(even) {
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
    ?>

    <div class="parent">
        <div class="nav-bar">
            <a class="nav-contents">About</a>
            <a class="nav-contents">Gallery</a>
            <a class="nav-contents">Services</a>
            <a class="nav-contents">Contact</a>
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
                <button class="name-ser" data-section="service" onclick="showMainSection('service', this)">Services</button>
                <button class="name-ser" data-section="package" onclick="showMainSection('package', this)">Packages</button>
            </div>

            <div class="details">
                <div id="service" class="main-section">
                    <div class="top">
                        <button onclick="displayServiceSection('hall', this)" class="each-service">Hall</button>
                        <button onclick="displayServiceSection('catering', this)" class="each-service">Catering</button>
                    </div>

                    <div class="service-info">
                        <div id="hall" class="secondary-sec">
                            <?php
                            $query = "SELECT DISTINCT h.*, 
                                (SELECT photo FROM photos WHERE hall_id = h.hall_id LIMIT 1) as photo 
                                FROM hall h ORDER BY h.hall_id";
                            $result = $conn->query($query);
                            if (!$result) {
                                die("Query failed: " . $conn->error);
                            }

                            while ($row = $result->fetch_assoc()):
                                $photoPath = $row['photo'];
                                if (strpos($photoPath, '/') !== 0) {
                                    $photoPath = '/' . $photoPath;
                                }
                            ?>
                                <form action="" method="get" onsubmit="return false;">
                                    <div class="card hall-card" onclick="showService(event, '<?php echo htmlspecialchars($row['hall_name']); ?>')">
                                        <img src="<?php echo htmlspecialchars($photoPath); ?>" alt="" width="420px" height="270px">
                                        <h3><?php echo htmlspecialchars($row['hall_name']); ?></h3>
                                        <p>Seating Capacity : <?php echo (int) $row['seat_capacity']; ?></p>
                                        <p>Type : <?php echo htmlspecialchars($row['space_type']); ?></p>
                                    </div>
                                </form>
                            <?php endwhile; ?>
                        </div>

                        <div id="catering" class="secondary-sec">
                            <p class="menu-text">In laliguras, we have a variety of dishes we offer. You can customize your own menu or select a package that already exists.
                                You can view the images below and click the buttons when you are ready to place your order.
                            </p>

                            <div class="menu">
                                <div class="buttons">
                                    <button class="bookBtn" onclick="goTo('view')">View Menu</button>
                                </div>

                                <div id="package-section">
                                    <?php
                                    $query = "SELECT p.package_id, p.name, p.description, p.price, p.type
                                        FROM packages p WHERE p.type = 'catering'";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $stmt->close();
                                    ?>

                                    <?php while ($package = $result->fetch_assoc()): ?>
                                        <?php
                                        $detail_query = "SELECT dt.detail_text FROM package_details pd
                                            JOIN detail dt ON dt.detail_id = pd.detail_id
                                            WHERE pd.package_id = ? LIMIT 2";
                                        $detail_stmt = $conn->prepare($detail_query);
                                        $detail_stmt->bind_param("i", $package['package_id']);
                                        $detail_stmt->execute();
                                        $details = $detail_stmt->get_result();
                                        ?>

                                        <div class="package-card">
                                            <div class="card-top">
                                                <span class="package-type type-<?= strtolower(htmlspecialchars($package['type'])) ?>">
                                                    <?= htmlspecialchars($package['type']) ?>
                                                </span>
                                                <span class="package-price">Rs. <?= number_format($package['price']) ?></span>
                                            </div>

                                            <h3><?= htmlspecialchars($package['name']) ?></h3>
                                            <p class="package-description"><?= htmlspecialchars($package['description']) ?></p>

                                            <?php if ($details->num_rows > 0): ?>
                                                <ul class="package-details">
                                                    <?php while ($d = $details->fetch_assoc()): ?>
                                                        <li><?= htmlspecialchars($d['detail_text']) ?></li>
                                                    <?php endwhile; ?>
                                                </ul>
                                            <?php endif;
                                            $detail_stmt->close();
                                            ?>

                                            <?php
                                            $food_type = "SELECT ft.type_name FROM food_types ft
                                                JOIN package_food_types pf ON ft.type_id = pf.type_id
                                                WHERE pf.package_id = ?";
                                            $ft_stmt = $conn->prepare($food_type);
                                            $ft_stmt->bind_param("i", $package['package_id']);
                                            $ft_stmt->execute();
                                            $type_details = $ft_stmt->get_result();
                                            ?>
                                            <ul class="package-details">
                                                <?php while ($row = $type_details->fetch_assoc()): ?>
                                                    <li><?= htmlspecialchars($row['type_name']) ?></li>
                                                <?php endwhile;
                                                $ft_stmt->close();
                                                ?>
                                            </ul>

                                            <div class="edit">
                                                <button class="btn" onclick="goTo('book-package', <?= $package['package_id'] ?>)">Book Package</button>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="package" class="main-section">
                    <div class="service-info">
                        <div class="card hall-card">
                            <img src="venue.jpg" alt="" width="400px">
                            <h3>Golden Package</h3>
                            <p>Includes Sagarmatha hall and menu for 600 guests!</p>
                        </div>
                        <div class="card hall-card">
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