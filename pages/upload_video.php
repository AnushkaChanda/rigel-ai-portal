<?php
session_start();

// Ensure the user is logged in
// if (!isset($_SESSION['user_id'])) {
//     http_response_code(401);
//     echo json_encode(["status" => "error", "message" => "Unauthorized"]);
//     exit;
// }

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
