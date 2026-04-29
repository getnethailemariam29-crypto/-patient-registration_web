# Debre Berhan University Student Portal

A fully functional student portal system built with HTML, CSS, JavaScript, PHP, and MySQL for Debre Berhan University.

![PHP](https://img.shields.io/badge/PHP-8.0+-blue) ![MySQL](https://img.shields.io/badge/MySQL-5.7+-orange) ![License](https://img.shields.io/badge/License-MIT-green)

## Features

### Student Portal
- **Login & Registration** — Secure authentication with password hashing
- **Dashboard** — Overview with enrolled courses, GPA, today's schedule, and announcements
- **Profile Management** — View and update personal information, change password
- **Course Registration** — Browse and enroll in available courses
- **Grade Report** — View detailed grades with GPA calculation per semester and cumulative
- **Weekly Schedule** — Visual weekly class timetable with room and building info

### Admin Panel
- **Dashboard** — System statistics, recent registrations, department analytics
- **Student Management** — Add, edit, search, filter, and delete students
- **Course Management** — Add and manage courses with department assignments
- **Grade Management** — Enter and update student grades per course
- **Announcements** — Create and manage announcements for students

## Tech Stack

| Technology | Usage |
|-----------|-------|
| **HTML5** | Page structure and semantic markup |
| **CSS3** | Responsive design, animations, modern UI |
| **JavaScript** | Client-side interactivity, form validation, modals |
| **PHP 8.0+** | Server-side logic, authentication, API |
| **MySQL 5.7+** | Database for storing all portal data |

## Project Structure

```
dbu-student-portal/
├── config/
│   └── database.php          # Database connection configuration
├── sql/
│   └── schema.sql            # Database schema and sample data
├── includes/
│   ├── header.php             # Common header with navigation
│   ├── footer.php             # Common footer
│   └── auth.php               # Authentication and helper functions
├── assets/
│   ├── css/
│   │   └── style.css          # Main stylesheet (responsive)
│   └── js/
│       └── main.js            # Client-side JavaScript
├── admin/
│   ├── index.php              # Admin dashboard
│   ├── manage_students.php    # Student management (CRUD)
│   ├── edit_student.php       # Edit student details
│   ├── manage_courses.php     # Course management (CRUD)
│   ├── manage_grades.php      # Grade entry and management
│   └── announcements.php      # Announcement management
├── student/
│   ├── dashboard.php          # Student dashboard
│   ├── profile.php            # Profile view and edit
│   ├── courses.php            # Course enrollment
│   ├── grades.php             # Grade report
│   └── schedule.php           # Weekly schedule
├── index.php                  # Login page
├── register.php               # Student registration
├── logout.php                 # Logout handler
└── README.md                  # This file
```

## Installation & Setup

### Prerequisites
- PHP 8.0 or higher
- MySQL 5.7 or higher (or MariaDB 10.3+)
- Apache/Nginx web server with mod_rewrite
- XAMPP, WAMP, MAMP, or LAMP stack (recommended for local development)

### Step 1: Clone or Download
```bash
git clone https://github.com/getnethailemariam29-crypto/dbu-student-portal.git
```

Or download and extract the ZIP file.

### Step 2: Database Setup

1. Open **phpMyAdmin** or MySQL command line
2. Create the database and import the schema:

```bash
mysql -u root -p < sql/schema.sql
```

Or in phpMyAdmin:
- Click "Import"
- Select `sql/schema.sql`
- Click "Go"

### Step 3: Configure Database Connection

Edit `config/database.php` with your database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // Your MySQL password
define('DB_NAME', 'dbu_student_portal');
```

### Step 4: Deploy to Web Server

- **XAMPP**: Copy project to `C:\xampp\htdocs\dbu-student-portal\`
- **WAMP**: Copy project to `C:\wamp\www\dbu-student-portal\`
- **Linux**: Copy project to `/var/www/html/dbu-student-portal/`

### Step 5: Access the Portal

Open your browser and navigate to:
```
http://localhost/dbu-student-portal/
```

## Default Login Credentials

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin123` |

> **Note:** Register a new student account through the registration page to test student features.

## Database Schema

### Tables
- **users** — Authentication (username, password hash, role)
- **departments** — University departments (10 pre-loaded)
- **students** — Student profiles linked to users
- **courses** — Course catalog (10 sample courses)
- **enrollments** — Student-course enrollment records
- **grades** — Grade records (midterm, final, assignment, quiz)
- **schedules** — Weekly class schedules
- **announcements** — System announcements

### Grading System
| Score Range | Grade | Grade Point |
|------------|-------|-------------|
| 90-100 | A+ | 4.00 |
| 85-89 | A | 4.00 |
| 80-84 | A- | 3.75 |
| 75-79 | B+ | 3.50 |
| 70-74 | B | 3.00 |
| 65-69 | B- | 2.75 |
| 60-64 | C+ | 2.50 |
| 50-59 | C | 2.00 |
| 45-49 | C- | 1.75 |
| 40-44 | D | 1.00 |
| 0-39 | F | 0.00 |

## Security Features

- Password hashing using `password_hash()` with bcrypt
- Prepared statements to prevent SQL injection
- Input sanitization with `htmlspecialchars()` and `strip_tags()`
- Session-based authentication
- Role-based access control (admin/student)
- CSRF protection through session validation

## Screenshots

The portal features:
- Modern, responsive design that works on desktop, tablet, and mobile
- Professional color scheme with Debre Berhan University branding
- Interactive modals, animated transitions, and real-time form validation
- Font Awesome icons throughout the interface

## License

This project is open source and available under the [MIT License](LICENSE).

## Contact

Debre Berhan University  
Debre Berhan, Ethiopia  
Website: [www.dbu.edu.et](https://www.dbu.edu.et)  
Email: info@dbu.edu.et
