<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$token = $_GET['token'] ?? '';
if (!$token) {
    echo "Invalid token.";
    exit;
}

// mark confirmed
$stmt = $conn->prepare("UPDATE subscribers SET confirmed = 1 WHERE token = ? LIMIT 1");
if ($stmt) {
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();
} else {
    $affected = 0;
}

if ($affected) {
    echo "<!doctype html><html><head><meta charset='utf-8'><title>Subscription confirmed</title></head><body><h1>Subscription confirmed</h1><p>Thank you — your email has been confirmed. You can now vote.</p></body></html>";
} else {
    echo "<!doctype html><html><head><meta charset='utf-8'><title>Already confirmed or invalid</title></head><body><h1>Invalid or already confirmed</h1><p>The confirmation link is invalid or the address is already confirmed.</p></body></html>";
}