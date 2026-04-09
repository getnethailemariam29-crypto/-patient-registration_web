-- Create database
CREATE DATABASE IF NOT EXISTS hospital_system;
USE hospital_system;

-- 1. Patients table
CREATE TABLE IF NOT EXISTS patients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    health_care_number VARCHAR(50) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    age INT NOT NULL,
    gender ENUM('male','female','other','prefer-not-to-say') NOT NULL,
    marital_status VARCHAR(20),
    under18 ENUM('yes','no') NOT NULL,
    birth_month VARCHAR(20) NOT NULL,
    birth_day INT NOT NULL,
    birth_year INT NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    street_address1 VARCHAR(100),
    street_address2 VARCHAR(100),
    city VARCHAR(50),
    state VARCHAR(50),
    postal_code VARCHAR(20),
    emergency_first_name VARCHAR(50) NOT NULL,
    emergency_last_name VARCHAR(50) NOT NULL,
    emergency_relationship VARCHAR(50) NOT NULL,
    emergency_phone VARCHAR(20) NOT NULL,
    doctor_first_name VARCHAR(50),
    doctor_last_name VARCHAR(50),
    doctor_phone VARCHAR(20),
    pharmacy VARCHAR(100),
    pharmacy_phone VARCHAR(20),
    registration_reason TEXT,
    additional_notes TEXT,
    medications ENUM('yes','no'),
    medical_conditions TEXT,
    other_conditions TEXT,
    insurance_company VARCHAR(100),
    insurance_id VARCHAR(50),
    policy_number VARCHAR(50),
    policy_holder_first_name VARCHAR(50),
    policy_holder_last_name VARCHAR(50),
    policy_holder_dob DATE,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Appointments table
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT,
    patient_name VARCHAR(100),
    patient_email VARCHAR(100),
    patient_phone VARCHAR(20),
    doctor_name VARCHAR(100) NOT NULL,
    doctor_specialty VARCHAR(100),
    service_type VARCHAR(100),
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    notes TEXT,
    status ENUM('pending','confirmed','cancelled','completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE SET NULL
);

-- 3. Contact messages
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(100),
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Emergency registrations
CREATE TABLE IF NOT EXISTS emergency_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(20),
    emergency_type VARCHAR(100),
    phone VARCHAR(20) NOT NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 5. Password reset tokens
CREATE TABLE IF NOT EXISTS password_resets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 6. Doctors table
CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    specialty VARCHAR(100) NOT NULL,
    bio TEXT,
    location VARCHAR(100),
    language VARCHAR(100),
    rating DECIMAL(3,1),
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 7. Video calls table
CREATE TABLE IF NOT EXISTS video_calls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    call_id VARCHAR(100) UNIQUE NOT NULL,
    patient_id INT,
    doctor_id INT,
    doctor_name VARCHAR(100),
    patient_name VARCHAR(100),
    call_status ENUM('waiting','active','completed','missed','cancelled') DEFAULT 'waiting',
    scheduled_time DATETIME,
    start_time DATETIME,
    end_time DATETIME,
    duration INT DEFAULT 0,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE SET NULL,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE SET NULL
);
-- Insert sample doctors
INSERT INTO doctors (name, specialty, bio, location, language, rating, image) VALUES
('Dr. Tewodros', 'General Practitioner', 'Specializes in family medicine and preventive care with over 10 years of experience.', 'Hakim Gizaw', 'English, አማርኛ', 4.8, 'immage/Dr.Birhanu.jpg'),
('Dr. Gizaw', 'Cardiologist', 'Heart specialist with 15 years of experience in treating complex cardiac conditions.', 'Hakim Gizaw', 'English, አማርኛ', 4.9, 'immage/Dr. Gizaw.jpg'),
('Dr. Samuel Belay', 'Pediatrician', 'Children\'s health specialist focused on providing compassionate care for infants through adolescents.', 'Tebasie', 'English, አማርኛ', 4.7, 'immage/Doctor.jpg'),
('Dr. Esubalew Amanu', 'Neurologist', 'Expert in treating disorders of the nervous system with a focus on patient-centered care.', 'Hakim Gizaw', 'English, አማርኛ', 4.8, 'immage/Esubalew Amanu - Internist.jpg'),
('Dr. Abraraw Admasu', 'Orthopedic Surgeon', 'Specializes in musculoskeletal conditions with expertise in minimally invasive procedures.', '04 Kebele', 'English, አማርኛ', 4.9, 'immage/Tenadoc Abraraw Admasu.jpg'),
('Dr. Yared Assefa', 'Dermatologist', 'Skin care specialist with expertise in medical, surgical, and cosmetic dermatology.', 'Tebasie', 'English, አማርኛ', 4.7, 'immage/Yared Assefa - Surgeon.jpg');
-- Add doctor_id column to appointments table
ALTER TABLE appointments ADD COLUMN doctor_id INT AFTER doctor_name;

-- Add foreign key constraint (optional)
ALTER TABLE appointments ADD FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE SET NULL;