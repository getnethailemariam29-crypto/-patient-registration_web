<?php
require_once 'config.php';
// No login required
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
    <title>Our Facilities | Hakim Gizaw Hospital</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1a6fc9;
            --primary-dark: #14559e;
            --secondary: #2c3e50;
            --accent: #e74c3c;
            --light: #f8f9fa;
            --dark-gray: #7f8c8d;
            --white: #ffffff;
        }

        body {
            background-color: #f5f9fc;
            color: var(--secondary);
            line-height: 1.7;
        }
        
        .header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            padding: 60px 0 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: '';
            position: absolute;
            bottom: -50px;
            left: 0;
            width: 100%;
            height: 100px;
            background: var(--white);
            transform: skewY(-3deg);
            z-index: 1;
        }
        
        .hospital-name {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }
        
        .hospital-slogan {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 20px;
        }
        
        .page-title {
            font-size: 2.2rem;
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
            z-index: 2;
        }
        
        .page-title::after {
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
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            position: relative;
            z-index: 2;
        }
        
        .intro-text {
            text-align: center;
            max-width: 800px;
            margin: 0 auto 50px;
            color: var(--dark-gray);
            font-size: 1.1rem;
        }
        
        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }
        
        .facility-card {
            background: var(--white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .facility-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }
        
        .card-icon {
            background: linear-gradient(45deg, var(--primary), var(--primary-dark));
            color: var(--white);
            font-size: 2.5rem;
            padding: 30px;
            text-align: center;
        }
        
        .card-content {
            padding: 30px;
        }
        
        .card-title {
            font-size: 1.4rem;
            color: var(--secondary);
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .card-description {
            color: var(--dark-gray);
            margin-bottom: 20px;
        }
        
        .card-highlight {
            display: flex;
            align-items: center;
            color: var(--primary);
            font-weight: 500;
            margin-top: 15px;
        }
        
        .card-highlight i {
            margin-right: 8px;
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
    
    <header class="header">
        <div class="container">
            <h1 class="hospital-name">Hakim Gizaw Hospital</h1>
            <p class="hospital-slogan">Compassionate Care, Advanced Technology</p>
            <h2 class="page-title">Our Digital Facilities</h2>
        </div>
    </header>
    
    <main class="container">
        <p class="intro-text">
            At Hakim Gizaw Hospital, we've integrated cutting-edge digital solutions to make your healthcare experience seamless and efficient. 
            Explore our online patient registration facilities designed to save your time and enhance your care.
        </p>
        
        <div class="facilities-grid">
            <div class="facility-card">
                <div class="card-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-title">24/7 Online Registration</h3>
                    <p class="card-description">
                        Register as a patient anytime, anywhere through our secure portal. Complete your medical history forms online before your visit to minimize waiting time.
                    </p>
                    <div class="card-highlight">
                        <i class="fas fa-check-circle"></i>
                        <span>Average time saved: 25 minutes</span>
                    </div>
                </div>
            </div>
            
            <div class="facility-card">
                <div class="card-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Mobile Appointment Booking</h3>
                    <p class="card-description">
                        Schedule, reschedule or cancel appointments with our specialists directly from your smartphone. Receive instant confirmations and reminders.
                    </p>
                    <div class="card-highlight">
                        <i class="fas fa-check-circle"></i>
                        <span>Real-time doctor availability</span>
                    </div>
                </div>
            </div>
            
            <div class="facility-card">
                <div class="card-icon">
                    <i class="fas fa-file-medical-alt"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Digital Health Records</h3>
                    <p class="card-description">
                        Access your complete medical history, lab results, and treatment plans through your personalized patient dashboard. All your health data in one secure place.
                    </p>
                    <div class="card-highlight">
                        <i class="fas fa-check-circle"></i>
                        <span>HIPAA-compliant security</span>
                    </div>
                </div>
            </div>
            
            <div class="facility-card">
                <div class="card-icon">
                    <i class="fas fa-video"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Virtual Consultations</h3>
                    <p class="card-description">
                        Connect with our healthcare providers through secure video calls for follow-ups, prescriptions, and non-emergency consultations from the comfort of your home.
                    </p>
                    <div class="card-highlight">
                        <i class="fas fa-check-circle"></i>
                        <span>Reduces unnecessary visits by 40%</span>
                    </div>
                </div>
            </div>
            
            <div class="facility-card">
                <div class="card-icon">
                    <i class="fas fa-pills"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-title">E-Prescription Service</h3>
                    <p class="card-description">
                        Receive digital prescriptions sent directly to your preferred pharmacy. Never worry about lost paper prescriptions again with our integrated system.
                    </p>
                    <div class="card-highlight">
                        <i class="fas fa-check-circle"></i>
                        <span>Connected to 50+ local pharmacies</span>
                    </div>
                </div>
            </div>
            
            <div class="facility-card">
                <div class="card-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-title">Personalized Health Alerts</h3>
                    <p class="card-description">
                        Get automated reminders for medication schedules, upcoming appointments, vaccination due dates, and preventive health screenings tailored to your needs.
                    </p>
                    <div class="card-highlight">
                        <i class="fas fa-check-circle"></i>
                        <span>Customizable notification preferences</span>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
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