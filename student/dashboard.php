<?php
/**
 * Student Dashboard
 * Debre Berhan University Student Portal
 */

require_once __DIR__ . '/../includes/auth.php';
requireStudent();

$pageTitle = 'Dashboard';
$student = getStudentInfo($_SESSION['user_id']);

$conn = getConnection();

// Get enrolled courses count
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM enrollments WHERE student_id = ? AND status = 'active'");
$stmt->bind_param("i", $student['id']);
$stmt->execute();
$courseCount = $stmt->get_result()->fetch_assoc()['count'];
$stmt->close();

// Get GPA
$stmt = $conn->prepare("
    SELECT g.grade_point, c.credit_hours 
    FROM grades g 
    JOIN enrollments e ON g.enrollment_id = e.id 
    JOIN courses c ON e.course_id = c.id 
    WHERE e.student_id = ? AND e.status = 'completed'
");
$stmt->bind_param("i", $student['id']);
$stmt->execute();
$gradeData = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$gpa = calculateGPA($gradeData);
$stmt->close();

// Get today's schedule
$today = date('l');
$stmt = $conn->prepare("
    SELECT s.*, c.course_name, c.course_code 
    FROM schedules s 
    JOIN courses c ON s.course_id = c.id 
    JOIN enrollments e ON e.course_id = c.id 
    WHERE e.student_id = ? AND e.status = 'active' AND s.day_of_week = ?
    ORDER BY s.start_time
");
$stmt->bind_param("is", $student['id'], $today);
$stmt->execute();
$todaySchedule = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get announcements
$announcements = [];
$result = $conn->query("SELECT * FROM announcements WHERE is_active = 1 AND (target = 'all' OR target = 'students') ORDER BY created_at DESC LIMIT 5");
while ($row = $result->fetch_assoc()) {
    $announcements[] = $row;
}

$conn->close();

include __DIR__ . '/../includes/header.php';
?>

<div class="dashboard">
    <div class="dashboard-welcome">
        <h1>Welcome, <?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?>!</h1>
        <p>Student ID: <?php echo htmlspecialchars($student['student_id']); ?> | 
           Department: <?php echo htmlspecialchars($student['department_name'] ?? 'N/A'); ?> | 
           Year <?php echo $student['year']; ?>, Semester <?php echo $student['semester']; ?></p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #3498db;">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $courseCount; ?></h3>
                <p>Enrolled Courses</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #2ecc71;">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo number_format($gpa, 2); ?></h3>
                <p>Current GPA</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #e74c3c;">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo count($todaySchedule); ?></h3>
                <p>Classes Today</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #f39c12;">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-info">
                <h3>Year <?php echo $student['year']; ?></h3>
                <p>Academic Year</p>
            </div>
        </div>
    </div>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-clock"></i> Today's Schedule (<?php echo $today; ?>)</h2>
            </div>
            <div class="card-body">
                <?php if (empty($todaySchedule)): ?>
                    <p class="no-data">No classes scheduled for today.</p>
                <?php else: ?>
                    <div class="schedule-list">
                        <?php foreach ($todaySchedule as $class): ?>
                            <div class="schedule-item">
                                <div class="schedule-time">
                                    <span><?php echo date('h:i A', strtotime($class['start_time'])); ?></span>
                                    <span><?php echo date('h:i A', strtotime($class['end_time'])); ?></span>
                                </div>
                                <div class="schedule-details">
                                    <h4><?php echo htmlspecialchars($class['course_name']); ?></h4>
                                    <p><i class="fas fa-hashtag"></i> <?php echo htmlspecialchars($class['course_code']); ?></p>
                                    <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($class['room'] . ', ' . $class['building']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-bullhorn"></i> Announcements</h2>
            </div>
            <div class="card-body">
                <?php if (empty($announcements)): ?>
                    <p class="no-data">No announcements at this time.</p>
                <?php else: ?>
                    <div class="announcement-list">
                        <?php foreach ($announcements as $ann): ?>
                            <div class="announcement-item">
                                <h4><?php echo htmlspecialchars($ann['title']); ?></h4>
                                <p><?php echo htmlspecialchars(substr($ann['content'], 0, 150)); ?>...</p>
                                <span class="announcement-date">
                                    <i class="fas fa-calendar"></i> <?php echo date('M d, Y', strtotime($ann['created_at'])); ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
