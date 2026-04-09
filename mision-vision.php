<?php
require_once 'config.php';
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
    <title>Mission & Vision | Hakim Gizaw Hospital</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1a6fc9;
            --primary-light: #e8f2fc;
            --primary-dark: #14559e;
            --secondary: #2c3e50;
            --accent: #e74c3c;
            --light: #f8f9fa;
            --white: #ffffff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: var(--light);
            color: var(--secondary);
            line-height: 1.6;
        }
        
        .mission-vision-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-header h1 {
            font-size: 2.5rem;
            color: var(--secondary);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }
        
        .section-header h1::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--accent);
            border-radius: 2px;
        }
        
        .section-header p {
            color: var(--dark-gray);
            max-width: 700px;
            margin: 0 auto;
            font-size: 1.1rem;
        }
        
        .mv-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }
        
        .mv-card {
            background: var(--white);
            border-radius: 10px;
            padding: 40px 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            text-align: center;
            transition: transform 0.3s;
            border-top: 5px solid var(--primary);
        }
        
        .mv-card:hover {
            transform: translateY(-10px);
        }
        
        .mv-icon {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 20px;
        }
        
        .mv-card h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: var(--secondary);
        }
        
        .mv-card p {
            color: var(--dark-gray);
            margin-bottom: 20px;
        }
        
        .values-list {
            text-align: left;
            margin-top: 30px;
        }
        
        .values-list li {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .values-list i {
            color: var(--primary);
            margin-right: 10px;
        }
        
        @media (max-width: 768px) {
            .mv-cards {
                grid-template-columns: 1fr;
            }
            
            .section-header h1 {
                font-size: 2rem;
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
            <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
            <li><a href="appointment.php"><i class="fas fa-calendar-check"></i> Appointments</a></li>
            <li><a href="service.php"><i class="fas fa-medkit"></i> Services</a></li>
            <li><a href="doctors.php"><i class="fas fa-user-md"></i> Doctors</a></li>
            <li><a href="contact.php"><i class="fas fa-phone-alt"></i> Contact</a></li>
            <li><a href="facilities.php">Facilities</a></li>
            <li><a href="gallery.php">Gallery</a></li>
            
            <?php if(isset($_SESSION['user_id'])): ?>
                <?php if($_SESSION['is_doctor'] ?? false): ?>
                    <li><a href="doctor-video.php"><i class="fas fa-video"></i> Video Console</a></li>
                <?php else: ?>
                    <li><a href="video-call.php"><i class="fas fa-video"></i> Video Call</a></li>
                <?php endif; ?>
                <li><a href="call-history.php"><i class="fas fa-history"></i> History</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            <?php else: ?>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
            <?php endif; ?>
            
            <li><a href="emergency.php" class="btn-emergency">Emergency</a></li>
        </ul>
    </div>
</nav>
    
    <div class="mission-vision-container">
        <div class="section-header">
            <h1>Our Mission & Vision</h1>
            <p>Guiding principles that shape Hakim Gizaw Hospital's commitment to exceptional healthcare</p>
        </div>
        
        <div class="mv-cards">
            <div class="mv-card">
                <div class="mv-icon">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h2>Our Mission</h2>
                <p>To provide compassionate, high-quality healthcare through innovative digital solutions that make medical services accessible to all patients, anytime, anywhere.</p>
                <p>We are committed to leveraging technology to enhance patient experiences while maintaining the highest standards of medical excellence.</p>
            </div>
            
            <div class="mv-card">
                <div class="mv-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <h2>Our Vision</h2>
                <p>To be Ethiopia's leading digital healthcare provider, transforming patient care through seamless online integration and cutting-edge medical technology.</p>
                <p>We envision a future where geographical barriers no longer limit access to quality healthcare services.</p>
            </div>
            
            <div class="mv-card">
                <div class="mv-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h2>Core Values</h2>
                <ul class="values-list">
                    <li><i class="fas fa-check-circle"></i> Patient-Centered Care</li>
                    <li><i class="fas fa-check-circle"></i> Technological Innovation</li>
                    <li><i class="fas fa-check-circle"></i> Medical Excellence</li>
                    <li><i class="fas fa-check-circle"></i> Community Focus</li>
                    <li><i class="fas fa-check-circle"></i> Ethical Practice</li>
                </ul>
            </div>
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