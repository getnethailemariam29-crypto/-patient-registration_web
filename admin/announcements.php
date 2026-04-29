<?php
/**
 * Manage Announcements - Admin Panel
 * Debre Berhan University Student Portal
 */

require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Manage Announcements';
$conn = getConnection();

$message = '';
$error = '';

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $annId = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $annId);
    $stmt->execute();
    $stmt->close();
    $message = 'Announcement deleted successfully.';
}

// Handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_announcement'])) {
    $title = sanitize($_POST['title']);
    $content = sanitize($_POST['content']);
    $target = sanitize($_POST['target']);
    
    $stmt = $conn->prepare("INSERT INTO announcements (title, content, author_id, target) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $title, $content, $_SESSION['user_id'], $target);
    
    if ($stmt->execute()) {
        $message = 'Announcement posted successfully!';
    } else {
        $error = 'Failed to post announcement.';
    }
    $stmt->close();
}

// Get announcements
$announcements = [];
$result = $conn->query("SELECT a.*, u.username as author FROM announcements a LEFT JOIN users u ON a.author_id = u.id ORDER BY a.created_at DESC");
while ($row = $result->fetch_assoc()) {
    $announcements[] = $row;
}

$conn->close();

include __DIR__ . '/../includes/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-bullhorn"></i> Manage Announcements</h1>
        <button class="btn btn-primary" onclick="toggleModal('addAnnouncementModal')">
            <i class="fas fa-plus"></i> New Announcement
        </button>
    </div>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="dashboard-card">
        <div class="card-body">
            <?php if (empty($announcements)): ?>
                <p class="no-data">No announcements yet.</p>
            <?php else: ?>
                <?php foreach ($announcements as $ann): ?>
                    <div class="announcement-item-full">
                        <div class="announcement-header">
                            <h3><?php echo htmlspecialchars($ann['title']); ?></h3>
                            <div class="announcement-actions">
                                <span class="badge badge-<?php echo $ann['target'] === 'all' ? 'info' : ($ann['target'] === 'students' ? 'success' : 'warning'); ?>">
                                    <?php echo ucfirst($ann['target']); ?>
                                </span>
                                <a href="?delete=<?php echo $ann['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this announcement?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        <p class="announcement-content"><?php echo nl2br(htmlspecialchars($ann['content'])); ?></p>
                        <div class="announcement-meta">
                            <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($ann['author'] ?? 'System'); ?></span>
                            <span><i class="fas fa-calendar"></i> <?php echo date('M d, Y h:i A', strtotime($ann['created_at'])); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Announcement Modal -->
<div class="modal" id="addAnnouncementModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-bullhorn"></i> New Announcement</h2>
            <button class="modal-close" onclick="toggleModal('addAnnouncementModal')">&times;</button>
        </div>
        <form method="POST">
            <input type="hidden" name="add_announcement" value="1">
            <div class="modal-body">
                <div class="form-group">
                    <label>Title *</label>
                    <input type="text" name="title" required placeholder="Announcement title">
                </div>
                <div class="form-group">
                    <label>Content *</label>
                    <textarea name="content" rows="5" required placeholder="Write your announcement here..."></textarea>
                </div>
                <div class="form-group">
                    <label>Target Audience</label>
                    <select name="target">
                        <option value="all">All</option>
                        <option value="students">Students Only</option>
                        <option value="admins">Admins Only</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="toggleModal('addAnnouncementModal')">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Post Announcement</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
