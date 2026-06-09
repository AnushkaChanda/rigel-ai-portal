<?php
session_start();
set_time_limit(300);

// Check if file upload limits are exceeded before checking $_FILES
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_FILES) && empty($_POST) && isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > 0) {
        $postMax = ini_get('post_max_size');
        http_response_code(413); // Payload Too Large
        echo json_encode([
            "status" => "error", 
            "message" => "The recording file size is too large for the local server's limit (post_max_size is currently {$postMax}). Please increase post_max_size and upload_max_filesize in your php.ini."
        ]);
        exit;
    }
}

// Check if file is uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['video'])) {
    $uploadDir = '../uploads/';
    
    // Create uploads directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $file = $_FILES['video'];
    
    // Basic validation
    if ($file['error'] !== UPLOAD_ERR_OK) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Upload failed with error code: " . $file['error']]);
        exit;
    }
    
    // Generate a unique filename using user ID or session ID and timestamp
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : session_id();
    $timestamp = time();
    $fileName = "interview_{$userId}_{$timestamp}.webm";
    $targetPath = $uploadDir . $fileName;
    
    // Move the uploaded file
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo json_encode([
            "status" => "success", 
            "message" => "Video uploaded successfully", 
            "fileName" => $fileName,
            "path" => $targetPath
        ]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to save the uploaded file"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "No video file provided"]);
}
?>
