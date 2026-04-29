<?php
/**
 * Registration Page
 * Debre Berhan University Student Portal
 */

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: /student/dashboard.php");
    exit();
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';

$error = '';
$success = '';
$departments = [];

// Fetch departments
$conn = getConnection();
$result = $conn->query("SELECT id, name, code FROM departments ORDER BY name");
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}
$conn->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = sanitize($_POST['student_id'] ?? '');
    $firstName = sanitize($_POST['first_name'] ?? '');
    $lastName = sanitize($_POST['last_name'] ?? '');
    $gender = sanitize($_POST['gender'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $departmentId = intval($_POST['department_id'] ?? 0);
    
    if (empty($studentId) || empty($firstName) || empty($lastName) || empty($gender) || 
        empty($email) || empty($username) || empty($password) || empty($departmentId)) {
        $error = 'Please fill in all required fields';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address';
    } else {
        $result = registerUser($username, $password, $email, $firstName, $lastName, $gender, $departmentId, $studentId);
        if ($result['success']) {
            header("Location: /index.php?success=Registration successful! Please login.");
            exit();
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DBU Student Portal</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page">
    <div class="login-container register-container">
        <div class="login-header">
            <div class="university-logo">
                <i class="fas fa-university"></i>
            </div>
            <h1>Debre Berhan University</h1>
            <h2>Student Registration</h2>
        </div>
        
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="" class="login-form register-form" id="registerForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="student_id"><i class="fas fa-id-card"></i> Student ID *</label>
                    <input type="text" id="student_id" name="student_id" placeholder="e.g., DBU/1234/14" required
                           value="<?php echo isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="department_id"><i class="fas fa-building"></i> Department *</label>
                    <select id="department_id" name="department_id" required>
                        <option value="">Select Department</option>
                        <?php foreach ($departments as $dept): ?>
                            <option value="<?php echo $dept['id']; ?>" 
                                <?php echo (isset($_POST['department_id']) && $_POST['department_id'] == $dept['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dept['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="first_name"><i class="fas fa-user"></i> First Name *</label>
                    <input type="text" id="first_name" name="first_name" placeholder="First name" required
                           value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="last_name"><i class="fas fa-user"></i> Last Name *</label>
                    <input type="text" id="last_name" name="last_name" placeholder="Last name" required
                           value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="gender"><i class="fas fa-venus-mars"></i> Gender *</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email *</label>
                    <input type="email" id="email" name="email" placeholder="your@email.com" required
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="username"><i class="fas fa-user-circle"></i> Username *</label>
                <input type="text" id="username" name="username" placeholder="Choose a username" required
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password *</label>
                    <input type="password" id="password" name="password" placeholder="Min 6 characters" required minlength="6">
                </div>
                <div class="form-group">
                    <label for="confirm_password"><i class="fas fa-lock"></i> Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-user-plus"></i> Register
            </button>
        </form>
        
        <div class="login-footer">
            <p>Already have an account? <a href="/index.php">Login here</a></p>
        </div>
    </div>
    
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    </script>
</body>
</html>
