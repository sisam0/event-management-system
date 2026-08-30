<?php
//php backend for fetching data from database and returning it as JSON for AJAX requests
$conn = new mysqli('db', 'admin', 'event123', 'event_db');

// Check connection
if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: " . $conn->connect_error;
    exit();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' 
    && $_POST['action'] === 'status-select') {

    $bookingId = $_POST['booking_id'];

    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $bookingId);
    $stmt->execute();

    if(!$stmt){
        echo "not updated";
    }

}


$data = array();

$query = "SELECT 
        b.booking_id, u.fname, u.lname, b.date, b.guest_count, b.message, b.status, b.total 
        FROM booking b join user u on b.user_id = u.user_id";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    http_response_code(500);
    echo json_encode(array("error" => "Query failed"));
    exit;
}

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// need to echo the data as JSON so that it can be used by the AJAX request in the frontend
echo json_encode($data);

$stmt->close();
