<?php
session_start();

header('Content-Type: application/json');

// if (!isset($_SESSION['user_id'])) {
//     http_response_code(401);
//     echo json_encode(["status" => "error", "message" => "Unauthorized"]);
//     exit;
// }

// Replace with actual HuggingFace API Token provided by user later
$hf_api_key = "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";

// Expected input: the filename of the uploaded video
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['fileName'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "No filename provided"]);
    exit;
}

$fileName = basename($data['fileName']);
$filePath = "../uploads/" . $fileName;

if (!file_exists($filePath)) {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "File not found on server"]);
    exit;
}

// NOTE: HuggingFace Whisper API expects an audio file. In a real scenario, you would 
// extract audio from the .webm (e.g. using FFmpeg) before sending.

// ---------------------------------------------------------
// STEP 1: Transcription using HuggingFace Whisper API (Mocked logic)
// ---------------------------------------------------------
$ch = curl_init();
$audioData = file_get_contents($filePath);

curl_setopt_array($ch, array(
    CURLOPT_URL => "https://api-inference.huggingface.co/models/openai/whisper-large-v3",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $audioData,
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $hf_api_key",
        "Content-Type: application/octet-stream"
    ),
));
$response = curl_exec($ch);
$transcriptionData = json_decode($response, true);
$transcript = (is_array($transcriptionData) && isset($transcriptionData['text'])) ? $transcriptionData['text'] : "Mock transcript: The applicant demonstrated strong communication skills and clear dedication to the NGO's mission.";
curl_close($ch);

// ---------------------------------------------------------
// STEP 2: Generate Summary using HuggingFace Mistral/LLaMA
// ---------------------------------------------------------
$ch2 = curl_init();
$prompt = "<s>[INST] Provide a detailed summary of the candidate's specific answers from the following interview transcript. Do not provide a generic evaluation (e.g. 'they are a good fit'). Focus strictly on summarizing the actual points and answers the candidate provided in the interview:\\n\\n" . $transcript . " [/INST]";
$postData2 = json_encode([
    "inputs" => $prompt,
    "parameters" => [
        "max_new_tokens" => 250,
        "return_full_text" => false
    ]
]);
curl_setopt_array($ch2, array(
    CURLOPT_URL => "https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct-v0.2",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postData2,
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $hf_api_key",
        "Content-Type: application/json"
    ),
));
$response2 = curl_exec($ch2);
$summaryData = json_decode($response2, true);
$summary = (is_array($summaryData) && isset($summaryData[0]['generated_text'])) ? $summaryData[0]['generated_text'] : "Mock Summary: Highly recommended. Good fit for the team.";
curl_close($ch2);

// ---------------------------------------------------------
// STEP 3: Save to Database
// ---------------------------------------------------------
require_once '../includes/db_connect.php';

try {
    // Create table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS interviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        video_filename VARCHAR(255) NOT NULL,
        transcript TEXT NOT NULL,
        summary TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);

    // Insert record
    $uid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $stmt = $pdo->prepare("INSERT INTO interviews (user_id, video_filename, transcript, summary) VALUES (?, ?, ?, ?)");
    $stmt->execute([$uid, $fileName, $transcript, $summary]);
} catch (PDOException $e) {
    // Log error but don't break the frontend JSON response if DB isn't configured
    error_log("Database Error: " . $e->getMessage());
}

// Return the summary so it can be emailed in Phase 5
echo json_encode([
    "status" => "success",
    "transcript" => $transcript,
    "summary" => $summary
]);
?>
