<?php
require_once 'includes/db_connect.php';
try {
    $sql = "CREATE TABLE IF NOT EXISTS interviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        video_filename VARCHAR(255) NOT NULL,
        transcript TEXT NOT NULL,
        summary TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);

    $uid = 1;
    $fileName = 'test_video.webm';
    $transcript = 'This is a test transcript.';
    $summary = 'This is a test summary.';

    $stmt = $pdo->prepare("INSERT INTO interviews (user_id, video_filename, transcript, summary) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$uid, $fileName, $transcript, $summary])) {
        echo "Insert SUCCESS!\n";
    } else {
        echo "Insert FAILED!\n";
    }
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
?>
