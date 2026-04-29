<?php
/**
 * Edit Student - Admin Panel
 * Debre Berhan University Student Portal
 */

require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Edit Student';
$conn = getConnection();

$message = '';
$error = '';
$studentId = intval($_GET['id'] ?? 0);

if ($studentId === 0) {
    header("Location: /admin/manage_students.php");
    exit();
}

// Get student
$stmt = $conn->prepare("
    SELECT s.*, u.username, u.is_active 
    FROM students s 
    JOIN users u ON s.user_id = u.id 
    WHERE s.id = ?
");
$stmt->bind_param("i", $studentId);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$student) {
    header("Location: /admin/manage_students.php?error=Student not found");
    exit();
}

// Get departments
$departments = [];
$result = $conn->query("SELECT id, name FROM departments ORDER BY name");
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = sanitize($_POST['first_name']);
    $lastName = sanitize($_POST['last_name']);
    $gender = sanitize($_POST['gender']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    $departmentId = intval($_POST['department_id']);
    $year = intval($_POST['year']);
    $semester = intval($_POST['semester']);
    $isActive = isset($_POST['is_active']) ? 1 : 0;
    
    $stmt = $conn->prepare("
        UPDATE students SET first_name = ?, last_name = ?, gender = ?, email = ?, 
        phone = ?, address = ?, department_id = ?, year = ?, semester = ? WHERE id = ?
    ");
    $stmt->bind_param("ssssssiiii", $firstName, $lastName, $gender, $email, $phone, $address, $departmentId, $year, $semester, $studentId);
    $stmt->execute();
    $stmt->close();
    
    // Update user status
    $stmt = $conn->prepare("UPDATE users SET is_active = ? WHERE id = ?");
    $stmt->bind_param("ii", $isActive, $student['user_id']);
    $stmt->execute();
    $stmt->close();
    
    $message = 'Student updated successfully!';
    
    // Refresh data
    $stmt = $conn->prepare("SELECT s.*, u.username, u.is_active FROM students s JOIN users u ON s.user_id = u.id WHERE s.id = ?");
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

$conn->close();

include __DIR__ . '/../includes/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-user-edit"></i> Edit Student</h1>
        <a href="/admin/manage_students.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $message; ?></div>
    <?php endif; ?>
    
    <div class="dashboard-card">
        <div class="card-body">
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>Student ID</label>
                        <input type="text" value="<?php echo htmlspecialchars($student['student_id']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" value="<?php echo htmlspecialchars($student['username']); ?>" disabled>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name *</label>
                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($student['first_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name *</label>
                        <input type="text" name="last_name" value="<?php echo htmlspecialchars($student['last_name']); ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender">
                            <option value="Male" <?php echo $student['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo $student['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department_id">
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['id']; ?>" <?php echo $student['department_id'] == $dept['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dept['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($student['email'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Year</label>
                        <select name="year">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo $student['year'] == $i ? 'selected' : ''; ?>>Year <?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <select name="semester">
                            <option value="1" <?php echo $student['semester'] == 1 ? 'selected' : ''; ?>>Semester 1</option>
                            <option value="2" <?php echo $student['semester'] == 2 ? 'selected' : ''; ?>>Semester 2</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" rows="3"><?php echo htmlspecialchars($student['address'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" <?php echo $student['is_active'] ? 'checked' : ''; ?>>
                        Account Active
                    </label>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Student</button>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
