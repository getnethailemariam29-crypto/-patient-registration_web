<?php
/**
 * Manage Students - Admin Panel
 * Debre Berhan University Student Portal
 */

require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Manage Students';
$conn = getConnection();

$message = '';
$error = '';

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $studentId = intval($_GET['delete']);
    $stmt = $conn->prepare("SELECT user_id FROM students WHERE id = ?");
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($result) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $result['user_id']);
        $stmt->execute();
        $stmt->close();
        $message = 'Student deleted successfully.';
    }
}

// Handle add student
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $studentIdNum = sanitize($_POST['student_id']);
    $firstName = sanitize($_POST['first_name']);
    $lastName = sanitize($_POST['last_name']);
    $gender = sanitize($_POST['gender']);
    $email = sanitize($_POST['email']);
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];
    $departmentId = intval($_POST['department_id']);
    $year = intval($_POST['year']);
    $semester = intval($_POST['semester']);
    
    $result = registerUser($username, $password, $email, $firstName, $lastName, $gender, $departmentId, $studentIdNum);
    
    if ($result['success']) {
        // Update year and semester
        $stmt = $conn->prepare("UPDATE students SET year = ?, semester = ? WHERE student_id = ?");
        $stmt->bind_param("iis", $year, $semester, $studentIdNum);
        $stmt->execute();
        $stmt->close();
        $message = 'Student added successfully!';
    } else {
        $error = $result['message'];
    }
}

if (isset($_GET['success'])) {
    $message = htmlspecialchars($_GET['success']);
}

// Search/filter
$search = sanitize($_GET['search'] ?? '');
$deptFilter = intval($_GET['department'] ?? 0);

$query = "
    SELECT s.*, d.name as department_name, u.username, u.is_active 
    FROM students s 
    LEFT JOIN departments d ON s.department_id = d.id 
    LEFT JOIN users u ON s.user_id = u.id
    WHERE 1=1
";
$params = [];
$types = '';

if (!empty($search)) {
    $query .= " AND (s.first_name LIKE ? OR s.last_name LIKE ? OR s.student_id LIKE ? OR s.email LIKE ?)";
    $searchParam = "%$search%";
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
    $types .= 'ssss';
}
if ($deptFilter > 0) {
    $query .= " AND s.department_id = ?";
    $params[] = $deptFilter;
    $types .= 'i';
}

$query .= " ORDER BY s.created_at DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$students = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get departments for filter
$departments = [];
$result = $conn->query("SELECT id, name, code FROM departments ORDER BY name");
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

$conn->close();

include __DIR__ . '/../includes/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> Manage Students</h1>
        <button class="btn btn-primary" onclick="toggleModal('addStudentModal')">
            <i class="fas fa-user-plus"></i> Add Student
        </button>
    </div>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>
    
    <!-- Search & Filter -->
    <div class="filter-bar">
        <form method="GET" class="filter-form">
            <div class="form-group">
                <input type="text" name="search" placeholder="Search by name, ID, or email..." 
                       value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="form-group">
                <select name="department">
                    <option value="">All Departments</option>
                    <?php foreach ($departments as $dept): ?>
                        <option value="<?php echo $dept['id']; ?>" <?php echo $deptFilter == $dept['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dept['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
            <a href="/admin/manage_students.php" class="btn btn-secondary"><i class="fas fa-times"></i> Clear</a>
        </form>
    </div>
    
    <div class="dashboard-card">
        <div class="card-header">
            <h2>Students List (<?php echo count($students); ?> total)</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Department</th>
                            <th>Year</th>
                            <th>Semester</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($students)): ?>
                            <tr><td colspan="9" class="no-data">No students found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($students as $s): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($s['student_id']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($s['first_name'] . ' ' . $s['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($s['gender']); ?></td>
                                    <td><?php echo htmlspecialchars($s['department_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo $s['year']; ?></td>
                                    <td><?php echo $s['semester']; ?></td>
                                    <td><?php echo htmlspecialchars($s['email'] ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $s['is_active'] ? 'success' : 'danger'; ?>">
                                            <?php echo $s['is_active'] ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/admin/edit_student.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-info" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?php echo $s['id']; ?>" class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Are you sure you want to delete this student?')" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal" id="addStudentModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-user-plus"></i> Add New Student</h2>
            <button class="modal-close" onclick="toggleModal('addStudentModal')">&times;</button>
        </div>
        <form method="POST">
            <input type="hidden" name="add_student" value="1">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Student ID *</label>
                        <input type="text" name="student_id" required placeholder="e.g., DBU/1234/14">
                    </div>
                    <div class="form-group">
                        <label>Department *</label>
                        <select name="department_id" required>
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name *</label>
                        <input type="text" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name *</label>
                        <input type="text" name="last_name" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Gender *</label>
                        <select name="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Year</label>
                        <select name="year">
                            <option value="1">Year 1</option>
                            <option value="2">Year 2</option>
                            <option value="3">Year 3</option>
                            <option value="4">Year 4</option>
                            <option value="5">Year 5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <select name="semester">
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Username *</label>
                        <input type="text" name="username" required>
                    </div>
                    <div class="form-group">
                        <label>Password *</label>
                        <input type="password" name="password" required minlength="6">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="toggleModal('addStudentModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Student</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
