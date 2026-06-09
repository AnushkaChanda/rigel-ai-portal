<?php
try {
    require_once __DIR__ . '/includes/db_connect.php';

    $stmt = $pdo->query('SELECT summary FROM interviews ORDER BY id DESC LIMIT 1');
    $row = $stmt->fetch();
    
    if ($row && !empty($row['summary'])) {
        echo "LATEST INTERVIEW SUMMARY:\n\n" . $row['summary'];
    } else {
        echo "No summary found in the database. Ensure the interview was processed successfully.";
    }
} catch (Exception $e) {
    echo "DB Error: " . $e->getMessage();
}
?>
