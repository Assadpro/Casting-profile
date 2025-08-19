<?php
header('Content-Type: application/json; charset=utf-8');
include __DIR__ . '/../config/database.php'; // uses $conn from [config/database.php](config/database.php)

function fetch_all_assoc($conn, $sql) {
    $res = $conn->query($sql);
    $out = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) $out[] = $row;
    }
    return $out;
}

$response = [];
$response['billboards']    = fetch_all_assoc($conn, "SELECT * FROM billboards WHERE active=1 ORDER BY position ASC");
$response['projects']      = fetch_all_assoc($conn, "SELECT * FROM projects ORDER BY featured DESC, release_date DESC, id DESC");
$response['featured']      = fetch_all_assoc($conn, "SELECT * FROM projects WHERE featured=1 ORDER BY release_date DESC LIMIT 6");
$response['nominees']      = fetch_all_assoc($conn, "SELECT * FROM nominees ORDER BY votes DESC");
$response['stats']         = fetch_all_assoc($conn, "SELECT * FROM stats");
$response['news']          = fetch_all_assoc($conn, "SELECT * FROM news ORDER BY created_at DESC LIMIT 6");
$response['timeline']      = fetch_all_assoc($conn, "SELECT * FROM timeline ORDER BY position ASC");
$response['quick_actions'] = fetch_all_assoc($conn, "SELECT * FROM quick_actions ORDER BY id ASC");

echo json_encode($response, JSON_UNESCAPED_UNICODE);