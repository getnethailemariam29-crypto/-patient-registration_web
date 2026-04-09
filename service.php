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
    <title>Our Services - Healthcare Center</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .page {
            background: linear-gradient(rgba(0, 119, 204, 0.8), rgba(0, 119, 204, 0.8)), url('immage/medical-bg.jpg');
            background-position: center;
            color: yellow;
            text-align: center;
            padding: 80px 20px;
            margin-bottom: 40px;
            background-image: url('immage/com\ famil.jpg');
            background-size: 100%;
            background-repeat: no-repeat;
            background-attachment: scroll;
        }
        .page h1 {
            font-size: 2.5em;
            margin-bottom: 15px;
        }
        
        .page p {
            font-size: 1.2em;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .services-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .services-intro {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .services-intro h2 {
            color: #0077cc;
            font-size: 2em;
            margin-bottom: 15px;
        }
        
        .services-intro p {
            color: #666;
            font-size: 1.1em;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }
        
        .service-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        
        .service-image {
            height: 200px;
            overflow: hidden;
        }
        
        .service-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .service-card:hover .service-image img {
            transform: scale(1.05);
        }
        
        .service-content {
            padding: 25px;
        }
        
        .service-content h3 {
            color: #0077cc;
            margin-bottom: 15px;
            font-size: 1.5em;
        }
        
        .service-content p {
            color: #666;
            margin-bottom: 20px;
        }
        
        .service-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        
        .btn-learn-more {
            display: inline-block;
            padding: 8px 20px;
            background: #0077cc;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
        }
        
        .btn-learn-more:hover {
            background: #005599;
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
    
    <div class="page">
        <h1>Our Healthcare Services</h1>
        <p>Comprehensive medical services designed to meet all your healthcare needs with compassion and excellence.</p>
    </div>
    
    <div class="services-container">
        <div class="services-intro">
            <h2>Our Healthcare Services</h2>
            <p>Comprehensive medical services accessible through Hakim Gizaw Hospital's online platform.</p>
        </div>
        
        <div class="services-grid">
            <div class="service-card" id="general-medicine">
                <div class="service-image">
                    <img src="immage/gene med.webp" alt="General Medicine">
                </div>
                <div class="service-content">
                    <h3>General Medicine</h3>
                    <p>Comprehensive primary care services for patients of all ages, including preventive care, chronic disease management, and acute illness treatment.</p>
                    <div class="service-meta">
                        <a href="general-medicine.php" class="btn-learn-more">Learn More</a>
                    </div>
                </div>
            </div>
            
            <div class="service-card" id="specialty-care">
                <div class="service-image">
                    <img src="immage/spcare.webp" alt="Specialty Care">
                </div>
                <div class="service-content">
                    <h3>Specialty Care</h3>
                    <p>Expert care from our specialists in cardiology, dermatology, endocrinology, gastroenterology, and more for complex health conditions.</p>
                    <div class="service-meta">
                        <a href="doctors.php" class="btn-learn-more">View Doctors</a>
                    </div>
                </div>
            </div>
            
            <div class="service-card" id="diagnostic-services">
                <div class="service-image">
                    <img src="immage/diog ser.webp" alt="Diagnostic Services">
                </div>
                <div class="service-content">
                    <h3>Diagnostic Services</h3>
                    <p>Advanced diagnostic testing including laboratory services, imaging (X-ray, ultrasound, MRI), and cardiac testing for accurate diagnosis.</p>
                    <div class="service-meta">
                        <a href="appointment.php" class="btn-learn-more">Book Appointment</a>
                    </div>
                </div>
            </div>
            <div class="service-card">
                <div class="service-image">
                    <img src="immage/c.jpg" alt="Online Registration">
                </div>
                <div class="service-content">
                    <h3>Online Registration</h3>
                    <p>Register as a new patient completely online. Complete your forms digitally before your first visit to save time.</p>
                    <div class="service-meta">
                        <a href="register.php" class="btn-learn-more">Register Now</a>
                    </div>
                </div>
            </div>
            <div class="service-card">
                <div class="service-image">
                    <img src="immage/app.jfif" alt="Book Appointment">
                </div>
                <div class="service-content">
                    <h3>Book Appointment</h3>
                    <p>Schedule, reschedule or cancel appointments with our specialists through our easy-to-use portal.</p>
                    <div class="service-meta">
                        <a href="appointment.php" class="btn-learn-more">Book Appointment</a>
                    </div>
                </div>
            </div>
            <div class="service-card">
                <div class="service-image">
                    <img src="immage/tele.jpg" alt="Telemedicine">
                </div>
                <div class="service-content">
                    <h3>Telemedicine</h3>
                    <p>Consult with our doctors remotely through secure video calls for follow-ups and non-emergency care.</p>
                    <div class="service-meta">
                        <a href="general-medicine.php" class="btn-learn-more">Start Consultation</a>
                    </div>
                </div>
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