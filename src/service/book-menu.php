<?php
session_start();
include 'connect2.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: http://localhost:8081/login.php");
    exit();
}

$package_id = isset($_GET['package_id']) ? (int) $_GET['package_id'] : 0;

// if ($package_id <= 0) {
//     header("Location: http://localhost:8081/service/service.php");
//     exit;
// }

// Fetch the package with its hall + service info
$query = "SELECT p.package_id, p.name, p.description, p.price, p.type,
                 s.ser_name, h.hall_name, h.seat_capacity, h.space_type
          FROM packages p
          LEFT JOIN service s ON s.service_id = p.service_id
          LEFT JOIN hall h ON h.hall_id = p.hall_id
          WHERE p.package_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $package_id);
$stmt->execute();
$package = $stmt->get_result()->fetch_assoc();
$stmt->close();

// No such package — bail out instead of showing a blank/broken page
if (!$package) {
    http_response_code(404);
    echo "Package not found.";
    exit;
}

// Fetch ALL details for this package (no LIMIT, unlike the card preview)
$detail_query = "SELECT dt.detail_text FROM package_details pd
                  JOIN detail dt ON dt.detail_id = pd.detail_id
                  WHERE pd.package_id = ?";
$detail_stmt = $conn->prepare($detail_query);
$detail_stmt->bind_param("i", $package_id);
$detail_stmt->execute();
$details = $detail_stmt->get_result();
$detail_stmt->close();

// Fetch food types included in this package, if any
$food_query = "SELECT ft.type_name FROM package_food_types pft
                JOIN food_types ft ON ft.type_id = pft.type_id
                WHERE pft.package_id = ?";
$food_stmt = $conn->prepare($food_query);
$food_stmt->bind_param("i", $package_id);
$food_stmt->execute();
$food_types = $food_stmt->get_result();
$food_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($package['name']) ?> — Package Details</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" integrity="sha512-ApSLB1Pd3/bZN8fWB/RG9YhN/7bd9Hkf3AGaE2mPfebjrxagjuBtx2GcgdqIlJkUzwylBo61r9Xa9NmgBI0swA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300..700;1,300..700&family=Dancing+Script:wght@400..700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="book-menu.css">
    <link rel="stylesheet" href="view-package.css">
</head>

<body>
    <div class="parent">
        <!-- <div class="nav-bar">
            <a class="nav-contents">About</a>
            <a class="nav-contents">Gallery</a>
            <a class="nav-contents">Services</a>
            <a class="nav-contents">Contact</a>
            <a class="nav-contents" href="http://localhost:8081/login.php" style="display:<?php echo isset($_SESSION['isLoggedin']) ? 'none' : 'inline-block'; ?>">Login</a>
            <a href="ser-logout.php" class="nav-contents" style="display:<?php echo isset($_SESSION['isLoggedin']) ? 'inline-block' : 'none'; ?>">Log out</a>
        </div> -->

        <div class="detail-wrap">
            <a href="service.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to packages</a>

            <div class="detail-card">
                <div class="detail-top">
                    <span class="package-type type-<?= strtolower(htmlspecialchars($package['type'])) ?>">
                        <?= htmlspecialchars($package['type']) ?>
                    </span>
                    <span class="detail-price">Rs. <?= number_format($package['price']) ?></span>
                </div>

                <h1><?= htmlspecialchars($package['name']) ?></h1>
                <p class="detail-description"><?= htmlspecialchars($package['description']) ?></p>

                <div class="package-meta">
                    <?php if ($package['hall_name']): ?>
                        <span><i class="fa-solid fa-building-columns"></i>
                            <?= htmlspecialchars($package['hall_name']) ?>
                            <?php if ($package['seat_capacity']): ?>
                                &middot; <?= (int) $package['seat_capacity'] ?> seats
                            <?php endif; ?>
                        </span>
                    <?php endif; ?>
                    <?php if ($package['ser_name']): ?>
                        <span><i class="fa-solid fa-utensils"></i> <?= htmlspecialchars($package['ser_name']) ?></span>
                    <?php endif; ?>
                </div>

                <?php if ($details->num_rows > 0): ?>
                    <div class="detail-section">
                        <h2>What's included</h2>
                        <ul class="package-details">
                            <?php while ($d = $details->fetch_assoc()): ?>
                                <li><?= htmlspecialchars($d['detail_text']) ?></li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if ($food_types->num_rows > 0): ?>
                    <div class="detail-section">
                        <h2>Food types</h2>
                        <div class="food-tags">
                            <?php while ($f = $food_types->fetch_assoc()): ?>
                                <span class="food-tag"><?= htmlspecialchars($f['type_name']) ?></span>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <form action="book-package.php" method="GET" class="book-form">
                    <input type="hidden" name="package_id" value="<?= (int) $package['package_id'] ?>">
                    <button type="submit" class="btn book-btn">Book This Package</button>
                </form>
            </div>
        </div>
    </div>

    <script src="book-menu.js"></script>
</body>

</html>