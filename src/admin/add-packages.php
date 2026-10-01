<?php
include 'connect.php';
session_start();
$packageAdded = false;
$errorMessage = '';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Package</title>
    <link rel="stylesheet" href="sweetalert2.min.css">


    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.3.0/css/all.min.css" integrity="sha512-ApSLB1Pd3/bZN8fWB/RG9YhN/7bd9Hkf3AGaE2mPfebjrxagjuBtx2GcgdqIlJkUzwylBo61r9Xa9NmgBI0swA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="add-packages.css">

    <style>
        .topmost {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <form action="" method="post" id="packageForm">
        <div class="topmost">
            <div></div>
            <div>
                <h1 style="font-family: var(--secoundary-font);">Make a package!</h1>
            </div>
            <div><span id="closeBtn" onclick="closeFullView()" style="font-size: 40px; cursor: pointer; position:sticky; top:15px;">&times;</span></div>
        </div>
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
                        echo '<label><input type="radio" name="hall_id" value="' . htmlspecialchars($row['hall_id']) . '"> ' . htmlspecialchars($row['hall_name']) . '</label>';
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
                        echo '<label><input type="checkbox" name="menu_type[]" value="' . htmlspecialchars($row['type_id']) . '"> ' . htmlspecialchars($row['type_name']) . '</label>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Description:</td>
                <td><textarea name="description" cols="30px" rows="5px" id=""></textarea></td>
            </tr>
            <tr>
                <td>Choose features of the package:</td>
                <td>
                    <?php
                    $details = "select * from detail";
                    $res = mysqli_query($conn, $details);

                    while ($row = mysqli_fetch_assoc($res)) {
                        echo '<label><input type="checkbox" value="' . htmlspecialchars($row['detail_id']) . '" name="details[]"> ' . htmlspecialchars($row['detail_text']) . '<br></label>';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>Price:</td>
                <td><input type="number" name="price" step="0.01"></td>
            </tr>
            <tr style="align-items: center;">
                <td colspan="2"><input type="submit" name="submit"></td>
            </tr>

        </table>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

        $name = trim($_POST['p_name'] ?? '');
        $type = $_POST['type'] ?? '';
        $description = trim($_POST['description'] ?? '');
        $price = $_POST['price'] ?? '';
        $service_id = 3;
        $package_detail = $_POST['details'] ?? [];
        $menu_type = $_POST['menu_type'] ?? [];

        $errors = [];
        if ($name === '') $errors[] = 'Package name cannot be empty.';
        if (!in_array($type, ['catering', 'hall', 'both'], true)) $errors[] = 'Invalid package type.';
        if ($description === '') $errors[] = 'Description cannot be empty.';
        if (!is_numeric($price) || $price <= 0) $errors[] = 'Price must be greater than 0.';
        if (empty($package_detail)) $errors[] = 'Select at least one feature.';

        $hall_id = null;
        if ($type === 'hall' || $type === 'both') {
            if (empty($_POST['hall_id'])) $errors[] = 'Select a hall.';
            else $hall_id = (int) $_POST['hall_id'];
        }
        if (($type === 'catering' || $type === 'both') && empty($menu_type)) {
            $errors[] = 'Select at least one catering type.';
        }
        if ($type === 'hall') $menu_type = [];

        if ($errors) {
            $errorMessage = implode("\n", $errors);
        } else {
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

                foreach ($package_detail as $detail) {
                    $detail_id = (int) $detail;
                    $detail_query = "insert into package_details(detail_id, package_id) values(?,?)";
                    $detail_stmt = $conn->prepare($detail_query);
                    $detail_stmt->bind_param("ii", $detail_id, $package_id);
                    $detail_stmt->execute();
                }

                $conn->commit();
                $packageAdded = true;
            } catch (mysqli_sql_exception $e) {
                $conn->rollback();
                $errorMessage = $e->getMessage();
            }
        }
    }
    ?>


    <script src="add-package.js"></script>

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

</body>

</html>