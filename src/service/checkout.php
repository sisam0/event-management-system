<?php
session_start();
require 'vendor/autoload.php';
include 'connect2.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$stripe_secret_key = $_ENV['STRIPE_SECRET_KEY'];

\Stripe\Stripe::setApiKey($stripe_secret_key);
\Stripe\Stripe::setApiKey($stripe_secret_key);

// ---------- A) JS calls this with POST: create the payment ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $in = json_decode(file_get_contents('php://input'), true);
    $hallId = (int) $in['hall_id'];
    $packageId = (int) ($in['package_id'] ?? 0);

    // prices come from the database, not the browser
    $stmt = $conn->prepare("SELECT price FROM hall WHERE hall_id = ?");
    $stmt->bind_param("i", $hallId);
    $stmt->execute();
    $total = (int) $stmt->get_result()->fetch_assoc()['price'];

    if ($packageId) {
        $stmt = $conn->prepare("SELECT price FROM packages WHERE package_id = ?");
        $stmt->bind_param("i", $packageId);
        $stmt->execute();
        $total += (int) $stmt->get_result()->fetch_assoc()['price'];
    }

    $intent = \Stripe\PaymentIntent::create([
        'amount' => (int) ($total * 0.2 * 100),   // 20% advance
        'currency' => 'usd',
        'automatic_payment_methods' => ['enabled' => true],
    ]);

    // remember the details until payment succeeds
    $_SESSION['pending'] = [
        'intent_id' => $intent->id,
        'hall_id' => $hallId,
        'date' => $in['booking_date'],
        'guest_count' => (int) $in['guest_count'],
        'message' => $in['message'],
        'total' => $total,
    ];

    echo json_encode(['clientSecret' => $intent->client_secret]);
    exit;
}

// ---------- B) Stripe redirects here with GET: verify, insert, redirect ----------

error_log("GET: " . json_encode($_GET) . " | pending: " . json_encode($_SESSION['pending'] ?? null));

$p = $_SESSION['pending'] ?? null;
$intentId = $_GET['payment_intent'] ?? '';

// no ID in the URL, or no pending booking in the session -> nothing to verify
if ($intentId === '' || !$p || $p['intent_id'] !== $intentId) {
    header("Location: http://localhost:8081/service/service.php?payment=false");
    exit;
}

try {
    $intent = \Stripe\PaymentIntent::retrieve($intentId);
} catch (\Exception $e) {
    header("Location: http://localhost:8081/service/service.php?payment=false");
    exit;
}

if ($intent->status !== 'succeeded') {
    header("Location: http://localhost:8081/service/service.php?payment=false");
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("INSERT INTO booking (user_id, date, guest_count, status, message, total)
                        VALUES (?, ?, ?, 'confirmed', ?, ?)");
$stmt->bind_param("isisi", $userId, $p['date'], $p['guest_count'], $p['message'], $p['total']);
$stmt->execute();
$bookingId = $conn->insert_id;

$stmt = $conn->prepare("INSERT INTO booking_service (booking_id, service_id, price, date) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiis", $bookingId, $p['hall_id'], $p['total'], $p['date']);
$stmt->execute();

$stmt = $conn->prepare("INSERT INTO unavailable (service_id, date) VALUES (?, ?)");
$stmt->bind_param("is", $p['hall_id'], $p['date']);
$stmt->execute();

unset($_SESSION['pending']);   // stops a refresh from inserting twice

header("Location: http://localhost:8081/service/service.php?payment=true");
exit;
