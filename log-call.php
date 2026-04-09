<?php
require_once 'config.php';
requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $call_id = $data['call_id'];
    $action = $data['action'];
    
    if ($action === 'start') {
        $stmt = $pdo->prepare("UPDATE video_calls SET start_time = NOW(), call_status = 'active' WHERE call_id = ?");
        $stmt->execute([$call_id]);
    } elseif ($action === 'end') {
        $duration = $data['duration'] ?? 0;
        $stmt = $pdo->prepare("UPDATE video_calls SET end_time = NOW(), duration = ?, call_status = 'completed' WHERE call_id = ?");
        $stmt->execute([$duration, $call_id]);
    }
    
    echo json_encode(['success' => true]);
}
?>