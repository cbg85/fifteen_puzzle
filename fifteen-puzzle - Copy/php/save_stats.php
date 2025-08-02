<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$moves = isset($_POST['moves']) ? intval($_POST['moves']) : null;
$time = isset($_POST['time']) ? intval($_POST['time']) : null;

if ($moves === null || $time === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing moves or time']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO game_stats (user_id, moves, time_seconds) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $user_id, $moves, $time);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database insert error']);
}

$stmt->close();
$conn->close();
?>
