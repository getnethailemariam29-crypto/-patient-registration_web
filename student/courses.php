<?php
/**
 * Student Courses Page
 * Debre Berhan University Student Portal
 */

require_once __DIR__ . '/../includes/auth.php';
requireStudent();

$pageTitle = 'My Courses';
$student = getStudentInfo($_SESSION['user_id']);

$conn = getConnection();

// Get enrolled courses
$stmt = $conn->prepare("
    SELECT c.*, e.semester as enrolled_semester, e.academic_year, e.status as enrollment_status,
           d.name as department_name
    FROM enrollments e 
    JOIN courses c ON e.course_id = c.id 
    LEFT JOIN departments d ON c.department_id = d.id
    WHERE e.student_id = ?
    ORDER BY e.academic_year DESC, e.semester DESC, c.course_name
");
$stmt->bind_param("i", $student['id']);
$stmt->execute();
$courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get available courses for registration
$stmt = $conn->prepare("
    SELECT c.*, d.name as department_name 
    FROM courses c 
    LEFT JOIN departments d ON c.department_id = d.id
    WHERE c.id NOT IN (SELECT course_id FROM enrollments WHERE student_id = ? AND status = 'active')
    ORDER BY c.course_code
");
$stmt->bind_param("i", $student['id']);
$stmt->execute();
$availableCourses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle course enrollment
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll'])) {
    $courseId = intval($_POST['course_id']);
    $semester = intval($_POST['semester'] ?? $student['semester']);
    $academicYear = sanitize($_POST['academic_year'] ?? '2024/2025');
    
    $stmt = $conn->prepare("INSERT INTO enrollments (student_id, course_id, semester, academic_year) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $student['id'], $courseId, $semester, $academicYear);
    
    if ($stmt->execute()) {
        $message = 'Successfully enrolled in the course!';
        header("Location: /student/courses.php?success=" . urlencode($message));
        exit();
    } else {
        $error = 'Failed to enroll. You may already be enrolled in this course.';
    }
    $stmt->close();
}

if (isset($_GET['success'])) {
    $message = htmlspecialchars($_GET['success']);
}

$conn->close();

include __DIR__ . '/../includes/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-book"></i> My Courses</h1>
    </div>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="dashboard-card">
        <div class="card-header">
            <h2><i class="fas fa-list"></i> Enrolled Courses</h2>
        </div>
        <div class="card-body">
            <?php if (empty($courses)): ?>
                <p class="no-data">You are not enrolled in any courses yet.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Department</th>
                                <th>Credit Hours</th>
                                <th>Instructor</th>
                                <th>Semester</th>
                                <th>Academic Year</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($course['course_code']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                                    <td><?php echo htmlspecialchars($course['department_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo $course['credit_hours']; ?></td>
                                    <td><?php echo htmlspecialchars($course['instructor'] ?? 'TBA'); ?></td>
                                    <td>Semester <?php echo $course['enrolled_semester']; ?></td>
                                    <td><?php echo htmlspecialchars($course['academic_year']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $course['enrollment_status'] === 'active' ? 'success' : ($course['enrollment_status'] === 'completed' ? 'info' : 'danger'); ?>">
                                            <?php echo ucfirst($course['enrollment_status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="dashboard-card" style="margin-top: 20px;">
        <div class="card-header">
            <h2><i class="fas fa-plus-circle"></i> Available Courses for Registration</h2>
        </div>
        <div class="card-body">
            <?php if (empty($availableCourses)): ?>
                <p class="no-data">No additional courses available for registration.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Department</th>
                                <th>Credit Hours</th>
                                <th>Instructor</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($availableCourses as $course): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($course['course_code']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                                    <td><?php echo htmlspecialchars($course['department_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo $course['credit_hours']; ?></td>
                                    <td><?php echo htmlspecialchars($course['instructor'] ?? 'TBA'); ?></td>
                                    <td>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                            <input type="hidden" name="enroll" value="1">
                                            <input type="hidden" name="academic_year" value="2024/2025">
                                            <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Enroll in <?php echo htmlspecialchars($course['course_name']); ?>?')">
                                                <i class="fas fa-plus"></i> Enroll
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
