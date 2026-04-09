<?php
// setup_database.php - Run this once to setup everything
$host = 'localhost';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Read and execute SQL file
    $sql = file_get_contents('database.sql');
    $pdo->exec($sql);
    
    echo "<h2 style='color: green;'>✅ Database Setup Complete!</h2>";
    echo "<ul>";
    echo "<li>✅ Database 'hospital_system' created</li>";
    echo "<li>✅ All tables created successfully</li>";
    echo "<li>✅ Sample doctors inserted (6 records)</li>";
    echo "<li>✅ Test doctor user created (username: doctor, password: Doctor@123)</li>";
    echo "</ul>";
    echo "<br><a href='register.php' style='display: inline-block; padding: 10px 20px; background: #0077cc; color: white; text-decoration: none; border-radius: 5px;'>Go to Registration →</a>";
    echo "&nbsp;&nbsp;<a href='login.php' style='display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px;'>Go to Login →</a>";
    echo "<br><br><strong style='color: red;'>⚠️ Delete this setup_database.php file now!</strong>";
    
} catch(PDOException $e) {
    echo "<h2 style='color: red;'>❌ Error: " . $e->getMessage() . "</h2>";
    echo "<p>Make sure MySQL is running in XAMPP!</p>";
}
?>