<?php
require_once 'config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_name = sanitize($_POST['patient-name'] ?? '');
    $age = (int)($_POST['age'] ?? 0);
    $gender = $_POST['gender'] ?? '';
    $emergency_type = $_POST['emergency'] ?? '';
    $phone = sanitize($_POST['phone'] ?? '');
    $notes = sanitize($_POST['notes'] ?? '');
    
    if ($patient_name && $age > 0 && $phone) {
        $stmt = $pdo->prepare("INSERT INTO emergency_requests (patient_name, age, gender, emergency_type, phone, notes) VALUES (?,?,?,?,?,?)");
        if ($stmt->execute([$patient_name, $age, $gender, $emergency_type, $phone, $notes])) {
            $success = "Emergency request submitted successfully! Medical team has been notified. Please stay by your phone.";
        } else {
            $error = "Failed to submit emergency request. Please call us immediately.";
        }
    } else {
        $error = "Please fill in all required fields.";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emergency Registration - Hakim Gizaw Hospital</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff;
            color: #333;
        }
        .emergency-header {
            background-color: #e74c3c;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .emergency-numbers {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .emergency-card {
            background: #fafaf9;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            text-align: center;
            border-top: 5px solid #e74c3c;
        }
        .emergency-card i {
            font-size: 2rem;
            color: #e74c3c;
            margin-bottom: 15px;
        }
        .emergency-card h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
        }
        .phone-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #e74c3c;
            margin: 15px 0;
        }
        .btn {
            display: inline-block;
            background: #e74c3c;
            color: white;
            padding: 12px 25px;
            border-radius: 100px;
            text-decoration: none;
            font-weight: 500;
            margin-top: 10px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        .hospital-name {
            font-size: 1.8em;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="logo">
        <img src="immage/pbanner.png" height="100px" alt="Healthcare Center Logo" width="400">
        <img src="immage/H G LOGO.png" hspace="50px" height="300px" alt="Healthcare Center Logo" width="300PX">
    </div>
    
    <nav>
        <div class="navbar">
            <ul class="nav-links">
                <li><a href="home.php"><i class="fas fa-home"></i> index</a></li>
                <li><a href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                <li><a href="doctors.php">Doctors</a></li>
                <li><a href="appointment.php">Appointment</a></li>
                <li><a href="service.php">Services</a></li>
                <li><a href="contact.php"><i class="fas fa-phone-alt"></i> Contact</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="emergency-header">
        <div class="hospital-name">Hakim Gizaw Hospital</div>
        <h1>Emergency Patient Registration</h1>
    </div>

    <div class="emergency-numbers">
        <div class="emergency-card">
            <i class="fas fa-ambulance"></i>
            <h3>Ambulance Service</h3>
            <p>24/7 emergency ambulance dispatch</p>
            <div class="phone-number">+251 911 987 654</div>
            <a href="tel:+251911987654" class="btn"><i class="fas fa-phone"></i> Call Ambulance</a>
        </div>
        
        <div class="emergency-card">
            <i class="fas fa-first-aid"></i>
            <h3>Emergency Department</h3>
            <p>Direct hospital emergency line</p>
            <div class="phone-number">+251 115 567 890</div>
            <a href="tel:+251115567890" class="btn"><i class="fas fa-phone"></i> Call Emergency</a>
        </div>
        
        <div class="emergency-card">
            <i class="fas fa-heartbeat"></i>
            <h3>National Emergency</h3>
            <p>Ethiopian national emergency number</p>
            <div class="phone-number">833</div>
            <a href="tel:833" class="btn"><i class="fas fa-phone"></i> Call 833</a>
        </div>
    </div>
    
    <div class="container">
        <?php if($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="patient-name">Patient Full Name *</label>
                <input type="text" id="patient-name" name="patient-name" required>
            </div>

            <div class="form-group">
                <label for="age">Age *</label>
                <input type="number" id="age" name="age" required>
            </div>

            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="emergency">Emergency Type *</label>
                <select id="emergency" name="emergency" required>
                    <option value="">Select Emergency Type</option>
                    <option value="accident">Accident</option>
                    <option value="heart">Heart Attack</option>
                    <option value="stroke">Stroke</option>
                    <option value="breathing">Breathing Difficulty</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="phone">Contact Phone Number *</label>
                <input type="tel" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="notes">Additional Notes</label>
                <input type="text" id="notes" name="notes">
            </div>

            <button type="submit" class="btn" style="width: 100%;">Submit Emergency Registration</button>
        </form>
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