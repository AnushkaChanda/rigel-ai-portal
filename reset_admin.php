<?php
try {
    require_once __DIR__ . '/includes/db_connect.php';
    
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Insert the superadmin user
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, account_type) VALUES (?, ?, ?, ?)");
    $stmt->execute(['Super Admin', 'admin@rigel.com', $hash, 'superadmin']);
    
    echo "Created admin user with password 'admin123'";
} catch (Exception $e) {
    echo "DB Error: " . $e->getMessage();
}
?>
