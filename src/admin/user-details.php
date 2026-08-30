<?php
include 'connect.php';
session_start();

if($_SESSION["updated"] == true){
    $msg = "Updated!";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/3.0.2/css/dataTables.dataTables.css" />
</head>

<body>
    <input type="text" value="<?php echo $msg; ?>" id="updated" hidden>
    <section class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">User Details</h3>
            </div>
            <div class="card-body">
                <table id="productList" class="display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User Name</th>
                            <th>Date</th>
                            <th>Guest Count</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/4.0.0/jquery.min.js" integrity="sha512-8LENNbXmzI/Gbj+OwXmqR6V4QaUAw0/porPzy1+dQoJqC0JPHedWoe0DDOTL2uHA5XXJyIsPtiMHH86pVlay6A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- datatable js -->
    <script src="https://cdn.datatables.net/3.0.2/js/dataTables.min.js"></script>

    <!-- custom js -->
    <script src="script.js"></script>

</html>