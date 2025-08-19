<?php
require_once __DIR__ . '/../config/database.php'; // Include database connection

// Check if the request is an AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the email from the request
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $csrf = $_POST['csrf'] ?? '';

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'Invalid email address.']);
        exit;
    }

    // Check CSRF token
    session_start();
    if (!hash_equals($_SESSION['csrf'], $csrf)) {
        echo json_encode(['success' => false, 'error' => 'Invalid CSRF token.']);
        exit;
    }

    // Prepare to insert the email into the database
    $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
    $stmt->bind_param('s', $email);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $token = bin2hex(random_bytes(16)); // Generate a unique token for the subscriber
        echo json_encode(['success' => true, 'token' => $token]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Subscription failed.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>