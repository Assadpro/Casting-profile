<?php
require_once __DIR__ . '/../config/database.php'; // sets $conn (mysqli)

// Check if the request is valid
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    exit;
}

// Get the share token and channel from the request
$shareToken = isset($_POST['share_token']) ? $_POST['share_token'] : null;
$channel = isset($_POST['channel']) ? $_POST['channel'] : null;

// Validate input
if (!$shareToken || !$channel) {
    echo json_encode(['success' => false, 'error' => 'Missing parameters.']);
    exit;
}

// Prepare and execute the database query to update share information
$stmt = $conn->prepare("UPDATE shares SET channel = ?, updated_at = NOW() WHERE token = ?");
$stmt->bind_param('ss', $channel, $shareToken);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>