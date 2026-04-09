<?php
require_once 'config.php';
// No login required for home page
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
    <title>Patient Registration System</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Hero Section */
        .hero {
            background: linear-gradient(rgba(0, 119, 204, 0.8), rgba(0, 119, 204, 0.9)), 
            url('immage/E.HEALTH\ PEOPLE.jpg');
            background-size: cover;
            background-position: center;
            color: var(--white);
            padding: 180px 0 100px;
            text-align: center;
        }

        .hero-title {
            font-size: 3.5rem;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-bottom: 40px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 40px;
        } 
        
        /* Marquee Banner */
        .marquee-banner {
            background-color: rgb(192, 241, 11);
            color: var(--primary-color);
            padding: 8px 0;
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            width: 100%;
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
        }
        
        .marquee-content {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 15s linear infinite;
        }
        
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
        
        /* Main Content */
        .main-content {
            padding: 40px 0;
        }
        
        .content-row {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .content-col {
            flex: 1;
            min-width: 300px;
        }
        .welcome-section h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-size: 2rem;
        }
        
        .welcome-section p {
            margin-bottom: 15px;
            line-height: 1.8;
            text-align: justify;
        }
        .welcome-section strong {
            color: var(--primary-color);
            font-size: 1.2rem;
        }
        
        .image-container {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .image-container img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s ease;
        }
        .image-container:hover img {
            transform: scale(1.05);
        }
       
        /* Services Section */
        .services {
            background-color: rgb(203, 212, 221);
        }
        
        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }
        
        .service-card {
            background: gold;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }
        
        .service-card:hover {
            transform: translateY(-10px);
        }
        
        .service-img {
            height: 200px;
            background-size: cover;
            background-position: center;
        }
        
        .service-content {
            padding: 25px;
        }
        
        .service-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
            color: var(--primary);
        }
         /* Registration CTA */
        .registration-cta {
            background-color: rgb(56, 146, 173);
            padding: 60px 20px;
            text-align: center;
        }
        
        .cta-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .registration-cta h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: var(--secondary);
        }
        
        .registration-cta p {
            color: #ffcc00;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        .video-container {
            width: 100%;
            height: 200px; 
            overflow: hidden; 
        }

        .video-container video {
            width: 100%;
            height: 100%;
            object-fit: cover; 
        }
    </style>
