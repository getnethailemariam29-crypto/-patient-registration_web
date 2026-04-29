<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>DBU Student Portal</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
    <nav class="navbar">
        <div class="nav-brand">
            <i class="fas fa-university"></i>
            <span>DBU Student Portal</span>
        </div>
        <button class="nav-toggle" id="navToggle">
            <i class="fas fa-bars"></i>
        </button>
        <ul class="nav-menu" id="navMenu">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li><a href="/admin/index.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="/admin/manage_students.php" class="nav-link"><i class="fas fa-users"></i> Students</a></li>
                <li><a href="/admin/manage_courses.php" class="nav-link"><i class="fas fa-book"></i> Courses</a></li>
                <li><a href="/admin/manage_grades.php" class="nav-link"><i class="fas fa-chart-bar"></i> Grades</a></li>
                <li><a href="/admin/announcements.php" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a></li>
            <?php else: ?>
                <li><a href="/student/dashboard.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="/student/profile.php" class="nav-link"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="/student/courses.php" class="nav-link"><i class="fas fa-book"></i> Courses</a></li>
                <li><a href="/student/grades.php" class="nav-link"><i class="fas fa-chart-bar"></i> Grades</a></li>
                <li><a href="/student/schedule.php" class="nav-link"><i class="fas fa-calendar-alt"></i> Schedule</a></li>
            <?php endif; ?>
            <li class="nav-user">
                <span class="user-greeting">Hi, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="/logout.php" class="nav-link logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
    <main class="main-content">
