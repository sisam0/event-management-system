<?php
session_start();
include 'connect2.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="show-hall.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" integrity="sha512-ApSLB1Pd3/bZN8fWB/RG9YhN/7bd9Hkf3AGaE2mPfebjrxagjuBtx2GcgdqIlJkUzwylBo61r9Xa9NmgBI0swA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Halls</title>
</head>

<body>

    <div id="cardInfo">
        <span class="closeCard" onclick="closeService()">&times;</span>
        <button class="bookBtn">Book hall!</button>

        <!-- to get info of a specific clicked hall  -->
        <?php
        $hallName = $_GET['hall'] ?? '';
        // print_r($_GET);
        // print_r($hallName);
        // print_r($_SESSION);
        $query = "select * from hall where hall_name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $hallName);
        $stmt->execute();
        $res = $stmt->get_result();

        while ($row = $res->fetch_assoc()) {
        ?>
            <div>
                <h2 style="text-align: center;"><?php echo $row['hall_name']; ?></h2>
                <table class="info-table">
                    <tr>
                        <td class="ser-tbl"><i class="fa-solid fa-person"></i>Seat Capacity</td>
                        <td class="ser-tbl"><?php echo $row['seat_capacity']; ?></td>
                    </tr>
                    <tr>
                        <td class="ser-tbl"><i class="fa-solid fa-building"></i>Type</td>
                        <td class="ser-tbl"><?php echo $row['space_type']; ?></td>
                    </tr>
                    <tr>
                        <td>price</td>
                        <td><?php echo $row['price']; ?></td>
                    </tr>
                </table>

                <br>
            </div>

            <div class="gallery">
                <?php
                $sql = "select photo from photos where hall_id = ?";
                $get = $conn->prepare($sql);
                $get->bind_param("i", $row['hall_id']);
                $get->execute();
                $hallPhoto = $get->get_result();

                while ($pic = $hallPhoto->fetch_assoc()) {
                ?>
                    <img class="imageCard" width="450px" src="<?php echo "/" . $pic['photo']; ?>">
                <?php
                }
                ?>

            </div>
        <?php

        }
        ?>

        <!-- this is the calender -->
        <div class="calendar-wrapper">
            <div class="calendar-header">
                <button class="cal-nav" onclick="alert('Previous month (demo)')"><i class="fas fa-chevron-left"></i></button>
                <span>August 2026</span>
                <button class="cal-nav" onclick="alert('Next month (demo)')"><i class="fas fa-chevron-right"></i></button>
            </div>

            <div class="calendar-grid">
                <!-- day names -->
                <div class="day-name">Mo</div>
                <div class="day-name">Tu</div>
                <div class="day-name">We</div>
                <div class="day-name">Th</div>
                <div class="day-name">Fr</div>
                <div class="day-name">Sa</div>
                <div class="day-name">Su</div>

                <!-- days (static August 2026 example) -->
                <!-- week 1: 1..2 are in July, but we show them as other-month -->
                <div class="day other-month">27</div>
                <div class="day other-month">28</div>
                <div class="day other-month">29</div>
                <div class="day other-month">30</div>
                <div class="day other-month">31</div>
                <div class="day">1</div>
                <div class="day weekend">2</div>

                <!-- week 2 -->
                <div class="day">3</div>
                <div class="day">4</div>
                <div class="day">5</div>
                <div class="day">6</div>
                <div class="day">7</div>
                <div class="day weekend">8</div>
                <div class="day weekend">9</div>

                <!-- week 3 -->
                <div class="day">10</div>
                <div class="day">11</div>
                <div class="day">12</div>
                <div class="day">13</div>
                <div class="day">14</div>
                <div class="day weekend">15</div>
                <div class="day weekend">16</div>

                <!-- week 4 -->
                <div class="day">17</div>
                <div class="day">18</div>
                <div class="day">19</div>
                <div class="day">20</div>
                <div class="day">21</div>
                <div class="day weekend">22</div>
                <div class="day weekend">23</div>

                <!-- week 5 -->
                <div class="day">24</div>
                <div class="day">25</div>
                <div class="day">26</div>
                <div class="day">27</div>
                <div class="day">28</div>
                <div class="day weekend">29</div>
                <div class="day weekend">30</div>

                <!-- week 6 (partial) -->
                <div class="day">31</div>
                <div class="day other-month">1</div>
                <div class="day other-month">2</div>
                <div class="day other-month">3</div>
                <div class="day other-month">4</div>
                <div class="day other-month weekend">5</div>
                <div class="day other-month weekend">6</div>
            </div>

            <!-- simple selection hint (click on a day to select) -->
            <div style="margin-top: 14px; display: flex; justify-content: flex-end; font-size: 0.85rem; color: #5b4c3d;">
                <span><i class="far fa-circle" style="color: #b76e4b; margin-right: 6px;"></i>click day to select</span>
            </div>
        </div>

    </div>

    <script src="show-service.js"></script>
</body>

</html>