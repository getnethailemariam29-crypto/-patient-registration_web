<?php
require_once 'config.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and collect form data
    $health_care_number = sanitize($_POST['healthCareNumber'] ?? '');
    $first_name = sanitize($_POST['firstName'] ?? '');
    $last_name = sanitize($_POST['lastName'] ?? '');
    $age = (int)($_POST['age'] ?? 0);
    $gender = $_POST['gender'] ?? '';
    $marital_status = $_POST['marital-status'] ?? '';
    $under18 = $_POST['under18'] ?? '';
    $birth_month = $_POST['birthMonth'] ?? '';
    $birth_day = (int)($_POST['birthDay'] ?? 0);
    $birth_year = (int)($_POST['birthYear'] ?? 0);
    $phone = sanitize($_POST['phone'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $street1 = sanitize($_POST['streetAddress1'] ?? '');
    $street2 = sanitize($_POST['streetAddress2'] ?? '');
    $city = sanitize($_POST['city'] ?? '');
    $state = sanitize($_POST['state'] ?? '');
    $postal = sanitize($_POST['postalCode'] ?? '');
    $emergency_first = sanitize($_POST['emergencyFirstName'] ?? '');
    $emergency_last = sanitize($_POST['emergencyLastName'] ?? '');
    $emergency_rel = sanitize($_POST['emergencyRelationship'] ?? '');
    $emergency_phone = sanitize($_POST['emergencyPhone'] ?? '');
    $doc_first = sanitize($_POST['doctorFirstName'] ?? '');
    $doc_last = sanitize($_POST['doctorLastName'] ?? '');
    $doc_phone = sanitize($_POST['doctorPhone'] ?? '');
    $pharmacy = sanitize($_POST['pharmacy'] ?? '');
    $pharmacy_phone = sanitize($_POST['pharmacyPhone'] ?? '');
    $reg_reason = sanitize($_POST['registrationReason'] ?? '');
    $notes = sanitize($_POST['additionalNotes'] ?? '');
    $medications = $_POST['medications'] ?? '';
    $conditions = isset($_POST['conditions']) ? implode(',', $_POST['conditions']) : '';
    $other_conditions = sanitize($_POST['other_conditions'] ?? '');
    $insurance_co = sanitize($_POST['insuranceCompany'] ?? '');
    $insurance_id = sanitize($_POST['insuranceId'] ?? '');
    $policy_no = sanitize($_POST['policyNumber'] ?? '');
    $policy_holder_first = sanitize($_POST['policyHolderFirstName'] ?? '');
    $policy_holder_last = sanitize($_POST['policyHolderLastName'] ?? '');
    $policy_holder_dob = $_POST['policyHolderDob'] ?? '';
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirmPassword'] ?? '';

    // Validation
    if (empty($first_name) || empty($last_name)) $errors[] = "First and last name are required.";
    if ($age < 0 || $age > 110) $errors[] = "Please enter a valid age (0-110).";
    if (empty($gender)) $errors[] = "Please select gender.";
    if (empty($phone)) $errors[] = "Phone number is required.";
    if (empty($username) || strlen($username) < 4) $errors[] = "Username must be at least 4 characters.";
    if (strlen($password) < 8 || !preg_match('/[!@#$%^&*]/', $password)) {
        $errors[] = "Password must be at least 8 characters with a special character (!@#$%^&*).";
    }
    if ($password !== $confirm) $errors[] = "Passwords do not match.";
    if (empty($health_care_number)) $errors[] = "Health care number is required.";
    if (empty($emergency_first) || empty($emergency_last) || empty($emergency_phone)) {
        $errors[] = "Emergency contact information is required.";
    }

    // Check if username or health care number exists
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM patients WHERE username = ? OR health_care_number = ?");
            $stmt->execute([$username, $health_care_number]);
            if ($stmt->fetch()) {
                $errors[] = "Username or Health Care Number already exists. Please choose a different one.";
            }
        } catch (PDOException $e) {
            // Table might not exist yet
            if (strpos($e->getMessage(), "Table 'hospital_system.patients' doesn't exist") !== false) {
                // Table doesn't exist - that's fine for new setup
                // We'll create it in the next step
            } else {
                $errors[] = "Database error: " . $e->getMessage();
            }
        }
    }

    // Insert into database
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO patients (
            health_care_number, first_name, last_name, age, gender, marital_status, under18,
            birth_month, birth_day, birth_year, phone, email, street_address1, street_address2, 
            city, state, postal_code, emergency_first_name, emergency_last_name, emergency_relationship, 
            emergency_phone, doctor_first_name, doctor_last_name, doctor_phone, pharmacy, pharmacy_phone,
            registration_reason, additional_notes, medications, medical_conditions, other_conditions,
            insurance_company, insurance_id, policy_number, policy_holder_first_name, policy_holder_last_name,
            policy_holder_dob, username, password_hash
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        
        try {
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute([
                $health_care_number, $first_name, $last_name, $age, $gender, $marital_status, $under18,
                $birth_month, $birth_day, $birth_year, $phone, $email, $street1, $street2,
                $city, $state, $postal, $emergency_first, $emergency_last, $emergency_rel,
                $emergency_phone, $doc_first, $doc_last, $doc_phone, $pharmacy, $pharmacy_phone,
                $reg_reason, $notes, $medications, $conditions, $other_conditions,
                $insurance_co, $insurance_id, $policy_no, $policy_holder_first, $policy_holder_last,
                $policy_holder_dob, $username, $hashed_password
            ])) {
                $success = "Registration successful! You can now <a href='login.php'>login to your account</a>.";
                // Clear form data after success
                $_POST = array();
            } else {
                $errors[] = "Database error. Please try again.";
            }
        } catch(PDOException $e) {
            if (strpos($e->getMessage(), "Table 'hospital_system.patients' doesn't exist") !== false) {
                $errors[] = "Database tables are not set up. Please run setup_database.php first.";
            } else {
                $errors[] = "Error: " . $e->getMessage();
            }
        }
    }
}
?>
<!-- Rest of your HTML remains the same -->
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
    <title>Complete Patient Registration</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #0077cc;
            --secondary: #333333;
            --success: #28a745;
            --error: #e74c3c;
            --warning: #ffc107;
            --light-gray: #f8f9fa;
            --medium-gray: #e0e0e0;
            --dark-gray: #666666;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body{
            background-color: #bbcbd6;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: #dfd6cd;
            border-radius: 12px;
            padding: 40px 50px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 8px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), var(--success));
        }

        .container h1 {
            color: var(--primary);
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--medium-gray);
        }

        .form-description {
            color: var(--dark-gray);
            margin-bottom: 35px;
            font-size: 16px;
            text-align: center;
        }

        .form-section h2 {
            color: var(--primary);
            font-size: 20px;
            font-weight: 600;
            margin: 25px 0 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--medium-gray);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section h2 i {
            color: var(--primary);
            font-size: 18px;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .error-text {
            color: var(--error);
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }

        .form-section {
            margin-bottom: 25px;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid var(--medium-gray);
        }

        .form-row {
            display: flex;
            gap: 25px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: var(--secondary);
        }

        .required::after {
            content: '*';
            color: var(--error);
            margin-left: 4px;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--medium-gray);
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: white;
        }

        input, select, textarea {
            border: 2px solid #555 !important;
            font-weight: bold;
        }

        input.error, select.error, textarea.error {
            border-color: var(--error);
        }

        input.success, select.success, textarea.success {
            border-color: var(--success);
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 119, 204, 0.15);
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 8px;
        }

        .radio-option {
            display: flex;
            align-items: center;
        }

        .radio-option input {
            width: auto;
            margin-right: 8px;
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 8px;
        }

        .checkbox-option {
            display: flex;
            align-items: center;
            background: var(--light-gray);
            padding: 8px 15px;
            border-radius: 6px;
        }

        .checkbox-option input {
            width: auto;
            margin-right: 8px;
        }

        .form-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-submit {
            background-color: var(--primary);
            color: white;
        }

        .btn-submit:hover {
            background-color: #0dd818;
        }

        .btn-reset {
            background-color: #6c757d;
            color: white;
        }

        .btn-reset:hover {
            background-color: #f71717;
        }

        .video-container {
            width: 100%;
            height: 190px;
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
    <div class="video-container">
        <video autoplay muted playsinline>
            <source src="immage/banner.mp4" type="video/mp4">
        </video>
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
    <div class="container">
        <h1>New Patient Registration</h1>
        <p class="form-description">Please fill in the form below with accurate information</p>

        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach($errors as $err): ?>
                    <p>• <?php echo $err; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="success-message">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form id="registrationForm" method="POST" action="">
            <!-- Health Care Number Section -->
            <div class="form-section">
                <h2><i class="fas fa-id-card"></i> Health Care Number</h2>
                <div class="form-group">
                    <label class="required">Health Care Number</label>
                    <input type="text" id="healthCareNumber" name="healthCareNumber" required value="<?php echo isset($_POST['healthCareNumber']) ? htmlspecialchars($_POST['healthCareNumber']) : ''; ?>">
                    <p class="error-text" id="healthCareNumberError">Please enter a valid health care number</p>
                </div>
            </div>

            <!-- Registration Date and Time Section -->
            <div class="form-section">
                <h2><i class="far fa-calendar-alt"></i> Registration Date and Time</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label for="reg-date" class="required">Date</label>
                        <input type="date" id="reg-date" name="reg-date" required>
                    </div>
                    <div class="form-group">
                        <label for="reg-time" class="required">Time</label>
                        <input type="time" id="reg-time" name="reg-time" required>
                    </div>
                </div>
            </div>

            <!-- Personal Information Section -->
            <div class="form-section">
                <h2><i class="fas fa-user"></i> Personal Information</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label class="required">First Name</label>
                        <input type="text" id="firstName" name="firstName" required value="<?php echo isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : ''; ?>">
                        <p class="error-text" id="firstNameError">First letter must be capital and only letters allowed</p>
                    </div>
                    <div class="form-group">
                        <label class="required">Last Name</label>
                        <input type="text" id="lastName" name="lastName" required value="<?php echo isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : ''; ?>">
                        <p class="error-text" id="lastNameError">First letter must be capital and only letters allowed</p>
                    </div>
                    <div class="form-group">
                        <label class="required">Age</label>
                        <input type="number" id="age" name="age" min="0" max="110" required value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''; ?>">
                        <p class="error-text" id="ageError">Age must be between 0 and 110</p>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="gender" class="required">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
                            <option value="female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
                            <option value="other" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'other') ? 'selected' : ''; ?>>Other</option>
                            <option value="prefer-not-to-say" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'prefer-not-to-say') ? 'selected' : ''; ?>>Prefer not to say</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="marital-status">Marital Status</label>
                        <select id="marital-status" name="marital-status">
                            <option value="">Select Status</option>
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                            <option value="divorced">Divorced</option>
                            <option value="widowed">Widowed</option>
                            <option value="separated">Separated</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="required">Is the patient younger than 18?</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="under18-yes" name="under18" value="yes" required>
                            <label for="under18-yes">Yes</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="under18-no" name="under18" value="no">
                            <label for="under18-no">No</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date of Birth Section -->
            <div class="form-section">
                <h2><i class="fas fa-birthday-cake"></i> Date of Birth</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label class="required">Month</label>
                        <select id="birthMonth" name="birthMonth" required>
                            <option value="">Select Month</option>
                            <option value="January">January</option>
                            <option value="February">February</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="required">Day</label>
                        <select id="birthDay" name="birthDay" required>
                            <option value="">Select Day</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="required">Year</label>
                        <select id="birthYear" name="birthYear" required>
                            <option value="">Select Year</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Phone Number Section -->
            <div class="form-section">
                <h2><i class="fas fa-phone"></i> Phone Number</h2>
                <div class="form-group">
                    <label class="required">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="+251 000-000-000" required value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                    <p class="error-text" id="phoneError">Please enter a valid phone number (e.g., +251 912 345 678)</p>
                </div>
            </div>

            <!-- Email Section -->
            <div class="form-section">
                <h2><i class="fas fa-envelope"></i> Email</h2>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" id="email" name="email" placeholder="ex: myname@example.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    <p class="error-text" id="emailError">Please enter a valid email address</p>
                    <p class="example-text">example: example@example.com</p>
                </div>
            </div>

            <!-- Address Section -->
            <div class="form-section">
                <h2><i class="fas fa-map-marker-alt"></i> Address</h2>
                <div class="form-group">
                    <label>Street Address</label>
                    <input type="text" id="streetAddress1" name="streetAddress1" value="<?php echo isset($_POST['streetAddress1']) ? htmlspecialchars($_POST['streetAddress1']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label>Street Address Line 2</label>
                    <input type="text" id="streetAddress2" name="streetAddress2" value="<?php echo isset($_POST['streetAddress2']) ? htmlspecialchars($_POST['streetAddress2']) : ''; ?>">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" id="city" name="city" value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>State / Province</label>
                        <input type="text" id="state" name="state" value="<?php echo isset($_POST['state']) ? htmlspecialchars($_POST['state']) : ''; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label>Postal / Zip Code</label>
                    <input type="text" id="postalCode" name="postalCode" value="<?php echo isset($_POST['postalCode']) ? htmlspecialchars($_POST['postalCode']) : ''; ?>">
                </div>
            </div>

            <!-- Emergency Contact Section -->
            <div class="form-section">
                <h2><i class="fas fa-exclamation-triangle"></i> Emergency Contact</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label class="required">First Name</label>
                        <input type="text" id="emergencyFirstName" name="emergencyFirstName" required value="<?php echo isset($_POST['emergencyFirstName']) ? htmlspecialchars($_POST['emergencyFirstName']) : ''; ?>">
                        <p class="error-text" id="emergencyFirstNameError">First letter must be capital and only letters allowed</p>
                    </div>
                    <div class="form-group">
                        <label class="required">Last Name</label>
                        <input type="text" id="emergencyLastName" name="emergencyLastName" required value="<?php echo isset($_POST['emergencyLastName']) ? htmlspecialchars($_POST['emergencyLastName']) : ''; ?>">
                        <p class="error-text" id="emergencyLastNameError">First letter must be capital and only letters allowed</p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="required">Relationship</label>
                        <input type="text" id="emergencyRelationship" name="emergencyRelationship" placeholder="e.g., Parent, Spouse" required value="<?php echo isset($_POST['emergencyRelationship']) ? htmlspecialchars($_POST['emergencyRelationship']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label class="required">Contact Number</label>
                        <input type="tel" id="emergencyPhone" name="emergencyPhone" placeholder="+251-000-000-0000" required value="<?php echo isset($_POST['emergencyPhone']) ? htmlspecialchars($_POST['emergencyPhone']) : ''; ?>">
                        <p class="error-text" id="emergencyPhoneError">Please enter a valid phone number</p>
                    </div>
                </div>
            </div>

            <!-- Family Doctor Information Section -->
            <div class="form-section">
                <h2><i class="fas fa-user-md"></i> Family Doctor Information</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" id="doctorFirstName" name="doctorFirstName" value="<?php echo isset($_POST['doctorFirstName']) ? htmlspecialchars($_POST['doctorFirstName']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" id="doctorLastName" name="doctorLastName" value="<?php echo isset($_POST['doctorLastName']) ? htmlspecialchars($_POST['doctorLastName']) : ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Family Doctor Phone Number</label>
                        <input type="tel" id="doctorPhone" name="doctorPhone" placeholder="+251-000-000-0000" value="<?php echo isset($_POST['doctorPhone']) ? htmlspecialchars($_POST['doctorPhone']) : ''; ?>">
                        <p class="error-text" id="doctorPhoneError">Please enter a valid phone number</p>
                    </div>
                    <div class="form-group">
                        <label>Preferred Pharmacy</label>
                        <input type="text" id="pharmacy" name="pharmacy" value="<?php echo isset($_POST['pharmacy']) ? htmlspecialchars($_POST['pharmacy']) : ''; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label>Pharmacy Phone Number</label>
                    <input type="tel" id="pharmacyPhone" name="pharmacyPhone" placeholder="+251-000-000-0000" value="<?php echo isset($_POST['pharmacyPhone']) ? htmlspecialchars($_POST['pharmacyPhone']) : ''; ?>">
                    <p class="error-text" id="pharmacyPhoneError">Please enter a valid phone number</p>
                </div>
            </div>

            <!-- Health History Section -->
            <div class="form-section">
                <h2><i class="fas fa-file-medical"></i> Health History</h2>
                <div class="form-group">
                    <label>Reason for Registration</label>
                    <textarea id="registrationReason" name="registrationReason" placeholder="Briefly describe the reason for registration"><?php echo isset($_POST['registrationReason']) ? htmlspecialchars($_POST['registrationReason']) : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Additional Notes</label>
                    <textarea id="additionalNotes" name="additionalNotes" placeholder="Any additional health information"><?php echo isset($_POST['additionalNotes']) ? htmlspecialchars($_POST['additionalNotes']) : ''; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Taking any medications currently?</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="meds-yes" name="medications" value="yes">
                            <label for="meds-yes">Yes</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="meds-no" name="medications" value="no">
                            <label for="meds-no">No</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Medical Conditions (Check all that apply)</label>
                    <div class="checkbox-group">
                        <div class="checkbox-option">
                            <input type="checkbox" id="diabetes" name="conditions[]" value="diabetes">
                            <label for="diabetes">Diabetes</label>
                        </div>
                        <div class="checkbox-option">
                            <input type="checkbox" id="hypertension" name="conditions[]" value="hypertension">
                            <label for="hypertension">Hypertension</label>
                        </div>
                        <div class="checkbox-option">
                            <input type="checkbox" id="heart-disease" name="conditions[]" value="heart-disease">
                            <label for="heart-disease">Heart Disease</label>
                        </div>
                        <div class="checkbox-option">
                            <input type="checkbox" id="asthma" name="conditions[]" value="asthma">
                            <label for="asthma">Asthma</label>
                        </div>
                        <div class="checkbox-option">
                            <input type="checkbox" id="other-condition" name="conditions[]" value="other">
                            <label for="other-condition">Other</label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="other-conditions">Other medical conditions not listed above:</label>
                    <textarea id="other-conditions" name="other_conditions" rows="3"><?php echo isset($_POST['other_conditions']) ? htmlspecialchars($_POST['other_conditions']) : ''; ?></textarea>
                </div>
            </div>

            <!-- Insurance Information Section -->
            <div class="form-section">
                <h2><i class="fas fa-shield-alt"></i> Insurance Information</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label>Insurance Company</label>
                        <input type="text" id="insuranceCompany" name="insuranceCompany" value="<?php echo isset($_POST['insuranceCompany']) ? htmlspecialchars($_POST['insuranceCompany']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Insurance ID</label>
                        <input type="text" id="insuranceId" name="insuranceId" value="<?php echo isset($_POST['insuranceId']) ? htmlspecialchars($_POST['insuranceId']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label>Policy Number</label>
                        <input type="text" id="policyNumber" name="policyNumber" placeholder="e.g., POL123456" value="<?php echo isset($_POST['policyNumber']) ? htmlspecialchars($_POST['policyNumber']) : ''; ?>">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Policy Holder's Name</label>
                        <div class="form-row" style="margin-bottom: 0; gap: 15px;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <input type="text" id="policyHolderFirstName" name="policyHolderFirstName" placeholder="First Name" value="<?php echo isset($_POST['policyHolderFirstName']) ? htmlspecialchars($_POST['policyHolderFirstName']) : ''; ?>">
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <input type="text" id="policyHolderLastName" name="policyHolderLastName" placeholder="Last Name" value="<?php echo isset($_POST['policyHolderLastName']) ? htmlspecialchars($_POST['policyHolderLastName']) : ''; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Policy Holder's Date of Birth</label>
                        <input type="date" id="policyHolderDob" name="policyHolderDob" value="<?php echo isset($_POST['policyHolderDob']) ? htmlspecialchars($_POST['policyHolderDob']) : ''; ?>">
                    </div>
                </div>
            </div>

            <!-- Account Setup Section -->
            <div class="form-section">
                <h2><i class="fas fa-user-cog"></i> Account Setup</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label class="required">Username</label>
                        <input type="text" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        <p class="error-text" id="usernameError">Username must be at least 4 characters</p>
                    </div>
                    <div class="form-group">
                        <label class="required">Password</label>
                        <input type="password" id="password" name="password" required>
                        <p class="error-text" id="passwordError">Password must be at least 8 characters with one special character</p>
                    </div>
                    <div class="form-group">
                        <label class="required">Confirm Password</label>
                        <input type="password" id="confirmPassword" name="confirmPassword" required>
                        <p class="error-text" id="confirmPasswordError">Passwords do not match</p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="checkbox-option">
                        <input type="checkbox" id="consent-terms" name="consent_terms" required>
                        <label for="consent-terms" class="required">I agree to the <a href="#" target="_blank">Terms and Conditions</a> of using this healthcare service.</label>
                    </div>
                </div>
            </div>

            <!-- Form Submission -->
            <div class="form-actions">
                <button type="submit" class="btn btn-submit">Submit Registration</button>
                <button type="reset" class="btn btn-reset">Clear Form</button>
            </div>
        </form>
    </div>
    <script src="form vali.js"></script>
    <script>
        // Populate days and years
        const daySelect = document.getElementById('birthDay');
        const yearSelect = document.getElementById('birthYear');
        
        if (daySelect) {
            for (let day = 1; day <= 31; day++) {
                const option = document.createElement('option');
                option.value = day;
                option.textContent = day;
                daySelect.appendChild(option);
            }
        }
        
        if (yearSelect) {
            const currentYear = new Date().getFullYear();
            for (let year = currentYear; year >= 1900; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                yearSelect.appendChild(option);
            }
        }
    </script>
</body>
</html>