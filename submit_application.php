<?php
// simple application receiver — saves upload and returns JSON
header('Content-Type: application/json');
include __DIR__ . '/config/database.php'; // optional; included for possible DB storage

$maxSize = 4 * 1024 * 1024; // 4MB
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { echo json_encode(['success'=>false,'error'=>'Invalid method']); exit; }
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$role = trim($_POST['role'] ?? '');
$cover = trim($_POST['cover'] ?? '');

if (!$name || !$email || !$role) { echo json_encode(['success'=>false,'error'=>'Missing required fields']); exit; }
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { echo json_encode(['success'=>false,'error'=>'Invalid email']); exit; }

if (empty($_FILES['resume']) || $_FILES['resume']['error'] !== UPLOAD_ERR_OK) {
  echo json_encode(['success'=>false,'error'=>'Resume upload failed']); exit;
}
if ($_FILES['resume']['size'] > $maxSize) { echo json_encode(['success'=>false,'error'=>'File too large']); exit; }

$uploads = __DIR__ . '/uploads/resumes';
if (!is_dir($uploads)) @mkdir($uploads, 0755, true);
$ext = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
$filename = preg_replace('/[^a-z0-9-_\.]/i','_', strtolower($name.'_'.$role.'_'.time())) . '.' . $ext;
$dest = $uploads . '/' . $filename;
if (!move_uploaded_file($_FILES['resume']['tmp_name'], $dest)) {
  echo json_encode(['success'=>false,'error'=>'Failed to save file']); exit;
}

// Optionally store in DB — omitted for brevity

echo json_encode(['success'=>true,'message'=>'Saved','file'=>$filename]);
exit;

?>