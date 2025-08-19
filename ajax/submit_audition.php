<?php
header('Content-Type: application/json');
include __DIR__ . '/../config/database.php';
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$project_id = $_POST['project_id'] ?? 'general';
$bio = trim($_POST['bio'] ?? '');
$demo_reel = trim($_POST['demo_reel'] ?? '');

if (!$name || !$email || !$bio) {
  echo json_encode(['success'=>false,'error'=>'Missing required fields']);
  exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  echo json_encode(['success'=>false,'error'=>'Invalid email']);
  exit;
}
$casting_id = null;
if ($project_id !== 'general') $casting_id = (int)$project_id;

$stmt = $conn->prepare("INSERT INTO auditions (casting_id, name, email, phone, bio, demo_reel, status) VALUES (?, ?, ?, ?, ?, ?, 'submitted')");
$stmt->bind_param('isssss', $casting_id, $name, $email, $phone, $bio, $demo_reel);
if ($stmt->execute()) {
  echo json_encode(['success'=>true,'id'=>$conn->insert_id]);
} else {
  echo json_encode(['success'=>false,'error'=>'DB error']);
}