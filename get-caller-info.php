<?php
require_once 'config.php';

header('Content-Type: application/json');

$call_id = $_GET['callId'] ?? '';

if ($call_id) {
    $stmt = $pdo->prepare("SELECT patient_name, doctor_name FROM video_calls WHERE call_id = ?");
    $stmt->execute([$call_id]);
    $call = $stmt->fetch();
    
    echo json_encode(['name' => $call['doctor_name'] ?? $call['patient_name'] ?? 'Unknown']);
} else {
    echo json_encode(['name' => 'Unknown']);
}
?>