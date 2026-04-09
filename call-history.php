<?php
require_once 'config.php';
requireLogin();

// Get user's call history
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM video_calls WHERE patient_id = ? OR doctor_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id, $user_id]);
$calls = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<meta name="theme-color" content="#0077cc">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<!-- Mobile Responsive Meta Tags -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes, viewport-fit=cover">
<meta name="theme-color" content="#005f73">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Hakim Gizaw Hospital">
<meta name="mobile-web-app-capable" content="yes">
<link rel="apple-touch-icon" href="immage/H G LOGO.png">
<link rel="icon" type="image/png" href="immage/H G LOGO.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Call History - Hakim Gizaw Hospital</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
        .call-history-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
        }
        .status-completed {
            color: #28a745;
        }
        .status-missed {
            color: #dc3545;
        }
        .status-waiting {
            color: #ffc107;
        }
        .btn {
            padding: 5px 15px;
            background: #0077cc;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
        }
        .btn:hover {
            background: #005fa3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-history"></i> Video Call History</h1>
            <p>Your consultation history with doctors</p>
        </div>
        
        <div class="call-history-table">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>With</th>
                        <th>Duration</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($calls as $call): ?>
                    <tr>
                        <td><?php echo date('M d, Y H:i', strtotime($call['created_at'])); ?></td>
                        <td>
                            <?php 
                            if ($call['patient_id'] == $user_id) {
                                echo '<i class="fas fa-user-md"></i> Dr. ' . htmlspecialchars($call['doctor_name']);
                            } else {
                                echo '<i class="fas fa-user"></i> ' . htmlspecialchars($call['patient_name']);
                            }
                            ?>
                        </td>
                        <td>
                            <?php 
                            if ($call['duration']) {
                                $minutes = floor($call['duration'] / 60);
                                $seconds = $call['duration'] % 60;
                                echo "{$minutes}m {$seconds}s";
                            } else {
                                echo '-';
                            }
                            ?>
                        </td>
                        <td class="status-<?php echo $call['call_status']; ?>">
                            <?php echo ucfirst($call['call_status']); ?>
                        </td>
                        <td>
                            <?php if ($call['call_status'] == 'completed'): ?>
                                <a href="#" class="btn">View Details</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>