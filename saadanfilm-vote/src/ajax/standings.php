<?php
require_once __DIR__ . '/../config/database.php'; // sets $conn (mysqli)

// Fetch current standings of nominees
$sql = "SELECT id, name, votes_count FROM nominees ORDER BY votes_count DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$nominees = [];
while ($row = $result->fetch_assoc()) {
    $nominees[] = $row;
}

// Prepare response
$response = [
    'success' => true,
    'nominees' => $nominees,
    'total_votes' => array_sum(array_column($nominees, 'votes_count'))
];

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>