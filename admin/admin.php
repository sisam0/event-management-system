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
                <span class="name" id="hall">Hall</span>
                <span class="name" id="catering">Catering</span>
            </div>

            <div id="render">
                <div class="hall">
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
                    <div id="addHall" class="card">
                        <div class="addingHall">
                            <i class="fa-solid fa-plus"></i> Add new hall photos
                        </div>
                        <button class="btn" onclick="addHall(event)">Add new hall</button>
                    </div>

                </div>

                <div class="catering">

                </div>
            </div>

            
                

            

        </div>
    </div>
    </div>
    <script src="admin.js"></script>

</body>

</html>