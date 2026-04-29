<?php
/**
 * Student Schedule Page
 * Debre Berhan University Student Portal
 */

require_once __DIR__ . '/../includes/auth.php';
requireStudent();

$pageTitle = 'My Schedule';
$student = getStudentInfo($_SESSION['user_id']);

$conn = getConnection();

// Get weekly schedule
$stmt = $conn->prepare("
    SELECT s.*, c.course_name, c.course_code, c.instructor 
    FROM schedules s 
    JOIN courses c ON s.course_id = c.id 
    JOIN enrollments e ON e.course_id = c.id 
    WHERE e.student_id = ? AND e.status = 'active'
    ORDER BY FIELD(s.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'), s.start_time
");
$stmt->bind_param("i", $student['id']);
$stmt->execute();
$allSchedule = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Organize by day
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$scheduleByDay = [];
foreach ($days as $day) {
    $scheduleByDay[$day] = [];
}
foreach ($allSchedule as $item) {
    $scheduleByDay[$item['day_of_week']][] = $item;
}

$today = date('l');

$conn->close();

include __DIR__ . '/../includes/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-calendar-alt"></i> Weekly Schedule</h1>
        <p>Current Day: <strong><?php echo $today; ?></strong></p>
    </div>
    
    <div class="schedule-container">
        <?php foreach ($days as $day): ?>
            <div class="schedule-day-card <?php echo $day === $today ? 'today' : ''; ?>">
                <div class="day-header <?php echo $day === $today ? 'today-header' : ''; ?>">
                    <h3>
                        <i class="fas fa-calendar-day"></i> <?php echo $day; ?>
                        <?php if ($day === $today): ?>
                            <span class="today-badge">Today</span>
                        <?php endif; ?>
                    </h3>
                </div>
                <div class="day-body">
                    <?php if (empty($scheduleByDay[$day])): ?>
                        <p class="no-classes">No classes</p>
                    <?php else: ?>
                        <?php foreach ($scheduleByDay[$day] as $class): ?>
                            <div class="class-item">
                                <div class="class-time">
                                    <i class="fas fa-clock"></i>
                                    <?php echo date('h:i A', strtotime($class['start_time'])); ?> - 
                                    <?php echo date('h:i A', strtotime($class['end_time'])); ?>
                                </div>
                                <div class="class-info">
                                    <h4><?php echo htmlspecialchars($class['course_code']); ?> - <?php echo htmlspecialchars($class['course_name']); ?></h4>
                                    <p><i class="fas fa-chalkboard-teacher"></i> <?php echo htmlspecialchars($class['instructor'] ?? 'TBA'); ?></p>
                                    <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($class['room'] . ', ' . $class['building']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
