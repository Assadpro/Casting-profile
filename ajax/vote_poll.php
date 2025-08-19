<?php
header('Content-Type: application/json');
include __DIR__ . '/../config/database.php';

$raw = json_decode(file_get_contents('php://input'), true);
$poll_id = (int)($raw['poll_id'] ?? 0);
$option_index = (int)($raw['option_index'] ?? -1);
if (!$poll_id || $option_index < 0) { echo json_encode(['success'=>false,'error'=>'Invalid input']); exit; }

// basic rate-limit by IP to prevent repeat votes (simple)
$ip = $_SERVER['REMOTE_ADDR'];

$conn->begin_transaction();
try {
    $r = $conn->query("SELECT * FROM polls WHERE id = ".(int)$poll_id." FOR UPDATE");
    if (!$r || $r->num_rows === 0) throw new Exception('Poll not found');
    $poll = $r->fetch_assoc();
    $options = json_decode($poll['options'], true);
    if (!is_array($options) || !isset($options[$option_index])) throw new Exception('Option invalid');

    // Check existing vote by IP
    $check = $conn->prepare("SELECT id FROM poll_votes WHERE poll_id = ? AND voter_ip = ? LIMIT 1");
    $check->bind_param('is',$poll_id,$ip);
    $check->execute(); $check->store_result();
    if ($check->num_rows > 0) { throw new Exception('You have already voted'); }

    $ins = $conn->prepare("INSERT INTO poll_votes (poll_id, option_index, voter_ip) VALUES (?, ?, ?)");
    $ins->bind_param('iis', $poll_id, $option_index, $ip);
    $ins->execute();

    $upd = $conn->prepare("UPDATE polls SET total_votes = total_votes + 1 WHERE id = ?");
    $upd->bind_param('i', $poll_id);
    $upd->execute();

    $conn->commit();
    echo json_encode(['success'=>true]);
} catch (Exception $ex) {
    $conn->rollback();
    echo json_encode(['success'=>false,'error'=>$ex->getMessage()]);
}