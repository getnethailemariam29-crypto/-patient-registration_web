<?php
require_once 'config.php';

// Fetch all doctors from database
$doctors = $pdo->query("SELECT * FROM doctors ORDER BY name")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<meta name="theme-color" content="#0077cc">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Doctors - Healthcare Center</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .page-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .page-header h1 {
            color: #0077cc;
            margin-bottom: 10px;
        }
        .doctor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }
        .doctor-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }
        .doctor-card:hover {
            transform: translateY(-5px);
        }
        .doctor-image {
            height: 280px;
            background-color: #eee;
            background-size: cover;
            background-position: center;
        }
        .doctor-info {
            padding: 20px;
        }
        .doctor-name {
            font-size: 1.3em;
            font-weight: bold;
            color: #0077cc;
            margin-bottom: 5px;
        }
        .doctor-specialty {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .doctor-bio {
            color: #555;
            margin-bottom: 15px;
            font-size: 0.9em;
            line-height: 1.4;
        }
        .doctor-meta {
            display: flex;
            justify-content: space-between;
            color: #666;
            font-size: 0.85em;
            margin-bottom: 15px;
        }
        .doctor-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        .btn-appointment {
            flex: 1;
            display: inline-block;
            padding: 10px;
            background-color: #0077cc;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            text-align: center;
            font-size: 14px;
            transition: background 0.3s;
        }
        .btn-appointment:hover {
            background-color: #005599;
        }
        .btn-video {
            flex: 1;
            display: inline-block;
            padding: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            text-align: center;
            font-size: 14px;
            transition: transform 0.3s;
        }
        .btn-video:hover {
            transform: scale(1.02);
        }
        .btn-video i, .btn-appointment i {
            margin-right: 5px;
        }
        @media (max-width: 768px) {
            .doctor-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="logo">
        <a href="index.html">
            <img src="immage/pbanner.png" height="40px" alt="Healthcare Center Logo" width="300">
            <img src="immage/H G LOGO.png" hspace="50px" height="40px" alt="Healthcare Center Logo" width="300">
        </a>
    </div>
    
    <nav>
        <div class="nav-container">
            <ul class="nav-links">
                <li><a href="home.php">index</a></li>
                <li><a href="register.php">New Patient Registration</a></li>
                <li class="dropdown">
                    <a href="#"><i class="fas fa-info-circle"></i> About Us</a>
                    <ul class="dropdown-menu">
                        <li><a href="doctors.php">Doctors</a></li>
                        <li><a href="mision-vision.php">Mission & Vision</a></li>
                        <li><a href="facilities.php">Facilities</a></li>
                        <li><a href="gallery.php">Gallery</a></li>
                        <li><a href="service.php">Services</a></li>
                        <li><a href="appointment.php">Appointment</a></li>
                        <li><a href="general-medicine.php">General Medicine</a></li>
                    </ul>
                </li>
                <li><a href="contact.php">Contact</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if($_SESSION['is_doctor'] ?? false): ?>
                        <li><a href="doctor-video.php"><i class="fas fa-video"></i> Video Console</a></li>
                    <?php endif; ?>
                    <li><a href="call-history.php"><i class="fas fa-history"></i> Call History</a></li>
                    <li><a href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
                <li><a href="emergency.php" class="btn-emergency">Emergency</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <div class="page-header">
            <h1>Our Doctors</h1>
            <p>Meet our team of experienced healthcare professionals</p>
        </div>
        
        <div class="doctor-grid">
            <?php foreach($doctors as $doctor): ?>
            <div class="doctor-card">
                <div class="doctor-image" style="background-image: url('<?php echo htmlspecialchars($doctor['image']); ?>');"></div>
                <div class="doctor-info">
                    <div class="doctor-name"><?php echo htmlspecialchars($doctor['name']); ?></div>
                    <div class="doctor-specialty"><?php echo htmlspecialchars($doctor['specialty']); ?></div>
                    <div class="doctor-meta">
                        <span><i class="fas fa-star"></i> <?php echo $doctor['rating']; ?> (<?php echo rand(50, 200); ?> reviews)</span>
                        <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($doctor['location']); ?></span>
                    </div>
                    <div class="doctor-bio"><?php echo htmlspecialchars($doctor['bio']); ?></div>
                    <div class="doctor-actions">
                        <a href="appointment.php?doctor_id=<?php echo $doctor['id']; ?>" class="btn-appointment">
                            <i class="fas fa-calendar-check"></i> Book Appointment
                        </a>
                        <?php if(isset($_SESSION['user_id']) && !($_SESSION['is_doctor'] ?? false)): ?>
                        <a href="video-call.php?doctor_id=<?php echo $doctor['id']; ?>&doctor_name=<?php echo urlencode($doctor['name']); ?>" class="btn-video">
                            <i class="fas fa-video"></i> Video Consult
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <footer>
        <div class="container">
            <div class="footer-container">
                <div class="footer-col">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="home.php">Home</a></li>
                        <li><a href="service.php">Services</a></li>
                        <li><a href="doctors.php">Our Doctors</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Patient Services</h3>
                    <ul>
                        <li><a href="register.php">New Registration</a></li>
                        <li><a href="appointment.php">Book Appointment</a></li>
                        <li><a href="login.php">Patient Login</a></li>
                        <li><a href="contact.php">Contact Us</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h3>Contact Information</h3>
                    <p><i class="fas fa-map-marker-alt"></i> Kebele 04, Debre Birhan City, Ethiopia</p>
                    <p><i class="fas fa-phone"></i> (+251) 116-170-679</p>
                    <p><i class="fas fa-envelope"></i> info@hakimgizawhospital.com</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Hakim Gizaw Hospital. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>