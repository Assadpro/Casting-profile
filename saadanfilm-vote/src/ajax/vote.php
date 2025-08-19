<?php
require_once __DIR__ . '/../config/database.php'; // sets $conn (mysqli)

header('Content-Type: application/json');

// CSRF protection
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
    echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
    exit;
}

// Validate input
$nomineeId = isset($_POST['nominee_id']) ? (int)$_POST['nominee_id'] : 0;
$subscriberToken = isset($_POST['subscriber_token']) ? $_POST['subscriber_token'] : '';

if ($nomineeId <= 0 || empty($subscriberToken)) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

// Check if subscriber exists (you should implement this function)
$subscriberId = getSubscriberIdByToken($subscriberToken);
if (!$subscriberId) {
    echo json_encode(['success' => false, 'error' => 'Subscriber not found']);
    exit;
}

// Update votes count
$stmt = $conn->prepare("UPDATE nominees SET votes_count = votes_count + 1 WHERE id = ?");
$stmt->bind_param('i', $nomineeId);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Vote could not be recorded']);
    exit;
}

// Optionally, you can log the vote in a separate table (not implemented here)

// Fetch updated nominee data
$stmt = $conn->prepare("SELECT name, votes_count FROM nominees WHERE id = ?");
$stmt->bind_param('i', $nomineeId);
$stmt->execute();
$result = $stmt->get_result();
$nominee = $result->fetch_assoc();

echo json_encode([
    'success' => true,
    'nominee_name' => $nominee['name'],
    'votes_count' => (int)$nominee['votes_count'],
    'share_token' => bin2hex(random_bytes(16)) // Generate a share token for sharing
]);
?>