<?php
session_start();
require_once 'vendor/autoload.php';
include 'connect2.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$stripe_secret_key = $_ENV['STRIPE_SECRET_KEY'];

\Stripe\Stripe::setApiKey($stripe_secret_key);

// header('Content-type:application/json');
// $in = json_decode(file_get_contents('php://input'), true);

$package_id = (int) $_POST['package_id'];

$stmt = $conn->prepare("SELECT name, price FROM packages WHERE package_id = ?");
$stmt->bind_param("i", $package_id);
$stmt->execute();
$package = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$package) {
    http_response_code(404);
    echo json_encode(['error' => 'Package not found']);
    exit;
}

$checkout_session = \Stripe\Checkout\Session::create([
    "mode" => "payment",
    "success_url" => "http://localhost:8081/service/service.php?payment=true",
    "cancel_url" => "http://localhost:8081/service/book-menu.php?package_id=" . $package_id,
    "line_items" => [
        [
            "quantity" => 1,
            "price_data" => [
                "currency" => "usd",
                "unit_amount" => (int) round($package['price'] * 0.2),
                "product_data" => [
                    "name" => "package"
                ]
            ]
        ]
    ]
]);

http_response_code(303);
header("Location: " . $checkout_session->url);