</head>
<body>
    <!-- Marquee Banner -->
    <div class="marquee-banner">
        <div class="marquee-content">
            Welcome to Hakim Gizaw Hospital - Patient Registration System
        </div>
    </div>
    
    <div class="video-container">
        <video autoplay muted playsinline>
            <source src="immage/banner.mp4" type="video/mp4">
        </video>
    </div>
    
    <header>
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
    </header>
    
    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="content-row">
                <div class="content-col welcome-section">
                    <h2>Welcome to Our Hospital</h2>
                    <p><strong>WELCOME TO HAKIM GIZAW HOSPITAL ONLINE PATIENT REGISTRATION SYSTEM</strong></p>
                    <p>At Hakim Gizaw Hospital, we've transformed patient registration into a smooth, stress-free experience. 
                        Our digital system eliminates paperwork and long queues, offering instant access to Ethiopia's finest healthcare professionals
                         with just a few clicks.From emergency services to specialized consultations, our integrated platform connects
                          you with the right care at the right time. Enjoy features like real-time appointment booking, e-prescription services, 
                          and 24/7 access to your medical history - all designed around your needs.</p>
                    <p>Our patient-centered platform is designed with your comfort and privacy in mind, featuring secure encryption and intuitive navigation.
                         Experience shorter wait times, automated reminders, and direct communication with your healthcare providers - all through our easy-to-use digital portal..</p>
                    <p>This technological advancement offers significant advantages for both patients and healthcare facilities.
                         Patients benefit from increased convenience, reduced waiting times, and improved accuracy of their records, as they can 
                         carefully review and input their own information.</p>
                    <a href="register.php" class="btn btn-primary">Register Now</a>
                </div>
                <div class="content-col">
                    <div class="image-container">
                        <img src="immage/b.jpg" alt="Hospital Building" style="height: 500px;">
                        <p style="color:#ffffff; font-size: 30px; background-color: yellowgreen;">እንኳን ወደ ሃኪም ግዛው ሆስፒታል ደህና መጡ!!!</p>
                    </div>
                </div>
            </div>
            
            <div class="content-row">
                <div class="content-col">
                    <div class="image-container">
                        <img src="immage/c.jpg" alt="Doctor with Patient">
                    </div>
                </div>
                <div class="content-col welcome-section">
                    <p>For providers, online registration systems drastically cut down on 
                      administrative burden, minimize paper waste, reduce manual data entry errors, 
                      and enhance overall operational efficiency. Seamless integration with Electronic
                       Health Record (EHR) systems further ensures that patient data is automatically
                        populated, enabling quicker and more organized patient flow and ultimately 
                        contributing to a more satisfying and efficient healthcare experience for
                         everyone involved.</p>
                    <p>Our system is designed with patient convenience and data security in mind,
                      ensuring that your medical information is handled with the utmost care and 
                      confidentiality.</p>
                    <a href="login.php" class="btn btn-primary">Patient Login</a>
                </div>
            </div>
        </div>
    </main>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h2 class="hero-title">Welcome to Hakim Gizaw Hospital Patient Portal</h2>
            <p class="hero-subtitle">Experience seamless healthcare management with our online patient registration system.
                 Book appointments, access medical records, and connect with your doctors - all in one place.</p>
            <div class="hero-buttons">
                <a href="register.php" class="btn btn-primary"><i class="fas fa-user-plus"></i> New Patient Registration</a>
                <a href="login.php" class="btn btn-outline"><i class="fas fa-sign-in-alt"></i> Patient Portal Login</a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section services">
        <div class="container">
            <h1 class="section-title">Our Online Services</h1>
            <p class="section-subtitle"><h3>Discover how Hakim Gizaw Hospital's digital platform makes healthcare more accessible and convenient for you.</h3></p>
            
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-img" style="background-image: url('immage/ab.jpg');"></div>
                    <div class="service-content">
                        <h3 class="service-title">24/7 Online Registration</h3>
                        <p>Register as a patient anytime from anywhere. Complete your forms online before your visit to save time.</p>
                        <a href="register.php" class="btn btn-outline" style="margin-top: 15px;">Learn More</a>
                    </div>
                </div>
                
                <div class="service-card">
                    <div class="service-img" style="background-image: url('immage/app.jfif');"></div>
                    <div class="service-content">
                        <h3 class="service-title">Appointment Scheduling</h3>
                        <p>Book, reschedule or cancel appointments with our specialists with just a few clicks.</p>
                        <a href="appointment.php" class="btn btn-outline" style="margin-top: 15px;">Learn More</a>
                    </div>
                </div>
                
                <div class="service-card">
                    <div class="service-img" style="background-image: url('immage/mdr.jpg');"></div>
                    <div class="service-content">
                        <h3 class="service-title">Digital Health Records</h3>
                        <p>Access your complete medical history, test results and prescriptions securely online.</p>
                        <a href="general-medicine.php" class="btn btn-outline" style="margin-top: 15px;">Learn More</a>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-img" style="background-image: url('immage/vc.jfif');"></div>
                    <div class="service-content">
                        <h3 class="service-title">Virtual Consultations</h3>
                        <p>Connect with our doctors through secure video calls for follow-ups and non-emergency care.</p>
                        <a href="doctors.php" class="btn btn-outline" style="margin-top: 15px;">Learn More</a>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-img" style="background-image: url('immage/ep.jfif');"></div>
                    <div class="service-content">
                        <h3 class="service-title">E-Prescriptions</h3>
                        <p>Receive digital prescriptions sent directly to your preferred pharmacy.</p>
                        <a href="diagnostic-services.php" class="btn btn-outline" style="margin-top: 15px;">Learn More</a>
                    </div>
                </div>
                <div class="service-card">
                    <div class="service-img" style="background-image: url('immage/hr.jfif');"></div>
                    <div class="service-content">
                        <h3 class="service-title">Health Reminders</h3>
                        <p>Get personalized reminders for medications, appointments and preventive care.</p>
                        <a href="doctors.php" class="btn btn-outline" style="margin-top: 15px;">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Registration CTA -->
    <section class="registration-cta">
        <div class="cta-container">
            <h2>Ready to Get Started?</h2>
            <p>Join thousands of patients who are managing their healthcare through Hakim Gizaw Hospital's online portal. Registration takes less than 5 minutes.</p>
            <a href="register.php" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.2rem;"><i class="fas fa-user-plus"></i> Register Now</a>
        </div>
    </section>

    <!-- Footer -->
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