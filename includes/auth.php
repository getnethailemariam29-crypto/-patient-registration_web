<?php
/**
 * Authentication Helper Functions
 * Debre Berhan University Student Portal
 */

session_start();

require_once __DIR__ . '/../config/database.php';

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Check if user is student
 */
function isStudent() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'student';
}

/**
 * Redirect if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: /index.php?error=Please login first");
        exit();
    }
}

/**
 * Redirect if not admin
 */
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header("Location: /student/dashboard.php?error=Access denied");
        exit();
    }
}

/**
 * Redirect if not student
 */
function requireStudent() {
    requireLogin();
    if (!isStudent()) {
        header("Location: /admin/index.php?error=Access denied");
        exit();
    }
}

/**
 * Login user
 */
function loginUser($username, $password) {
    $conn = getConnection();
    $stmt = $conn->prepare("SELECT id, username, password, role, email FROM users WHERE username = ? AND is_active = 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];
            
            $stmt->close();
            $conn->close();
            return true;
        }
    }
    
    $stmt->close();
    $conn->close();
    return false;
}

/**
 * Register new student user
 */
function registerUser($username, $password, $email, $firstName, $lastName, $gender, $departmentId, $studentIdNum) {
    $conn = getConnection();
    
    // Check if username exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $check->close();
        $conn->close();
        return ['success' => false, 'message' => 'Username already exists'];
    }
    $check->close();
    
    // Check if email exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $check->close();
        $conn->close();
        return ['success' => false, 'message' => 'Email already exists'];
    }
    $check->close();
    
    // Check if student ID exists
    $check = $conn->prepare("SELECT id FROM students WHERE student_id = ?");
    $check->bind_param("s", $studentIdNum);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $check->close();
        $conn->close();
        return ['success' => false, 'message' => 'Student ID already exists'];
    }
    $check->close();
    
    $conn->begin_transaction();
    
    try {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user
        $stmt = $conn->prepare("INSERT INTO users (username, password, role, email) VALUES (?, ?, 'student', ?)");
        $stmt->bind_param("sss", $username, $hashedPassword, $email);
        $stmt->execute();
        $userId = $conn->insert_id;
        $stmt->close();
        
        // Insert student
        $stmt = $conn->prepare("INSERT INTO students (user_id, student_id, first_name, last_name, gender, email, department_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssi", $userId, $studentIdNum, $firstName, $lastName, $gender, $email, $departmentId);
        $stmt->execute();
        $stmt->close();
        
        $conn->commit();
        $conn->close();
        return ['success' => true, 'message' => 'Registration successful'];
    } catch (Exception $e) {
        $conn->rollback();
        $conn->close();
        return ['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()];
    }
}

/**
 * Get student info
 */
function getStudentInfo($userId) {
    $conn = getConnection();
    $stmt = $conn->prepare("
        SELECT s.*, d.name as department_name, d.code as department_code 
        FROM students s 
        LEFT JOIN departments d ON s.department_id = d.id 
        WHERE s.user_id = ?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $student;
}

/**
 * Logout user
 */
function logoutUser() {
    session_unset();
    session_destroy();
    header("Location: /index.php");
    exit();
}

/**
 * Sanitize input
 */
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

/**
 * Calculate GPA
 */
function calculateGPA($grades) {
    if (empty($grades)) return 0;
    
    $totalPoints = 0;
    $totalCredits = 0;
    
    foreach ($grades as $grade) {
        $totalPoints += $grade['grade_point'] * $grade['credit_hours'];
        $totalCredits += $grade['credit_hours'];
    }
    
    return $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0;
}

/**
 * Get grade letter from total score
 */
function getGradeLetter($total) {
    if ($total >= 90) return 'A+';
    if ($total >= 85) return 'A';
    if ($total >= 80) return 'A-';
    if ($total >= 75) return 'B+';
    if ($total >= 70) return 'B';
    if ($total >= 65) return 'B-';
    if ($total >= 60) return 'C+';
    if ($total >= 50) return 'C';
    if ($total >= 45) return 'C-';
    if ($total >= 40) return 'D';
    return 'F';
}

/**
 * Get grade point from letter
 */
function getGradePoint($letter) {
    $points = [
        'A+' => 4.0, 'A' => 4.0, 'A-' => 3.75,
        'B+' => 3.5, 'B' => 3.0, 'B-' => 2.75,
        'C+' => 2.5, 'C' => 2.0, 'C-' => 1.75,
        'D' => 1.0, 'F' => 0.0
    ];
    return $points[$letter] ?? 0.0;
}
?>
