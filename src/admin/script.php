<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    include 'connect.php';
    session_start();

    if (isset($_POST['save'])) {
        $status = $_POST['status-select'];
        $booking_id = $_POST['booking_id'];
        $query = "update booking set status='$status' where booking_id='$booking_id'";
        $stmt = mysqli_prepare($conn, $query);
        $updated = $stmt->execute();

        if($updated){
            $_SESSION['updateStatus']=true;
        }
    }

    ?>
    <script>
        document.getElementById("updated").addEventListener("change", function(){
            //sweet alert 
        });

        $(document).ready(function() {

            $('#productList').DataTable({
                ajax: {
                    url: 'get-data.php',
                    dataSrc: ''
                },
                columns: [{
                        data: 'booking_id'
                    },
                    {
                        data: 'fname'
                    },
                    {
                        data: 'date'
                    },
                    {
                        data: 'guest_count'
                    },
                    {
                        data: 'message'
                    },
                    {
                        data: 'status',
                        render: function(data, type, row) {
                            return `
                        <form method="post">
                        <input type="number" value="${row.booking_id}" name="booking_id" hidden>                        
                        <select name="status-select" class="status-select" data-id="${row.booking_id}">
                            <option value="pending" ${data === 'pending' ? 'selected' : ''}>Pending</option>
                            <option value="confirmed" ${data === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                            <option value="cancelled" ${data === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                        </select>
                        <input type="submit" value="save" name="save"></input>
                        <form>
                    `;

                        }
                    },
                    {
                        data: 'total'
                    }
                ]
            });

        });


    </script>
</body>

</html>