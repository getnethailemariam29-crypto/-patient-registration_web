<?php
session_start();

$host = 'localhost';
$dbname = 'hospital_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isDoctor() {
    return isset($_SESSION['is_doctor']) && $_SESSION['is_doctor'] == true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function requireDoctor() {
    requireLogin();
    if (!isDoctor()) {
        header('Location: home.php');
        exit;
    }
}

function sanitize($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

function getUserById($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM patients WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch();
}
?>