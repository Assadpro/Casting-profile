<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../inc/mailer.php';

$csrf = $_POST['csrf'] ?? '';
if (!hash_equals($_SESSION['csrf'] ?? '', $csrf)) {
  echo json_encode(['success'=>false,'error'=>'Invalid CSRF']); exit;
}

$share_token = $_POST['share_token'] ?? '';
$channel = substr(trim($_POST['channel'] ?? ''), 0, 40);
if (!$share_token) { echo json_encode(['success'=>false,'error'=>'Missing token']); exit; }

$stmt = $conn->prepare("SELECT s.id, s.nominee_id, s.subscriber_id, sb.email FROM shares s JOIN subscribers sb ON sb.id = s.subscriber_id WHERE s.share_token = ? LIMIT 1");
$stmt->bind_param('s', $share_token);
$stmt->execute();
$res = $stmt->get_result();
$share = $res->fetch_assoc();
if (!$share) { echo json_encode(['success'=>false,'error'=>'Share not found']); exit; }

$stmt = $conn->prepare("UPDATE shares SET channel = ? WHERE id = ?");
$stmt->bind_param('si', $channel, $share['id']);
$stmt->execute();

// send appreciation email
$to = $share['email'];
$subject = "Thanks for sharing your vote — Saadan Film";
$body = "<p>Thanks for sharing your vote. We appreciate your support!</p><p>Enjoy a 10% discount on our store (code: THANKS10)</p>";
@sf_send_mail($to, $subject, $body);

echo json_encode(['success'=>true]);