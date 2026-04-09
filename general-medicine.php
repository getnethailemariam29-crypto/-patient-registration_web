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
    <title>General Medicine - Healthcare Center</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
            background: #f5f9fc;
        }

        .service-header {
            background: linear-gradient(rgba(0, 119, 204, 0.8), rgba(0, 119, 204, 0.9)), url('images/general-medicine-bg.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            padding: 80px 20px;
            margin-bottom: 40px;
        }

        .service-header h1 {
            font-size: 36px;
            margin-bottom: 15px;
        }

        .service-header p {
            font-size: 18px;
            max-width: 800px;
            margin: 0 auto;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .service-intro {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            margin-bottom: 40px;
            line-height: 1.8;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .service-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .service-card:hover {
            transform: translateY(-10px);
        }

        .service-card-img {
            height: 200px;
            background-size: cover;
            background-position: center;
        }

        .service-card-content {
            padding: 25px;
        }

        .service-card h3 {
            color: #0077cc;
            margin-top: 0;
            font-size: 22px;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #0077cc;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 15px;
        }

        .btn:hover {
            background: #005599;
        }

        .doctors-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            margin-bottom: 40px;
        }

        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .doctor-card {
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            background: #f9f9f9;
            transition: all 0.3s;
        }

        .doctor-card:hover {
            background: #e6f2fa;
        }

        .doctor-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 15px;
            border: 3px solid #0077cc;
        }

        .doctor-card h4 {
            margin: 10px 0 5px;
            color: #0077cc;
        }

        .doctor-specialty {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="logo">
        <img src="immage/pbanner.png" height="100px" alt="Healthcare Center Logo" width="500">
        <img src="immage/H G LOGO.png" hspace="60px" height="100px" alt="Healthcare Center Logo" width="300">
    </div>
    
   <nav>
    <div class="nav-container">
        <ul class="nav-links">
            <li><a href="home.php"><i class="fas fa-home"></i> index</a></li>
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
    
    <div class="service-header">
        <h1><i class="fas fa-heartbeat"></i> General Medicine</h1>
        <p>Comprehensive primary care for patients of all ages, focusing on prevention, diagnosis, and treatment of common illnesses</p>
    </div>

    <div class="container">
        <div class="service-intro">
            <h2>Your First Line of Defense for Health</h2>
            <p>Our General Medicine department provides comprehensive primary care services for patients of all ages. Our board-certified physicians are dedicated to building long-term relationships with patients and providing personalized care for your overall health and well-being. We focus on preventive care, health maintenance, and treatment of acute and chronic illnesses.</p>
            <p>Whether you need an annual physical, treatment for an illness, or management of chronic conditions, our general medicine team is here to provide compassionate, high-quality care tailored to your individual needs.</p>
            <a href="appointment.php" class="btn">Book an Appointment</a>
        </div>

        <h2 style="text-align: center; margin-bottom: 30px; color: #0077cc;">Our General Medicine Services</h2>
        
        <div class="service-grid">
            <div class="service-card">
                <div class="service-card-img" style="background-image: url('images/annual-checkup.jpg');"></div>
                <div class="service-card-content">
                    <h3>Preventive Care & Annual Physicals</h3>
                    <p>Comprehensive health evaluations, screenings, and immunizations to help prevent illness and detect health issues early.</p>
                    <ul>
                        <li>Annual physical exams</li>
                        <li>Health risk assessments</li>
                        <li>Immunizations and vaccinations</li>
                        <li>Cancer screenings</li>
                    </ul>
                    <a href="appointment.php" class="btn">Schedule Physical</a>
                </div>
            </div>
            
            <div class="service-card">
                <div class="service-card-img" style="background-image: url('images/chronic-care.jpg');"></div>
                <div class="service-card-content">
                    <h3>Chronic Disease Management</h3>
                    <p>Personalized care plans for managing ongoing health conditions to improve quality of life and prevent complications.</p>
                    <ul>
                        <li>Diabetes management</li>
                        <li>Hypertension (high blood pressure)</li>
                        <li>High cholesterol</li>
                        <li>Thyroid disorders</li>
                        <li>Asthma and COPD</li>
                    </ul>
                    <a href="appointment.php" class="btn">Learn More</a>
                </div>
            </div>
            
            <div class="service-card">
                <div class="service-card-img" style="background-image: url('images/acute-care.jpg');"></div>
                <div class="service-card-content">
                    <h3>Acute Illness Treatment</h3>
                    <p>Prompt evaluation and treatment for sudden illnesses and minor injuries that need immediate attention.</p>
                    <ul>
                        <li>Cold, flu, and sinus infections</li>
                        <li>Ear and throat infections</li>
                        <li>Urinary tract infections</li>
                        <li>Skin rashes and infections</li>
                        <li>Minor injuries and burns</li>
                    </ul>
                    <a href="appointment.php" class="btn">Get Treatment</a>
                </div>
            </div>
        </div>

        <div class="doctors-section">
            <h2 style="text-align: center; color: #0077cc;">Our General Medicine Physicians</h2>
            <p style="text-align: center;">Meet our team of board-certified primary care physicians dedicated to your health and well-being.</p>
            
            <div class="doctors-grid">
                <div class="doctor-card">
                    <img src="immage/Tenadoc Abraraw Admasu.jpg" alt="Dr. Abraraw Admasu" class="doctor-img">
                    <h4>Dr. Abraraw Admasu</h4>
                    <div class="doctor-specialty">Family Medicine</div>
                    <p>15 years of experience in comprehensive family care.</p>
                    <a href="appointment.php" class="btn">Book Appointment</a>
                </div>
                
                <div class="doctor-card">
                    <img src="immage/Dr. Gizaw.jpg" alt="Dr. Gizaw" class="doctor-img">
                    <h4>Dr. Gizaw</h4>
                    <div class="doctor-specialty">Internal Medicine</div>
                    <p>Specializes in adult preventive care and chronic disease.</p>
                    <a href="appointment.php" class="btn">Book Appointment</a>
                </div>
                
                <div class="doctor-card">
                    <img src="immage/Esubalew Amanu - Internist.jpg" alt="Dr. Esubalew Amanu" class="doctor-img">
                    <h4>Dr. Esubalew Amanu</h4>
                    <div class="doctor-specialty">Pediatric & Adult Care</div>
                    <p>Caring for patients from infancy through adulthood.</p>
                    <a href="appointment.php" class="btn">Book Appointment</a>
                </div>
                
                <div class="doctor-card">
                    <img src="immage/Dr.Birhanu.jpg" alt="Dr. Tewodros" class="doctor-img">
                    <h4>Dr. Tewodros</h4>
                    <div class="doctor-specialty">Geriatric Medicine</div>
                    <p>Special focus on health needs of older adults.</p>
                    <a href="appointment.php" class="btn">Book Appointment</a>
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