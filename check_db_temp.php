<?php
require_once 'includes/db_connect.php';

$stmt = $pdo->query("SELECT NOW() as now_time, DATE_ADD(NOW(), INTERVAL 1 HOUR) as expiry");
$times = $stmt->fetch();
echo "MySQL NOW(): " . $times['now_time'] . "\n";
echo "MySQL EXPIRY: " . $times['expiry'] . "\n";

echo "PHP time(): " . date('Y-m-d H:i:s') . "\n";

$stmt = $pdo->query("DESCRIBE users");
print_r($stmt->fetchAll());
?>
