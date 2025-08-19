<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

// require DB if available (adjust path if your db config is elsewhere)
$dbPath = __DIR__ . '/../config/database.php';
if (file_exists($dbPath)) require_once $dbPath;

// simple responder
function json_out($arr, $code = 200){
    http_response_code($code);
    echo json_encode($arr, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
    exit;
}

// POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_out(['success'=>false,'error'=>'Invalid method'], 405);

// CSRF check
$csrf = $_POST['csrf'] ?? '';
if (empty($_SESSION['csrf']) || !hash_equals((string)$_SESSION['csrf'], (string)$csrf)) {
    json_out(['success'=>false,'error'=>'Invalid CSRF token'], 400);
}

$email = trim((string)($_POST['email'] ?? ''));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_out(['success'=>false,'error'=>'Invalid email address'], 400);
}

// generate token
try { $token = bin2hex(random_bytes(16)); } catch (Exception $e) { $token = bin2hex(openssl_random_pseudo_bytes(16)); }

// If mysqli $conn is available, store subscriber; otherwise return token without persistence
if (!empty($conn) && $conn instanceof mysqli) {
    // ensure table
    $create = "CREATE TABLE IF NOT EXISTS subscribers (
      id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
      email VARCHAR(255) NOT NULL UNIQUE,
      token VARCHAR(128) NOT NULL,
      confirmed TINYINT(1) NOT NULL DEFAULT 0,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    if (!$conn->query($create)) {
        // don't reveal DB internals
        json_out(['success'=>false,'error'=>'Database error'], 500);
    }

    $stmt = $conn->prepare("INSERT INTO subscribers (email, token, confirmed) VALUES (?, ?, 0) ON DUPLICATE KEY UPDATE token = VALUES(token), confirmed = 0, created_at = CURRENT_TIMESTAMP");
    if (!$stmt) json_out(['success'=>false,'error'=>'Database error'], 500);
    $stmt->bind_param('ss', $email, $token);
    if (!$stmt->execute()) {
        json_out(['success'=>false,'error'=>'Database error'], 500);
    }
    $stmt->close();
}

// send confirmation email (may require SMTP config on XAMPP)
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$confirmUrl = $scheme . '://' . $host . dirname($_SERVER['SCRIPT_NAME']) . '/../ajax/confirm_sub.php?token=' . urlencode($token);

$subject = 'Confirm your subscription';
$message = "Thanks for subscribing.\n\nPlease confirm by visiting:\n\n" . $confirmUrl . "\n\nIf you didn't request this, ignore.";
$from = 'no-reply@' . preg_replace('/^www\./','',$host);
$headers = "From: Saadan Film <{$from}>\r\nReply-To: {$from}\r\nContent-Type: text/plain; charset=utf-8\r\n";

// @ to suppress mail warnings on misconfigured XAMPP; success is not guaranteed locally.
@mail($email, $subject, $message, $headers);

// return success and token
json_out(['success'=>true, 'token'=>$token, 'message'=>'Confirmation email sent.']);
?>