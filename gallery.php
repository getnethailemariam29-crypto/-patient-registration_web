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
    <title>Image Gallery</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            aspect-ratio: 1 / 1;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover {
            transform: scale(1.03);
        }

        .gallery-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
        }

        .gallery-item:hover .gallery-image {
            transform: scale(1.1);
        }

        .gallery-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 10px;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }

        .gallery-item:hover .gallery-caption {
            transform: translateY(0);
        }

        @media (max-width: 600px) {
            .gallery {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 10px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <a href="index.html">
            <img src="immage/pbanner.png" alt="Healthcare Center Logo" style="max-height: 100px;">
            <img src="immage/H G LOGO.png" alt="Healthcare Center Partner Logo" style="max-height: 100px;">
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
    
    <div class="gallery">
        <div class="gallery-item">
            <img src="immage/a.jpg" alt="Nature" class="gallery-image">
            <div class="gallery-caption">Professor Asrat Weldeyes</div>
        </div>
        
        <div class="gallery-item">
            <img src="immage/j.jpg" alt="City" class="gallery-image">
            <div class="gallery-caption">Daily Register</div>
        </div>
        
        <div class="gallery-item">
            <img src="immage/11SEW.jfif" alt="Animal" class="gallery-image">
            <div class="gallery-caption">Our Team Member</div>
        </div>
        
        <div class="gallery-item">
            <img src="immage/REGIS.webp" alt="Food" class="gallery-image">
            <div class="gallery-caption">Register Now</div>
        </div>
        
        <div class="gallery-item">
            <img src="immage/s.jpg" alt="Travel" class="gallery-image">
            <div class="gallery-caption">Our Hospital Building</div>
        </div>
        
        <div class="gallery-item">
            <img src="immage/online.html.png" alt="Architecture" class="gallery-image">
            <div class="gallery-caption">Report Format</div>
        </div>

        <div class="gallery-item">
            <img src="immage/app.jfif" alt="City" class="gallery-image">
            <div class="gallery-caption">Schedule Appointment</div>
        </div>
        
        <div class="gallery-item">
            <img src="immage/ab.jpg" alt="Animal" class="gallery-image">
            <div class="gallery-caption">Register by Your Phone</div>
        </div>
        
        <div class="gallery-item">
            <img src="immage/t.jpg" alt="Food" class="gallery-image">
            <div class="gallery-caption">Patient Registration Form</div>
        </div>
        
        <div class="gallery-item">
            <img src="immage/c.jpg" alt="Travel" class="gallery-image">
            <div class="gallery-caption">New Patient Registration Form</div>
        </div>
        
        <div class="gallery-item">
            <img src="immage/e.jpg" alt="Architecture" class="gallery-image">
            <div class="gallery-caption">Patient Reservation Form</div>
        </div>
        
        <div class="gallery-item">
            <img src="immage/l.jpg" alt="City" class="gallery-image">
            <div class="gallery-caption">E-Hospital</div>
        </div>
        
        <div class="gallery-item">
            <img src="immage/1666961056634.jfif" alt="Animal" class="gallery-image">
            <div class="gallery-caption">WELCOME</div>
        </div>
        
        <div class="gallery-item">
            <img src="immage/k.jpg" alt="Food" class="gallery-image">
            <div class="gallery-caption">Patient Registration Form</div>
        </div>
        
        <div class="gallery-item">
            <img src="immage/tele.jpg" alt="Travel" class="gallery-image">
            <div class="gallery-caption">Telemedicine</div>
        </div>
        
        <div class="gallery-item">
            <img src="immage/diog ser.webp" alt="Architecture" class="gallery-image">
            <div class="gallery-caption">Patient Reservation</div>
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