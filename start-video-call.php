<?php
require_once 'config.php';
requireLogin();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $doctor_id = $data['doctor_id'] ?? 0;
    $patient_id = $_SESSION['user_id'];
    
    // Get patient info
    $stmt = $pdo->prepare("SELECT first_name, last_name FROM patients WHERE id = ?");
    $stmt->execute([$patient_id]);
    $patient = $stmt->fetch();
    $patient_name = $patient['first_name'] . ' ' . $patient['last_name'];
    
    // Get doctor info
    $stmt = $pdo->prepare("SELECT name FROM doctors WHERE id = ?");
    $stmt->execute([$doctor_id]);
    $doctor = $stmt->fetch();
    $doctor_name = $doctor['name'] ?? 'Doctor';
    
    // Generate unique call ID
    $call_id = uniqid('call_') . '_' . $patient_id . '_' . time();
    
    // Save to database
    $stmt = $pdo->prepare("INSERT INTO video_calls (call_id, patient_id, doctor_id, patient_name, doctor_name, call_status, scheduled_time) 
                           VALUES (?, ?, ?, ?, ?, 'waiting', NOW())");
    
    if ($stmt->execute([$call_id, $patient_id, $doctor_id, $patient_name, $doctor_name])) {
        echo json_encode(['success' => true, 'call_id' => $call_id]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to create call']);
    }
}
?>