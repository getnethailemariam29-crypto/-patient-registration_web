<?php
/**
 * Manage Courses - Admin Panel
 * Debre Berhan University Student Portal
 */

require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Manage Courses';
$conn = getConnection();

$message = '';
$error = '';

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $courseId = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM courses WHERE id = ?");
    $stmt->bind_param("i", $courseId);
    if ($stmt->execute()) {
        $message = 'Course deleted successfully.';
    }
    $stmt->close();
}

// Handle add course
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
    $courseCode = sanitize($_POST['course_code']);
    $courseName = sanitize($_POST['course_name']);
    $departmentId = intval($_POST['department_id']);
    $creditHours = intval($_POST['credit_hours']);
    $semester = intval($_POST['semester']);
    $year = intval($_POST['year']);
    $instructor = sanitize($_POST['instructor']);
    $description = sanitize($_POST['description']);
    
    $stmt = $conn->prepare("INSERT INTO courses (course_code, course_name, department_id, credit_hours, semester, year, instructor, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiisss", $courseCode, $courseName, $departmentId, $creditHours, $semester, $year, $instructor, $description);
    
    if ($stmt->execute()) {
        $message = 'Course added successfully!';
    } else {
        $error = 'Failed to add course. Course code may already exist.';
    }
    $stmt->close();
}

// Handle edit course
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_course'])) {
    $courseId = intval($_POST['course_id']);
    $courseName = sanitize($_POST['course_name']);
    $departmentId = intval($_POST['department_id']);
    $creditHours = intval($_POST['credit_hours']);
    $semester = intval($_POST['semester']);
    $year = intval($_POST['year']);
    $instructor = sanitize($_POST['instructor']);
    
    $stmt = $conn->prepare("UPDATE courses SET course_name = ?, department_id = ?, credit_hours = ?, semester = ?, year = ?, instructor = ? WHERE id = ?");
    $stmt->bind_param("siiissi", $courseName, $departmentId, $creditHours, $semester, $year, $instructor, $courseId);
    
    if ($stmt->execute()) {
        $message = 'Course updated successfully!';
    } else {
        $error = 'Failed to update course.';
    }
    $stmt->close();
}

// Get courses
$courses = [];
$result = $conn->query("
    SELECT c.*, d.name as department_name, 
           (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND status = 'active') as enrolled_count
    FROM courses c 
    LEFT JOIN departments d ON c.department_id = d.id 
    ORDER BY c.course_code
");
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

// Get departments
$departments = [];
$result = $conn->query("SELECT id, name FROM departments ORDER BY name");
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

$conn->close();

include __DIR__ . '/../includes/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-book"></i> Manage Courses</h1>
        <button class="btn btn-primary" onclick="toggleModal('addCourseModal')">
            <i class="fas fa-plus"></i> Add Course
        </button>
    </div>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="dashboard-card">
        <div class="card-header">
            <h2>All Courses (<?php echo count($courses); ?>)</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Course Name</th>
                            <th>Department</th>
                            <th>Credits</th>
                            <th>Instructor</th>
                            <th>Year/Sem</th>
                            <th>Enrolled</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($courses)): ?>
                            <tr><td colspan="8" class="no-data">No courses found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($courses as $c): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($c['course_code']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($c['course_name']); ?></td>
                                    <td><?php echo htmlspecialchars($c['department_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo $c['credit_hours']; ?></td>
                                    <td><?php echo htmlspecialchars($c['instructor'] ?? 'TBA'); ?></td>
                                    <td>Year <?php echo $c['year']; ?> / Sem <?php echo $c['semester']; ?></td>
                                    <td><span class="badge badge-info"><?php echo $c['enrolled_count']; ?></span></td>
                                    <td>
                                        <a href="?delete=<?php echo $c['id']; ?>" class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Delete this course? This will remove all related enrollments and grades.')">
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

<!-- Add Course Modal -->
<div class="modal" id="addCourseModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-plus"></i> Add New Course</h2>
            <button class="modal-close" onclick="toggleModal('addCourseModal')">&times;</button>
        </div>
        <form method="POST">
            <input type="hidden" name="add_course" value="1">
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label>Course Code *</label>
                        <input type="text" name="course_code" required placeholder="e.g., CS101">
                    </div>
                    <div class="form-group">
                        <label>Course Name *</label>
                        <input type="text" name="course_name" required placeholder="e.g., Introduction to CS">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Department</label>
                        <select name="department_id">
                            <option value="">Select Department</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['id']; ?>"><?php echo htmlspecialchars($dept['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Credit Hours</label>
                        <input type="number" name="credit_hours" value="3" min="1" max="6">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Year</label>
                        <select name="year">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>">Year <?php echo $i; ?></option>
                            <?php endfor; ?>
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
                <div class="form-group">
                    <label>Instructor</label>
                    <input type="text" name="instructor" placeholder="Instructor name">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" rows="3" placeholder="Course description"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="toggleModal('addCourseModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Course</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
