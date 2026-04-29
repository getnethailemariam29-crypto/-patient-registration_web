<?php
/**
 * Student Grades Page
 * Debre Berhan University Student Portal
 */

require_once __DIR__ . '/../includes/auth.php';
requireStudent();

$pageTitle = 'My Grades';
$student = getStudentInfo($_SESSION['user_id']);

$conn = getConnection();

// Get grades with course info
$stmt = $conn->prepare("
    SELECT g.*, c.course_code, c.course_name, c.credit_hours, c.instructor,
           e.semester, e.academic_year
    FROM grades g 
    JOIN enrollments e ON g.enrollment_id = e.id 
    JOIN courses c ON e.course_id = c.id 
    WHERE e.student_id = ?
    ORDER BY e.academic_year DESC, e.semester DESC, c.course_code
");
$stmt->bind_param("i", $student['id']);
$stmt->execute();
$grades = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Calculate cumulative GPA
$totalPoints = 0;
$totalCredits = 0;
$semesterGrades = [];

foreach ($grades as $grade) {
    $key = $grade['academic_year'] . '-' . $grade['semester'];
    if (!isset($semesterGrades[$key])) {
        $semesterGrades[$key] = [
            'academic_year' => $grade['academic_year'],
            'semester' => $grade['semester'],
            'grades' => [],
            'total_points' => 0,
            'total_credits' => 0
        ];
    }
    $semesterGrades[$key]['grades'][] = $grade;
    $semesterGrades[$key]['total_points'] += $grade['grade_point'] * $grade['credit_hours'];
    $semesterGrades[$key]['total_credits'] += $grade['credit_hours'];
    
    $totalPoints += $grade['grade_point'] * $grade['credit_hours'];
    $totalCredits += $grade['credit_hours'];
}

$cumulativeGPA = $totalCredits > 0 ? round($totalPoints / $totalCredits, 2) : 0;

$conn->close();

include __DIR__ . '/../includes/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-chart-bar"></i> My Grades</h1>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #2ecc71;">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($cumulativeGPA, 2); ?></h3>
                <p>Cumulative GPA</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #3498db;">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $totalCredits; ?></h3>
                <p>Total Credit Hours</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #e74c3c;">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo count($grades); ?></h3>
                <p>Completed Courses</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: <?php echo $cumulativeGPA >= 3.5 ? '#2ecc71' : ($cumulativeGPA >= 2.5 ? '#f39c12' : '#e74c3c'); ?>;">
                <i class="fas fa-trophy"></i>
            </div>
            <div class="stat-info">
                <h3><?php 
                    if ($cumulativeGPA >= 3.75) echo 'Great Distinction';
                    elseif ($cumulativeGPA >= 3.5) echo 'Distinction';
                    elseif ($cumulativeGPA >= 3.0) echo 'Very Good';
                    elseif ($cumulativeGPA >= 2.5) echo 'Good';
                    elseif ($cumulativeGPA >= 2.0) echo 'Satisfactory';
                    else echo 'Needs Improvement';
                ?></h3>
                <p>Academic Standing</p>
            </div>
        </div>
    </div>
    
    <?php if (empty($grades)): ?>
        <div class="dashboard-card">
            <div class="card-body">
                <p class="no-data">No grades available yet.</p>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($semesterGrades as $key => $semData): ?>
            <div class="dashboard-card" style="margin-bottom: 20px;">
                <div class="card-header">
                    <h2>
                        <i class="fas fa-calendar-alt"></i> 
                        Semester <?php echo $semData['semester']; ?> - <?php echo htmlspecialchars($semData['academic_year']); ?>
                        <span class="semester-gpa">
                            GPA: <?php echo $semData['total_credits'] > 0 ? number_format($semData['total_points'] / $semData['total_credits'], 2) : '0.00'; ?>
                        </span>
                    </h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Credits</th>
                                    <th>Midterm (30%)</th>
                                    <th>Assignment (20%)</th>
                                    <th>Quiz (10%)</th>
                                    <th>Final (40%)</th>
                                    <th>Total</th>
                                    <th>Grade</th>
                                    <th>Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($semData['grades'] as $grade): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($grade['course_code']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($grade['course_name']); ?></td>
                                        <td><?php echo $grade['credit_hours']; ?></td>
                                        <td><?php echo number_format($grade['midterm'], 1); ?></td>
                                        <td><?php echo number_format($grade['assignment'], 1); ?></td>
                                        <td><?php echo number_format($grade['quiz'], 1); ?></td>
                                        <td><?php echo number_format($grade['final_exam'], 1); ?></td>
                                        <td><strong><?php echo number_format($grade['total'], 1); ?></strong></td>
                                        <td>
                                            <span class="grade-badge grade-<?php echo strtolower(str_replace('+', 'plus', str_replace('-', 'minus', $grade['grade_letter']))); ?>">
                                                <?php echo $grade['grade_letter']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo number_format($grade['grade_point'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
