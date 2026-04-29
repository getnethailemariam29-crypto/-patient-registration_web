<?php
/**
 * Admin Dashboard
 * Debre Berhan University Student Portal
 */

require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Admin Dashboard';
$conn = getConnection();

// Get statistics
$stats = [];

$result = $conn->query("SELECT COUNT(*) as count FROM students");
$stats['students'] = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM courses");
$stats['courses'] = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM departments");
$stats['departments'] = $result->fetch_assoc()['count'];

$result = $conn->query("SELECT COUNT(*) as count FROM enrollments WHERE status = 'active'");
$stats['enrollments'] = $result->fetch_assoc()['count'];

// Recent registrations
$recentStudents = [];
$result = $conn->query("
    SELECT s.*, d.name as department_name 
    FROM students s 
    LEFT JOIN departments d ON s.department_id = d.id 
    ORDER BY s.created_at DESC 
    LIMIT 5
");
while ($row = $result->fetch_assoc()) {
    $recentStudents[] = $row;
}

// Department stats
$deptStats = [];
$result = $conn->query("
    SELECT d.name, d.code, COUNT(s.id) as student_count 
    FROM departments d 
    LEFT JOIN students s ON d.id = s.department_id 
    GROUP BY d.id 
    ORDER BY student_count DESC
");
while ($row = $result->fetch_assoc()) {
    $deptStats[] = $row;
}

$conn->close();

include __DIR__ . '/../includes/header.php';
?>

<div class="dashboard">
    <div class="dashboard-welcome">
        <h1><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h1>
        <p>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>! Here's an overview of the system.</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: #3498db;">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats['students']; ?></h3>
                <p>Total Students</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #2ecc71;">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats['courses']; ?></h3>
                <p>Total Courses</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #e74c3c;">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats['departments']; ?></h3>
                <p>Departments</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #f39c12;">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-info">
                <h3><?php echo $stats['enrollments']; ?></h3>
                <p>Active Enrollments</p>
            </div>
        </div>
    </div>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-user-plus"></i> Recent Registrations</h2>
                <a href="/admin/manage_students.php" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <?php if (empty($recentStudents)): ?>
                    <p class="no-data">No students registered yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Year</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentStudents as $s): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($s['student_id']); ?></td>
                                        <td><?php echo htmlspecialchars($s['first_name'] . ' ' . $s['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($s['department_name'] ?? 'N/A'); ?></td>
                                        <td><?php echo $s['year']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($s['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="dashboard-card">
            <div class="card-header">
                <h2><i class="fas fa-chart-pie"></i> Students per Department</h2>
            </div>
            <div class="card-body">
                <?php foreach ($deptStats as $dept): ?>
                    <div class="dept-stat-item">
                        <div class="dept-info">
                            <span class="dept-code"><?php echo htmlspecialchars($dept['code']); ?></span>
                            <span class="dept-name"><?php echo htmlspecialchars($dept['name']); ?></span>
                        </div>
                        <div class="dept-bar-container">
                            <div class="dept-bar" style="width: <?php echo $stats['students'] > 0 ? ($dept['student_count'] / max($stats['students'], 1) * 100) : 0; ?>%"></div>
                            <span class="dept-count"><?php echo $dept['student_count']; ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
