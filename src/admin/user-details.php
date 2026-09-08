<?php
include 'connect.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Details</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/3.0.2/css/dataTables.dataTables.css" />
</head>
<body>
<?php
$msg = "";
if (isset($_POST['save'])) {
    $status = $_POST['status-select'];
    $booking_id = $_POST['booking_id'];
    $query = "UPDATE booking SET status=? WHERE booking_id=?";
    $stmt = mysqli_prepare($conn, $query);
    $stmt->bind_param("si", $status, $booking_id);
    $updated = $stmt->execute();

    if ($updated) {
        $_SESSION['updateStatus'] = true;
    }
}

if (isset($_SESSION["updateStatus"]) && $_SESSION["updateStatus"] == true) {
    $msg = "Updated!";
    unset($_SESSION["updateStatus"]);
}
?>
    <input type="text" value="<?php echo $msg; ?>" id="updated" hidden>

    <section class="container">
        <div class="card">
            <div class="card-body">
                <table id="productList" class="display">
                    <thead>
                        <tr>
                            <th>ID</th><th>User Name</th><th>Date</th>
                            <th>Guest Count</th><th>Message</th>
                            <th>Status</th><th>Total</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/4.0.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/3.0.2/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#productList').DataTable({
                ajax: { url: 'get-data.php', dataSrc: '' },
                columns: [
                    { data: 'booking_id' },
                    { data: 'fname' },
                    { data: 'date' },
                    { data: 'guest_count' },
                    { data: 'message' },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            return `<form method="post">
                                <input type="hidden" name="booking_id" value="${row.booking_id}">
                                <select name="status-select" class="status-select">
                                    <option value="pending" ${data === 'pending' ? 'selected' : ''}>Pending</option>
                                    <option value="confirmed" ${data === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                                    <option value="cancelled" ${data === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                                </select>
                                <input type="submit" value="Save" name="save">
                            </form>`;
                        }
                    },
                    { data: 'total' }
                ]
            });
        });
    </script>
</body>
</html>