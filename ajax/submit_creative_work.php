<?php
header('Content-Type: application/json');
include __DIR__ . '/../config/database.php';

$title = trim($_POST['title'] ?? '');
$type = $_POST['type'] ?? 'text';
$content = trim($_POST['content'] ?? '');
$author = trim($_POST['author'] ?? '');

if (!$title || !$content || !$author) { echo json_encode(['success'=>false,'error'=>'Missing fields']); exit; }
$type = in_array($type, ['text','image','video']) ? $type : 'text';

$stmt = $conn->prepare("INSERT INTO fan_submissions (title, content, type, author_name, status) VALUES (?, ?, ?, ?, 'pending')");
$stmt->bind_param('ssss', $title, $content, $type, $author);
if ($stmt->execute()) echo json_encode(['success'=>true,'id'=>$conn->insert_id]);
else echo json_encode(['success'=>false,'error'=>'DB error']);

?>