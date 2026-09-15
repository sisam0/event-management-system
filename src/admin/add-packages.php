<?php
include 'connect.php';
session_start();
$packageAdded = false;
$errorMessage = '';

// Enable exceptions for mysqli errors so try/catch below actually catches failures.
// Best placed in connect.php right after creating $conn, but included here as a fallback.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
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
                    $query = "select hall_id, hall_name from hall";
                    $hall = mysqli_query($conn, $query);

                    while ($row = mysqli_fetch_assoc($hall)) {
                        echo '<input type="radio" name="hall_id" value="' . htmlspecialchars($row['hall_id']) . '"> ' . htmlspecialchars($row['hall_name']);
                        echo "<br>";
                    }
                    ?>
                </td>
            </tr>
            <tr id="displayMenu" style="display: none;">
                <td>Catering</td>
                <td>
                    <?php
                    $query = "select type_id, type_name from food_types";
                    $menu = mysqli_query($conn, $query);

                    while ($row = mysqli_fetch_assoc($menu)) {
                        echo '<input type="checkbox" name="menu_type[]" value="' . htmlspecialchars($row['type_id']) . '"> ' . htmlspecialchars($row['type_name']);
                        echo "<br>";
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
                <td><input type="number" name="price" step="0.01"></td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" name="submit"></td>
            </tr>

        </table>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

        $name = $_POST['p_name'];
        $type = $_POST['type'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $service_id = 3;

        $hall_id = ($type == "hall" || $type == "both") ? (int) $_POST['hall_id'] : null;
        $menu_type = ($type == "catering" || $type == "both") ? $_POST['menu_type'] : []; //array of selected food type ids

        $conn->begin_transaction();

        try {
            $query = "insert into packages (service_id, hall_id, name, description, price, type) values(?,?,?,?,?,?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iissds", $service_id, $hall_id, $name, $description, $price, $type);
            $stmt->execute();

            $package_id = $conn->insert_id;

            foreach ($menu_type as $type_id) {
                $type_id = (int) $type_id;
                $food_query = "insert into package_food_types(package_id, type_id) values(?, ?)";
                $food_stmt = $conn->prepare($food_query);
                $food_stmt->bind_param("ii", $package_id, $type_id);
                $food_stmt->execute();
            }

            $conn->commit();
            $packageAdded = true;

        } catch (mysqli_sql_exception $e) {
            $conn->rollback();
            $errorMessage = $e->getMessage();
        }
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if ($packageAdded): ?>
        <script>
            Swal.fire({
                title: "Package is added!",
                icon: "success"
            });
        </script>
    <?php elseif ($errorMessage): ?>
        <script>
            Swal.fire({
                title: "Failed to add package",
                text: <?php echo json_encode($errorMessage); ?>,
                icon: "error"
            });
        </script>
    <?php endif; ?>

    <script src="add-package.js"></script>
</body>

</html>