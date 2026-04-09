<?php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Read and execute SQL file
    $sql = file_get_contents('database.sql');
    
    // Connect without database first
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Execute SQL
    $pdo->exec($sql);
    
    echo "<h2 style='color: green;'>✅ Database Setup Complete!</h2>";
    echo "<ul>";
    echo "<li>✅ Database 'hospital_system' created</li>";
    echo "<li>✅ All tables created successfully</li>";
    echo "<li>✅ Sample doctors inserted</li>";
    echo "</ul>";
    echo "<br><a href='register.php' style='display: inline-block; padding: 10px 20px; background: #0077cc; color: white; text-decoration: none; border-radius: 5px;'>Go to Registration Page →</a>";
    echo "<br><br><strong style='color: red;'>⚠️ Important: Delete this setup.php file now for security!</strong>";
    
} catch(PDOException $e) {
    echo "<h2 style='color: red;'>❌ Error: " . $e->getMessage() . "</h2>";
}
?>