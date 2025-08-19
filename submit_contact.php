<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['success'=>false,'error'=>'Invalid method']); exit; }
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$org = trim($_POST['organization'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$message) { echo json_encode(['success'=>false,'error'=>'Missing required fields']); exit; }
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { echo json_encode(['success'=>false,'error'=>'Invalid email']); exit; }

// optionally send email or store to DB. For now store to file for admin review
$logDir = __DIR__ . '/data';
if (!is_dir($logDir)) @mkdir($logDir,0755,true);
$entry = "[".date('c')."] $name <$email> ($org)\n".$message."\n\n";
file_put_contents($logDir.'/contacts.txt', $entry, FILE_APPEND | LOCK_EX);

echo json_encode(['success'=>true,'message'=>'Received']);
exit;
?>