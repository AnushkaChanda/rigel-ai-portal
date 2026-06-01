<?php
// Configuration for Database connection
// Use Localhost for local development since the university/ISP network is blocking port 3306
// $host = '127.0.0.1'; 
// $port = '3306';      
// $dbname = 'rigel_db2'; 
// $username = 'root';
// $password = ''; 

// ==========================================
// HOSTINGER LIVE DATABASE SETTINGS
// (Uncomment these when uploading to the live server)
// ==========================================
$host = '193.203.184.43'; // srv1261.hstgr.io
$port = '3306';      
$dbname = 'u795710498_careerai'; 
$username = 'u795710498_careeruser';  
$password = 'Rigel@2025';

try {
    // Connect to MySQL server WITHOUT specifying the database yet
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8", $username, $password);
    
    // Set PDO error mode to exception to enforce strict error catching
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Auto-create the database if you haven't manually created it in phpMyAdmin
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    
    // Now select the database
    $pdo->exec("USE `$dbname`");
    
    // Auto-create the users table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `full_name` VARCHAR(255) NOT NULL,
        `email` VARCHAR(255) UNIQUE NOT NULL,
        `password_hash` VARCHAR(255) NOT NULL,
        `account_type` VARCHAR(50) DEFAULT 'student',
        `last_login` TIMESTAMP NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Safely add last_login to existing tables without breaking if it already exists
    try {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `last_login` TIMESTAMP NULL");
    } catch (PDOException $e) {}
    
    // Safely add profile customization fields
    try {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `phone_number` VARCHAR(20) NULL");
    } catch (PDOException $e) {}
    try {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `bio` TEXT NULL");
    } catch (PDOException $e) {}
    try {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `linkedin_url` VARCHAR(255) NULL");
    } catch (PDOException $e) {}
    try {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `github_url` VARCHAR(255) NULL");
    } catch (PDOException $e) {}
    try {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `profile_picture` VARCHAR(255) NULL");
    } catch (PDOException $e) {}
    
    // Safely add password reset fields
    try {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `reset_token` VARCHAR(64) NULL");
    } catch (PDOException $e) {}
    try {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `reset_expires` DATETIME NULL");
    } catch (PDOException $e) {}
    
    // Setting default fetch mode to Associative Arrays
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If the request is expecting JSON (like our fetch API), return a JSON error
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Database Connection failed: ' . $e->getMessage()]);
    exit;
}
?>
