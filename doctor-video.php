<?php
require_once 'config.php';
requireLogin();

// Get waiting calls for this doctor
$stmt = $pdo->prepare("SELECT * FROM video_calls WHERE doctor_name = ? AND call_status = 'waiting' ORDER BY created_at DESC");
$stmt->execute([$_SESSION['full_name'] ?? $_SESSION['username']]);
$waiting_calls = $stmt->fetchAll();
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
    <title>Doctor Video Console - Hakim Gizaw Hospital</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .calls-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .call-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .call-card:hover {
            transform: translateY(-5px);
        }

        .caller-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .caller-avatar {
            width: 60px;
            height: 60px;
            background: #667eea;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .caller-details h3 {
            margin: 0 0 5px 0;
            color: #333;
        }

        .caller-details p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }

        .call-time {
            color: #999;
            font-size: 12px;
            margin-bottom: 15px;
        }

        .btn-start-call {
            width: 100%;
            padding: 12px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }

        .btn-start-call:hover {
            background: #218838;
        }

        .no-calls {
            text-align: center;
            padding: 60px;
            background: white;
            border-radius: 15px;
            color: #999;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-waiting {
            background: #ffc107;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-video"></i> Doctor Video Consultation Console</h1>
            <p>Welcome, Dr. <?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?></p>
        </div>

        <h2><i class="fas fa-clock"></i> Waiting Calls</h2>
        
        <div class="calls-grid">
            <?php if (empty($waiting_calls)): ?>
                <div class="no-calls">
                    <i class="fas fa-phone-slash" style="font-size: 48px; margin-bottom: 20px;"></i>
                    <h3>No waiting calls</h3>
                    <p>Patients will appear here when they request a video consultation.</p>
                </div>
            <?php else: ?>
                <?php foreach($waiting_calls as $call): ?>
                <div class="call-card">
                    <div class="caller-info">
                        <div class="caller-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="caller-details">
                            <h3><?php echo htmlspecialchars($call['patient_name']); ?></h3>
                            <p>Patient ID: #<?php echo $call['patient_id']; ?></p>
                        </div>
                    </div>
                    <div class="call-time">
                        <i class="fas fa-calendar"></i> Requested: <?php echo date('M d, H:i', strtotime($call['created_at'])); ?>
                        <span class="status-badge status-waiting">Waiting</span>
                    </div>
                    <button class="btn-start-call" onclick="startCall('<?php echo $call['call_id']; ?>')">
                        <i class="fas fa-video"></i> Start Video Call
                    </button>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function startCall(callId) {
            // Update call status
            fetch('update-call-status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ call_id: callId, status: 'active' })
            }).then(() => {
                // Redirect to video call page as doctor
                window.location.href = `video-call.php?call_id=${callId}&doctor_mode=1`;
            });
        }
    </script>
</body>
</html>