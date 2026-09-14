<?php
include 'connect.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Package</title>
    <link rel="stylesheet" href="sweetalert2.min.css">
</head>

<body>
    <form action="" method="post">
        <h1>Make a package!</h1>
        <table>
            <tr>
                <td><label for="">Name the package:</label></td>
                <td><input type="text" name="p_name"></td>
            </tr>
            <tr>
                <td><label for="">Package related to:</label></td>
                <td><input type="radio" name="type" class="packageType" value="catering">Catering
                    <input type="radio" name="type" class="packageType" value="hall">Halls
                    <input type="radio" name="type" class="packageType" value="both">Both
                </td>
            </tr>
            <tr id="displayHall" style="display: none;">
                <td>Halls</td>
                <td>
                    <?php
                    $query = "select hall_name from hall";
                    $hall = mysqli_query($conn, $query);

                    while ($row = mysqli_fetch_assoc($hall)) {
                        $hall_name = $row['hall_name'];
                        echo '<input type="radio" name="hall_name" value="' . htmlspecialchars($row['hall_name']) . '"> ' . htmlspecialchars($row['hall_name']);
                    }
                    ?>
                </td>
            </tr>
            <tr id="displayMenu" style="display: none;">
                <td>Catering</td>
                <td>
                    <?php
                    $query = "select type from menu group by type";
                    $menu = mysqli_query($conn, $query);

                    while ($row = mysqli_fetch_assoc($menu)) {
                        $menu_type = $row['type'];
                        echo '<input type="checkbox" name="menu_type" value="' . htmlspecialchars($row['type']) . '"> ' . htmlspecialchars($row['type']);
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Description:</td>
                <td><textarea name="description" cols="30px" rows="5px" id=""></textarea></td>
            </tr>
            <tr>
                <td>Price:</td>
                <td><input type="number" name="price"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit"></td>
            </tr>


        </table>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['submit'])) {
            $name = $_POST['p_name'];
            $type = $_POST['type'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $service_id = 3;

            $query = "insert into packages (service_id, name, description, price, type) values(?,?,?,?,?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("issis", $service_id, $name, $description, $price, $type);
            $stmt->execute();

            if ($stmt) {
                $packageAdded = true;
            }
        }
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if ($packageAdded == true) {
    ?>
        <script>
            Swal.fire({
                title: "Package is added!",
                icon: "success"
            });
        </script>
    <?php
    }
    ?>

    <script src="add-package.js"></script>
</body>


</html>