<?php
header('Content-Type: application/json');
include 'connect2.php';

$service_id = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0;

if ($service_id <= 0) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT date FROM unavailable WHERE service_id = ?");
$stmt->bind_param("i", $service_id);
$stmt->execute();

$dates = array_column($stmt->get_result()->fetch_all(MYSQLI_ASSOC), 'date');

echo json_encode($dates);
