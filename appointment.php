<?php
require_once 'config.php';
requireLogin();

$message = '';
$messageType = '';
$doctor_id = 0; // Initialize doctor_id variable

// Get doctors from database
$doctors = $pdo->query("SELECT * FROM doctors ORDER BY name")->fetchAll();

// Get doctor_id from URL if present
if (isset($_GET['doctor_id'])) {
    $doctor_id = (int)$_GET['doctor_id'];
}

// Handle appointment booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_appointment'])) {
    $doctor_name = sanitize($_POST['doctor_name']);
    $doctor_id = (int)($_POST['doctor_id'] ?? 0);
    $service_type = sanitize($_POST['service_type']);
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $notes = sanitize($_POST['appointment_notes']);
    $patient_id = $_SESSION['user_id'];
    
    // Get patient details
    $stmt = $pdo->prepare("SELECT first_name, last_name, email, phone FROM patients WHERE id = ?");
    $stmt->execute([$patient_id]);
    $patient = $stmt->fetch();
    
    $patient_name = $patient['first_name'] . ' ' . $patient['last_name'];
    $patient_email = $patient['email'];
    $patient_phone = $patient['phone'];
    
    // Get doctor specialty
    $doctor_specialty = '';
    foreach ($doctors as $doc) {
        if ($doc['name'] == $doctor_name) {
            $doctor_specialty = $doc['specialty'];
            $doctor_id = $doc['id'];
            break;
        }
    }
    
    $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, patient_name, patient_email, patient_phone, doctor_name, doctor_id, doctor_specialty, service_type, appointment_date, appointment_time, notes) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
    
    if ($stmt->execute([$patient_id, $patient_name, $patient_email, $patient_phone, $doctor_name, $doctor_id, $doctor_specialty, $service_type, $appointment_date, $appointment_time, $notes])) {
        $message = "Appointment booked successfully! A confirmation has been sent.";
        $messageType = "success";
        // Store the booked doctor_id for video call button
        $booked_doctor_id = $doctor_id;
    } else {
        $message = "Failed to book appointment. Please try again.";
        $messageType = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Healthcare Center</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .appointment-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .appointment-header h1 {
            color: #0077cc;
            margin-bottom: 10px;
            font-size: 2.5rem;
        }
        .appointment-container {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }
        .calendar-section {
            flex: 2;
            min-width: 300px;
            background: #f5f5f5;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .appointment-form-section {
            flex: 1;
            min-width: 300px;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .doctor-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .doctor-card:hover {
            border-color: #0077cc;
            box-shadow: 0 0 10px rgba(0, 119, 204, 0.1);
        }
        .doctor-card.selected {
            background-color: #e6f2ff;
            border-color: #0077cc;
        }
        .doctor-name {
            font-weight: bold;
            color: #0077cc;
            margin-bottom: 5px;
            font-size: 1.2rem;
        }
        .doctor-specialty {
            color: #666;
            font-size: 0.9em;
            margin-bottom: 10px;
        }
        .available-slots {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        .time-slot {
            padding: 8px 12px;
            background: #f5f5f5;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .time-slot:hover {
            background: #e0e0e0;
        }
        .time-slot.selected {
            background: #0077cc;
            color: white;
        }
        .btn {
            padding: 12px 20px;
            background: #0077cc;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
            width: 100%;
            margin-top: 20px;
        }
        .btn:hover {
            background: #005599;
        }
        .btn-video-call {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            transition: transform 0.3s;
            text-align: center;
            margin-top: 15px;
        }
        .btn-video-call:hover {
            transform: scale(1.05);
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .video-call-section {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
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
                <li><a href="home.php"><i class="fas fa-home"></i> index</a></li>
                <li><a href="register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                <li><a href="appointment.php" class="active"><i class="fas fa-calendar-check"></i> Appointments</a></li>
                <li><a href="service.php"><i class="fas fa-medkit"></i> Services</a></li>
                <li><a href="doctors.php"><i class="fas fa-user-md"></i> Doctors</a></li>
                <li><a href="contact.php"><i class="fas fa-phone-alt"></i> Contact</a></li>
                <li><a href="facilities.php">Facilities</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="call-history.php"><i class="fas fa-history"></i> History</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php endif; ?>
                <div class="nav-buttons">
                    <a href="emergency.php" class="btn-emergency">Emergency</a>
                </div>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        <div class="appointment-header">
            <h1><i class="fas fa-calendar-alt"></i> Book an Appointment</h1>
            <p>Schedule your visit with our healthcare providers</p>
        </div>

        <?php if ($message): ?>
            <div class="<?php echo $messageType; ?>-message">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="appointment-container">
            <div class="calendar-section">
                <h2>Select Date & Time</h2>
                <div class="form-group">
                    <label for="appointment-date">Appointment Date</label>
                    <input type="date" id="appointment-date" min="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="form-group">
                    <label for="service-type">Service Type</label>
                    <select id="service-type">
                        <option value="">Select a service</option>
                        <option value="general-checkup">General Checkup</option>
                        <option value="consultation">Consultation</option>
                        <option value="follow-up">Follow-up Visit</option>
                        <option value="vaccination">Vaccination</option>
                        <option value="lab-test">Lab Test</option>
                    </select>
                </div>
            </div>

            <div class="appointment-form-section">
                <form method="POST" action="" id="appointmentForm">
                    <h2>Select Doctor</h2>
                    
                    <?php foreach($doctors as $index => $doctor): ?>
                    <div class="doctor-card <?php echo ($index === 0 && $doctor_id == 0) || $doctor_id == $doctor['id'] ? 'selected' : ''; ?>" 
                         data-doctor="<?php echo htmlspecialchars($doctor['name']); ?>"
                         data-doctor-id="<?php echo $doctor['id']; ?>">
                        <div class="doctor-name"><?php echo htmlspecialchars($doctor['name']); ?></div>
                        <div class="doctor-specialty"><?php echo htmlspecialchars($doctor['specialty']); ?></div>
                        <div class="doctor-bio"><?php echo htmlspecialchars($doctor['bio']); ?></div>
                    </div>
                    <?php endforeach; ?>
                    
                    <input type="hidden" id="selected-doctor" name="doctor_name" value="<?php echo $doctors[0]['name'] ?? ''; ?>">
                    <input type="hidden" id="selected-doctor-id" name="doctor_id" value="<?php echo $doctor_id > 0 ? $doctor_id : ($doctors[0]['id'] ?? 0); ?>">
                    
                    <h2 style="margin-top: 30px;">Available Time Slots</h2>
                    <div class="available-slots">
                        <div class="time-slot selected" data-time="09:00:00">9:00 AM</div>
                        <div class="time-slot" data-time="10:00:00">10:00 AM</div>
                        <div class="time-slot" data-time="11:00:00">11:00 AM</div>
                        <div class="time-slot" data-time="13:00:00">1:00 PM</div>
                        <div class="time-slot" data-time="14:00:00">2:00 PM</div>
                        <div class="time-slot" data-time="15:00:00">3:00 PM</div>
                    </div>
                    <input type="hidden" id="selected-time" name="appointment_time" value="09:00:00">
                    <input type="hidden" id="selected-date" name="appointment_date">
                    
                    <div class="form-group" style="margin-top: 30px;">
                        <label for="appointment-notes">Appointment Notes</label>
                        <textarea id="appointment-notes" name="appointment_notes" rows="4" placeholder="Any special requests or information for the doctor"></textarea>
                    </div>
                    
                    <button type="submit" name="book_appointment" class="btn">Book Appointment</button>
                </form>
                
                <!-- Video Call Button - Shows after successful booking -->
                <?php if(isset($booked_doctor_id) && $booked_doctor_id > 0): ?>
                <div class="video-call-section">
                    <a href="video-call.php?doctor_id=<?php echo $booked_doctor_id; ?>" class="btn-video-call">
                        <i class="fas fa-video"></i> Start Video Consultation
                    </a>
                    <p style="font-size: 12px; color: #666; margin-top: 10px;">
                        <i class="fas fa-info-circle"></i> Click above to start a video call with your doctor
                    </p>
                </div>
                <?php endif; ?>
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

    <script>
        // Doctor selection
        const doctorCards = document.querySelectorAll('.doctor-card');
        const selectedDoctorInput = document.getElementById('selected-doctor');
        const selectedDoctorIdInput = document.getElementById('selected-doctor-id');
        
        doctorCards.forEach(card => {
            card.addEventListener('click', function() {
                doctorCards.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                const doctorName = this.getAttribute('data-doctor');
                const doctorId = this.getAttribute('data-doctor-id');
                selectedDoctorInput.value = doctorName;
                selectedDoctorIdInput.value = doctorId;
            });
        });
        
        // Time slot selection
        const timeSlots = document.querySelectorAll('.time-slot');
        const selectedTimeInput = document.getElementById('selected-time');
        
        timeSlots.forEach(slot => {
            slot.addEventListener('click', function() {
                timeSlots.forEach(s => s.classList.remove('selected'));
                this.classList.add('selected');
                const timeValue = this.getAttribute('data-time');
                selectedTimeInput.value = timeValue;
            });
        });
        
        // Set date input value
        const dateInput = document.getElementById('appointment-date');
        const selectedDateInput = document.getElementById('selected-date');
        
        if (dateInput) {
            dateInput.addEventListener('change', function() {
                selectedDateInput.value = this.value;
            });
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            dateInput.value = tomorrow.toISOString().split('T')[0];
            selectedDateInput.value = dateInput.value;
        }
        
        // Form validation
        const form = document.getElementById('appointmentForm');
        form.addEventListener('submit', function(e) {
            if (!selectedDateInput.value) {
                alert('Please select an appointment date');
                e.preventDefault();
                return false;
            }
            if (!selectedDoctorInput.value) {
                alert('Please select a doctor');
                e.preventDefault();
                return false;
            }
            const serviceType = document.getElementById('service-type');
            if (!serviceType.value) {
                alert('Please select a service type');
                e.preventDefault();
                return false;
            }
            const hiddenService = document.createElement('input');
            hiddenService.type = 'hidden';
            hiddenService.name = 'service_type';
            hiddenService.value = serviceType.value;
            form.appendChild(hiddenService);
            return true;
        });
    </script>
</body>
</html>