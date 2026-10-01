<?php
include 'connect.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" integrity="sha512-ApSLB1Pd3/bZN8fWB/RG9YhN/7bd9Hkf3AGaE2mPfebjrxagjuBtx2GcgdqIlJkUzwylBo61r9Xa9NmgBI0swA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.1/css/all.min.css" integrity="sha512-QeR2VH+lsBE5LSAe1Q5EnTBbe7XTBubt8dG93Y7gidSgdMCr8nVqKcfKAMyN96SV8KDbZVTDXChatu5G2KQGzg==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="packages.css">
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
                <button class="name" onclick="window.location.href='http://localhost:8081/admin/admin.php?tab=hall">Hall</button>
                <button class="name" onclick="window.location.href='http://localhost:8081/admin/admin.php?tab=catering'">Catering</button>
                <button class="name" style="background-color: rgb(255, 255, 159);">Package</button>
                <!-- <span class="name" id="hall">Hall</span>
                <span class="name" id="catering">Catering</span> -->
            </div>

            <div class="card-container">

                <div class="card" style="align-items: center;">
                    <span>Add new package!</span>
                    <button onclick="goTo('new', 0)" class="btn">Add new package</button>
                </div>

                <?php
                $query = " SELECT p.package_id, p.name, p.description, p.price, p.type, s.ser_name, h.hall_name
                        FROM packages p
                        LEFT JOIN service s ON s.service_id = p.service_id
                        LEFT JOIN hall h ON h.hall_id = p.hall_id";
                $result = $conn->query($query);
                ?>

                <?php while ($package = $result->fetch_assoc()): ?>
                    <?php
                    $detail_query = "SELECT dt.detail_text FROM package_details pd
                        JOIN detail dt ON dt.detail_id = pd.detail_id
                        WHERE pd.package_id = ? LIMIT 2 ";

                    $detail_stmt = $conn->prepare($detail_query);
                    $detail_stmt->bind_param("i", $package['package_id']);
                    $detail_stmt->execute();
                    $details = $detail_stmt->get_result();
                    ?>

                    <div class="card">
                        <div class="card-top">
                            <span class="package-type type-<?= strtolower(htmlspecialchars($package['type'])) ?>">
                                <?= htmlspecialchars($package['type']) ?>
                            </span>
                            <span class="package-price">Rs. <?= number_format($package['price']) ?></span>
                        </div>

                        <h3><?= htmlspecialchars($package['name']) ?></h3>
                        <p class="package-description"><?= htmlspecialchars($package['description']) ?></p>

                        <div class="package-meta">
                            <?php if ($package['hall_name']): ?>
                                <span><i class="fa-solid fa-building-columns"></i><?= htmlspecialchars($package['hall_name']) ?></span>
                            <?php endif; ?>
                            <?php if ($package['ser_name']): ?>
                                <span><i class="fa-solid fa-utensils"></i> <?= htmlspecialchars($package['ser_name']) ?></span>
                            <?php endif; ?>
                        </div>

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
                        $food_type = "select * from food_types ft 
                        join package_food_types pf on ft.type_id = pf.type_id 
                        where pf.package_id = ?";
                        $ft_stmt = $conn->prepare($food_type);
                        $ft_stmt->bind_param("i", $package['package_id']);
                        $ft_stmt->execute();
                        $type_details = $ft_stmt->get_result();

                        ?>
                        <ul class="package-details">
                            <?php while ($row = $type_details->fetch_assoc()): ?>
                                <li><?= htmlspecialchars($row['type_name']) ?></li>
                            <?php endwhile; ?>
                        </ul>

                        <div class="edit">
                            <button class="btn" onclick="goTo('edit', <?= $package['package_id'] ?> )">Edit</button>
                            <button class="btn" onclick="goTo('view', <?= $package['package_id'] ?> )">View</button>
                        </div>
                    </div>
                <?php endwhile; ?>

            </div>

        </div>
</body>
<script>
    document.querySelector(".addingHall").addEventListener("click", addHall);


    function goTo(destination, id) {
        if (destination === "edit") {
            window.location.href = "http://localhost:8081/admin/edit-package.php?id=" + id;
        } else if (destination === "view") {
            window.location.href = "http://localhost:8081/admin/view-package.php?id=" + id;
        } else if (destination === "new") {
            window.location.href = "http://localhost:8081/admin/add-packages.php";
        }
    }

    function addHall() {
        window.location.href = "http://localhost:8081/admin/add-packages.php";
    }
</script>

</html>