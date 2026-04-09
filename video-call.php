<?php
require_once 'config.php';
requireLogin();

// Get doctor ID from URL or POST
$doctor_id = $_GET['doctor_id'] ?? $_POST['doctor_id'] ?? 0;
$doctor_name = '';

if ($doctor_id) {
    $stmt = $pdo->prepare("SELECT name FROM doctors WHERE id = ?");
    $stmt->execute([$doctor_id]);
    $doctor = $stmt->fetch();
    $doctor_name = $doctor['name'] ?? 'Doctor';
}

// Generate unique call ID
$call_id = uniqid('call_') . '_' . $_SESSION['user_id'] . '_' . time();

// Save call record to database
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['start_call'])) {
    $stmt = $pdo->prepare("INSERT INTO video_calls (call_id, patient_id, doctor_id, doctor_name, patient_name, scheduled_time, call_status) 
                           VALUES (?, ?, ?, ?, ?, NOW(), 'waiting')");
    $stmt->execute([
        $call_id,
        $_SESSION['user_id'],
        $doctor_id,
        $doctor_name,
        $_SESSION['full_name'] ?? $_SESSION['username']
    ]);
}
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
    <title>Video Consultation - Hakim Gizaw Hospital</title>
    <script src="https://unpkg.com/peerjs@1.4.7/dist/peerjs.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            overflow: hidden;
        }

        .video-container {
            display: flex;
            height: 100vh;
            position: relative;
        }

        .remote-video-container {
            flex: 3;
            background: #000;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #remoteVideo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .local-video-container {
            position: absolute;
            bottom: 20px;
            right: 20px;
            width: 250px;
            height: 180px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            border: 2px solid white;
            cursor: pointer;
            transition: all 0.3s;
            z-index: 10;
        }

        .local-video-container:hover {
            transform: scale(1.05);
        }

        #localVideo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .controls {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 20px;
            background: rgba(0,0,0,0.7);
            padding: 15px 25px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            z-index: 20;
        }

        .control-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            cursor: pointer;
            font-size: 20px;
            transition: all 0.3s;
            background: #0077cc;
            color: white;
        }

        .control-btn:hover {
            transform: scale(1.1);
        }

        .control-btn.end-call {
            background: #dc3545;
        }

        .control-btn.end-call:hover {
            background: #c82333;
        }

        .call-info {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(0,0,0,0.6);
            padding: 10px 20px;
            border-radius: 10px;
            color: white;
            z-index: 20;
        }

        .call-timer {
            font-size: 18px;
            font-weight: bold;
        }

        .call-status {
            font-size: 14px;
            opacity: 0.9;
        }

        .error-message {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #dc3545;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            display: none;
            z-index: 100;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Incoming call modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
            animation: bounceIn 0.5s ease;
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .modal-content i {
            font-size: 60px;
            color: #0077cc;
            margin-bottom: 20px;
        }

        .modal-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 25px;
        }

        .modal-btn {
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .accept-btn {
            background: #28a745;
            color: white;
        }

        .reject-btn {
            background: #dc3545;
            color: white;
        }

        @media (max-width: 768px) {
            .local-video-container {
                width: 120px;
                height: 90px;
                bottom: 80px;
            }
            .control-btn {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="video-container">
        <div class="remote-video-container">
            <video id="remoteVideo" autoplay playsinline></video>
        </div>
        <div class="local-video-container">
            <video id="localVideo" autoplay muted playsinline></video>
        </div>
        <div class="call-info">
            <div class="call-timer">
                <i class="fas fa-clock"></i> <span id="callTimer">00:00</span>
            </div>
            <div class="call-status" id="callStatus">
                Connecting...
            </div>
        </div>
        <div class="controls">
            <button class="control-btn" id="muteBtn" title="Mute Microphone">
                <i class="fas fa-microphone"></i>
            </button>
            <button class="control-btn" id="videoBtn" title="Toggle Video">
                <i class="fas fa-video"></i>
            </button>
            <button class="control-btn end-call" id="endCallBtn" title="End Call">
                <i class="fas fa-phone-slash"></i>
            </button>
        </div>
    </div>

    <div id="incomingCallModal" class="modal">
        <div class="modal-content">
            <i class="fas fa-phone-ring"></i>
            <h2>Incoming Call</h2>
            <p><strong id="callerName">Doctor</strong> is calling you...</p>
            <div class="modal-buttons">
                <button class="modal-btn accept-btn" onclick="videoCall.answerCall(videoCall.currentCall)">
                    <i class="fas fa-check"></i> Accept
                </button>
                <button class="modal-btn reject-btn" onclick="videoCall.endCall()">
                    <i class="fas fa-times"></i> Reject
                </button>
            </div>
        </div>
    </div>

    <div id="errorMessage" class="error-message"></div>

    <input type="hidden" id="doctorId" value="<?php echo $doctor_id; ?>">
    <input type="hidden" id="callId" value="<?php echo $call_id; ?>">

    <script src="video-call.js"></script>
    <script>
        // Initialize video call
        const callId = document.getElementById('callId').value;
        const isInitiator = <?php echo $doctor_id ? 'true' : 'false'; ?>;
        
        document.addEventListener('DOMContentLoaded', () => {
            window.videoCall.init(callId, isInitiator);
        });
    </script>
</body>
</html>