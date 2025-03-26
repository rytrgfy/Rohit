<?php
include "dbconn.php";

// Fetch boards
$sql = "SELECT id, board_name FROM boards ORDER BY board_name ASC";
$query = $conn->query($sql);

if (!$query) {
    die(json_encode(['error' => 'Failed to fetch boards']));
}

$boards = [];
while ($row = $query->fetch_assoc()) {
    $boards[] = $row;
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($boards);
$conn->close();
?>