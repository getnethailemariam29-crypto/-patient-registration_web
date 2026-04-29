<?php
/**
 * Student Profile Page
 * Debre Berhan University Student Portal
 */

require_once __DIR__ . '/../includes/auth.php';
requireStudent();

$pageTitle = 'My Profile';
$student = getStudentInfo($_SESSION['user_id']);
$message = '';
$error = '';

$conn = getConnection();

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = sanitize($_POST['phone'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    
    // Handle profile image upload
    $profileImage = $student['profile_image'];
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['profile_image']['type'];
        
        if (in_array($fileType, $allowedTypes)) {
            $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
            $newFileName = 'student_' . $student['id'] . '_' . time() . '.' . $ext;
            $uploadDir = __DIR__ . '/../assets/images/profiles/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadDir . $newFileName)) {
                $profileImage = $newFileName;
            }
        } else {
            $error = 'Invalid image format. Use JPG, PNG, or GIF.';
        }
    }
    
    if (empty($error)) {
        $stmt = $conn->prepare("UPDATE students SET phone = ?, address = ?, email = ?, profile_image = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $phone, $address, $email, $profileImage, $student['id']);
        
        if ($stmt->execute()) {
            $message = 'Profile updated successfully!';
            $student = getStudentInfo($_SESSION['user_id']);
        } else {
            $error = 'Failed to update profile.';
        }
        $stmt->close();
    }
}

// Handle password change
if (isset($_POST['change_password'])) {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmNewPassword = $_POST['confirm_new_password'] ?? '';
    
    if (empty($currentPassword) || empty($newPassword) || empty($confirmNewPassword)) {
        $error = 'All password fields are required.';
    } elseif ($newPassword !== $confirmNewPassword) {
        $error = 'New passwords do not match.';
    } elseif (strlen($newPassword) < 6) {
        $error = 'New password must be at least 6 characters.';
    } else {
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if (password_verify($currentPassword, $user['password'])) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashedPassword, $_SESSION['user_id']);
            $stmt->execute();
            $stmt->close();
            $message = 'Password changed successfully!';
        } else {
            $error = 'Current password is incorrect.';
        }
    }
}

$conn->close();

include __DIR__ . '/../includes/header.php';
?>

<div class="page-container">
    <div class="page-header">
        <h1><i class="fas fa-user"></i> My Profile</h1>
    </div>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $message; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="profile-grid">
        <div class="profile-sidebar">
            <div class="profile-card">
                <div class="profile-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h2><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h2>
                <p class="student-id"><?php echo htmlspecialchars($student['student_id']); ?></p>
                <p class="department"><?php echo htmlspecialchars($student['department_name'] ?? 'N/A'); ?></p>
                <div class="profile-details-list">
                    <div class="detail-item">
                        <span class="label">Gender:</span>
                        <span class="value"><?php echo htmlspecialchars($student['gender']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Year:</span>
                        <span class="value"><?php echo $student['year']; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Semester:</span>
                        <span class="value"><?php echo $student['semester']; ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Email:</span>
                        <span class="value"><?php echo htmlspecialchars($student['email'] ?? 'N/A'); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Phone:</span>
                        <span class="value"><?php echo htmlspecialchars($student['phone'] ?? 'N/A'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="profile-main">
            <div class="dashboard-card">
                <div class="card-header">
                    <h2><i class="fas fa-edit"></i> Edit Profile</h2>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>" placeholder="Enter phone number">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($student['email'] ?? ''); ?>" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" rows="3" placeholder="Enter your address"><?php echo htmlspecialchars($student['address'] ?? ''); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Profile Image</label>
                            <input type="file" name="profile_image" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Profile</button>
                    </form>
                </div>
            </div>
            
            <div class="dashboard-card" style="margin-top: 20px;">
                <div class="card-header">
                    <h2><i class="fas fa-key"></i> Change Password</h2>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <input type="hidden" name="change_password" value="1">
                        <div class="form-group">
                            <label>Current Password</label>
                            <input type="password" name="current_password" required placeholder="Enter current password">
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <input type="password" name="new_password" required minlength="6" placeholder="Enter new password">
                        </div>
                        <div class="form-group">
                            <label>Confirm New Password</label>
                            <input type="password" name="confirm_new_password" required placeholder="Confirm new password">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-lock"></i> Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
