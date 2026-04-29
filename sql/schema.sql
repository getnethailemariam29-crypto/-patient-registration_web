-- Debre Berhan University Student Portal Database Schema
-- Created for DBU Student Portal System

CREATE DATABASE IF NOT EXISTS dbu_student_portal;
USE dbu_student_portal;

-- Users table for authentication
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') DEFAULT 'student',
    email VARCHAR(100) UNIQUE NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Departments table
CREATE TABLE IF NOT EXISTS departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(10) UNIQUE NOT NULL,
    description TEXT,
    head_of_department VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    date_of_birth DATE,
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    department_id INT,
    year INT DEFAULT 1,
    semester INT DEFAULT 1,
    admission_date DATE,
    profile_image VARCHAR(255) DEFAULT 'default.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Courses table
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_code VARCHAR(20) UNIQUE NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    department_id INT,
    credit_hours INT DEFAULT 3,
    description TEXT,
    semester INT,
    year INT,
    instructor VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Enrollments table
CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    semester INT NOT NULL,
    academic_year VARCHAR(20) NOT NULL,
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'dropped', 'completed') DEFAULT 'active',
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, course_id, semester, academic_year)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Grades table
CREATE TABLE IF NOT EXISTS grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT NOT NULL,
    midterm DECIMAL(5,2) DEFAULT 0,
    final_exam DECIMAL(5,2) DEFAULT 0,
    assignment DECIMAL(5,2) DEFAULT 0,
    quiz DECIMAL(5,2) DEFAULT 0,
    total DECIMAL(5,2) DEFAULT 0,
    grade_letter VARCHAR(2),
    grade_point DECIMAL(3,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Schedules table
CREATE TABLE IF NOT EXISTS schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    room VARCHAR(50),
    building VARCHAR(100),
    semester INT,
    academic_year VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Announcements table
CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    author_id INT,
    target ENUM('all', 'students', 'admins') DEFAULT 'all',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default departments
INSERT INTO departments (name, code, description) VALUES
('Computer Science', 'CS', 'Department of Computer Science and Information Technology'),
('Electrical Engineering', 'EE', 'Department of Electrical and Computer Engineering'),
('Civil Engineering', 'CE', 'Department of Civil Engineering'),
('Mechanical Engineering', 'ME', 'Department of Mechanical Engineering'),
('Biology', 'BIO', 'Department of Biology'),
('Chemistry', 'CHEM', 'Department of Chemistry'),
('Physics', 'PHY', 'Department of Physics'),
('Mathematics', 'MATH', 'Department of Mathematics'),
('English', 'ENG', 'Department of English Language and Literature'),
('Business Management', 'BM', 'Department of Business Management');

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, role, email) VALUES
('admin', '$2y$10$8K1p/a0dN1Ux5YPfE7.HQOQg0dXxJgFTp9GVwHnR1eTfz8jB5KWe6', 'admin', 'admin@dbu.edu.et');

-- Insert sample courses
INSERT INTO courses (course_code, course_name, department_id, credit_hours, semester, year, instructor) VALUES
('CS101', 'Introduction to Computer Science', 1, 3, 1, 1, 'Dr. Abebe Kebede'),
('CS201', 'Data Structures and Algorithms', 1, 4, 1, 2, 'Dr. Meseret Tadesse'),
('CS301', 'Database Systems', 1, 3, 1, 3, 'Prof. Solomon Getachew'),
('CS302', 'Software Engineering', 1, 3, 2, 3, 'Dr. Hana Worku'),
('CS401', 'Artificial Intelligence', 1, 3, 1, 4, 'Dr. Daniel Assefa'),
('MATH101', 'Calculus I', 8, 4, 1, 1, 'Prof. Yohannes Bekele'),
('MATH102', 'Linear Algebra', 8, 3, 2, 1, 'Dr. Sara Mengistu'),
('PHY101', 'General Physics I', 7, 4, 1, 1, 'Dr. Tesfaye Hailu'),
('ENG101', 'Communicative English', 9, 3, 1, 1, 'Mr. Dawit Alemu'),
('CS202', 'Object Oriented Programming', 1, 3, 2, 2, 'Dr. Tigist Bekele');

-- Insert sample announcements
INSERT INTO announcements (title, content, author_id, target) VALUES
('Welcome to New Academic Year', 'Welcome to Debre Berhan University! We are excited to start the new academic year 2024/2025. Please check your schedules and course registrations.', 1, 'all'),
('Registration Deadline', 'Course registration deadline is September 30, 2024. Please make sure to complete your registration before the deadline.', 1, 'students'),
('Library Hours Extended', 'The university library will now be open from 7:00 AM to 10:00 PM on weekdays and 8:00 AM to 6:00 PM on weekends.', 1, 'all');

-- Insert sample schedules for courses
INSERT INTO schedules (course_id, day_of_week, start_time, end_time, room, building, semester, academic_year) VALUES
(1, 'Monday', '08:00:00', '09:30:00', 'Room 101', 'ICT Building', 1, '2024/2025'),
(1, 'Wednesday', '08:00:00', '09:30:00', 'Room 101', 'ICT Building', 1, '2024/2025'),
(2, 'Tuesday', '10:00:00', '11:30:00', 'Room 205', 'ICT Building', 1, '2024/2025'),
(2, 'Thursday', '10:00:00', '11:30:00', 'Room 205', 'ICT Building', 1, '2024/2025'),
(3, 'Monday', '14:00:00', '15:30:00', 'Lab 301', 'ICT Building', 1, '2024/2025'),
(3, 'Friday', '14:00:00', '15:30:00', 'Lab 301', 'ICT Building', 1, '2024/2025'),
(6, 'Tuesday', '08:00:00', '09:30:00', 'Room 102', 'Science Building', 1, '2024/2025'),
(6, 'Thursday', '08:00:00', '09:30:00', 'Room 102', 'Science Building', 1, '2024/2025'),
(8, 'Wednesday', '10:00:00', '11:30:00', 'Room 103', 'Science Building', 1, '2024/2025'),
(8, 'Friday', '10:00:00', '11:30:00', 'Room 103', 'Science Building', 1, '2024/2025');
