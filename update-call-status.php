<?php
require_once 'config.php';
requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $call_id = $data['call_id'];
    $status = $data['status'];
    
    $stmt = $pdo->prepare("UPDATE video_calls SET call_status = ? WHERE call_id = ?");
    $stmt->execute([$status, $call_id]);
    
    echo json_encode(['success' => true]);
}
?>