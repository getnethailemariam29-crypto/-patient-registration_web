<?php
require_once 'config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $subject = $_POST['subject'] ?? '';
    $message = sanitize($_POST['message'] ?? '');
    
    if ($name && $email && $message) {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?,?,?,?,?)");
        if ($stmt->execute([$name, $email, $phone, $subject, $message])) {
            $success = "Thank you for contacting us! We'll get back to you within 24 hours.";
        } else {
            $error = "Failed to send message. Please try again.";
        }
    } else {
        $error = "Please fill in all required fields (Name, Email, Message).";
    }
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
    <title>Contact Us - Hakim Gizaw Hospital</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .contact-section {
            padding: 2rem 0;
        }
        .contact-container {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
        }
        .contact-info, .contact-form {
            flex: 1;
            min-width: 300px;
        }
        .contact-info {
            background: #e9f5f9;
            padding: 1.5rem;
            border-radius: 5px;
        }
        .contact-info h3 {
            color: #005f73;
            border-bottom: 2px solid #005f73;
            padding-bottom: 0.5rem;
        }
        .contact-info p {
            margin: 1rem 0;
        }
        .contact-form {
            background: #fff;
            padding: 1.5rem;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .contact-form h3 {
            color: #005f73;
            margin-bottom: 1rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        .form-group input, 
        .form-group textarea, 
        .form-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group textarea {
            height: 100px;
        }
        button {
            background: #005f73;
            color: #fff;
            border: none;
            padding: 0.7rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #003d4d;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        footer {
            background: #005f73;
            color: #fff;
            text-align: center;
            padding: 1rem 0;
            margin-top: 2rem;
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
    
    <section class="contact-section">
        <div class="container">
            <h2>Contact Us</h2>
            <p>Have questions or need assistance with patient registration? Reach out to our support team.</p>
            
            <?php if($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="contact-container">
                <div class="contact-info">
                    <h3>Our Information</h3>
                    <p><strong>Address:</strong> Kebele 04, Debre Birhan City, North Shewa, Amhara, Ethiopia</p>
                    <p><strong>Phone:</strong> (+251) 116-170-679</p>
                    <p><strong>Email:</strong> info@hakimgizawhospital.com</p>
                    <p><strong>Working Hours:</strong> Monday - Friday, 8:00 AM - 6:00 PM</p>
                    
                    <h3>Emergency Support</h3>
                    <p>For urgent medical issues, please call: <strong>(+251) 116-170-432</strong></p>
                </div>
                
                <div class="contact-form">
                    <h3>Send Us a Message</h3>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <select id="subject" name="subject">
                                <option value="registration">Patient Registration</option>
                                <option value="appointment">Appointment Booking</option>
                                <option value="technical">Technical Support</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        <button type="submit">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2025 Hakim Gizaw Hospital. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>