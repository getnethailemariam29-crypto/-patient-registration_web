<?php
/**
 * Manage Grades - Admin Panel
 * Debre Berhan University Student Portal
 */

require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Manage Grades';
$conn = getConnection();

$message = '';
$error = '';

// Handle grade submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_grade'])) {
    $enrollmentId = intval($_POST['enrollment_id']);
    $midterm = floatval($_POST['midterm']);
    $finalExam = floatval($_POST['final_exam']);
    $assignment = floatval($_POST['assignment']);
    $quiz = floatval($_POST['quiz']);
    $total = $midterm + $finalExam + $assignment + $quiz;
    $gradeLetter = getGradeLetter($total);
    $gradePoint = getGradePoint($gradeLetter);
    
    // Check if grade exists
    $stmt = $conn->prepare("SELECT id FROM grades WHERE enrollment_id = ?");
    $stmt->bind_param("i", $enrollmentId);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if ($existing) {
        $stmt = $conn->prepare("UPDATE grades SET midterm = ?, final_exam = ?, assignment = ?, quiz = ?, total = ?, grade_letter = ?, grade_point = ? WHERE enrollment_id = ?");
        $stmt->bind_param("dddddsdi", $midterm, $finalExam, $assignment, $quiz, $total, $gradeLetter, $gradePoint, $enrollmentId);
    } else {
        $stmt = $conn->prepare("INSERT INTO grades (enrollment_id, midterm, final_exam, assignment, quiz, total, grade_letter, grade_point) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idddddsd", $enrollmentId, $midterm, $finalExam, $assignment, $quiz, $total, $gradeLetter, $gradePoint);
    }
    
    if ($stmt->execute()) {
        // Update enrollment status to completed
        $updateStmt = $conn->prepare("UPDATE enrollments SET status = 'completed' WHERE id = ?");
        $updateStmt->bind_param("i", $enrollmentId);
        $updateStmt->execute();
        $updateStmt->close();
        
        $message = "Grade submitted successfully! Total: $total, Grade: $gradeLetter ($gradePoint)";
    } else {
        $error = 'Failed to submit grade.';
    }
    $stmt->close();
}

// Get filter params
$selectedCourse = intval($_GET['course_id'] ?? 0);

// Get courses
$courses = [];
$result = $conn->query("SELECT id, course_code, course_name FROM courses ORDER BY course_code");
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

// Get enrollments with grades for selected course
$enrollments = [];
if ($selectedCourse > 0) {
    $stmt = $conn->prepare("
        SELECT e.*, s.student_id as student_number, s.first_name, s.last_name,
               c.course_code, c.course_name,
               g.midterm, g.final_exam, g.assignment, g.quiz, g.total, g.grade_letter, g.grade_point
        FROM enrollments e
        JOIN students s ON e.student_id = s.id
        JOIN courses c ON e.course_id = c.id
        LEFT JOIN grades g ON g.enrollment_id = e.id
        WHERE e.course_id = ?
        ORDER BY s.last_name, s.first_name
    ");
    $stmt->bind_param("i", $selectedCourse);
    $stmt->execute();
    $enrollments = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$conn->close();

include __DIR__ . '/../includes/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-chart-bar"></i> Manage Grades</h1>
    </div>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>
    
    <!-- Course Selection -->
    <div class="filter-bar">
        <form method="GET" class="filter-form">
            <div class="form-group" style="flex: 1;">
                <select name="course_id" onchange="this.form.submit()">
                    <option value="">-- Select a Course --</option>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo $selectedCourse == $c['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c['course_code'] . ' - ' . $c['course_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
    
    <?php if ($selectedCourse > 0): ?>
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-list"></i> Student Grades (<?php echo count($enrollments); ?> enrolled)</h2>
            </div>
            <div class="card-body">
                <?php if (empty($enrollments)): ?>
                    <p class="no-data">No students enrolled in this course.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Midterm (30)</th>
                                    <th>Assignment (20)</th>
                                    <th>Quiz (10)</th>
                                    <th>Final (40)</th>
                                    <th>Total</th>
                                    <th>Grade</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($enrollments as $e): ?>
                                    <tr>
                                        <form method="POST">
                                            <input type="hidden" name="submit_grade" value="1">
                                            <input type="hidden" name="enrollment_id" value="<?php echo $e['id']; ?>">
                                            <td><strong><?php echo htmlspecialchars($e['student_number']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($e['first_name'] . ' ' . $e['last_name']); ?></td>
                                            <td>
                                                <input type="number" name="midterm" class="grade-input" min="0" max="30" step="0.5"
                                                       value="<?php echo $e['midterm'] ?? ''; ?>" required>
                                            </td>
                                            <td>
                                                <input type="number" name="assignment" class="grade-input" min="0" max="20" step="0.5"
                                                       value="<?php echo $e['assignment'] ?? ''; ?>" required>
                                            </td>
                                            <td>
                                                <input type="number" name="quiz" class="grade-input" min="0" max="10" step="0.5"
                                                       value="<?php echo $e['quiz'] ?? ''; ?>" required>
                                            </td>
                                            <td>
                                                <input type="number" name="final_exam" class="grade-input" min="0" max="40" step="0.5"
                                                       value="<?php echo $e['final_exam'] ?? ''; ?>" required>
                                            </td>
                                            <td><strong><?php echo $e['total'] !== null ? number_format($e['total'], 1) : '-'; ?></strong></td>
                                            <td>
                                                <?php if ($e['grade_letter']): ?>
                                                    <span class="grade-badge"><?php echo $e['grade_letter']; ?></span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">Pending</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-save"></i> Save
                                                </button>
                                            </td>
                                        </form>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="dashboard-card">
            <div class="card-body">
                <p class="no-data">Please select a course to manage grades.</p>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
