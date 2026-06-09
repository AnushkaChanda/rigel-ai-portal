<?php
session_start();
set_time_limit(300);

header('Content-Type: application/json');

// if (!isset($_SESSION['user_id'])) {
//     http_response_code(401);
//     echo json_encode(["status" => "error", "message" => "Unauthorized"]);
//     exit;
// }

// Load Groq API Key dynamically using shared environment loader
require_once dirname(__DIR__) . '/includes/env_loader.php';
$GROQ_API_KEY = getGroqApiKey();

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

// ---------------------------------------------------------
// STEP 1: Transcription using Groq API (Alternative to OpenAI)
// ---------------------------------------------------------
// Grok does not currently offer a Speech-to-Text service, so we use Groq's Whisper as the STT engine.
$cfile = new CURLFile($filePath, mime_content_type($filePath), $fileName);
$postData = array(
    "file" => $cfile,
    "model" => "whisper-large-v3-turbo",
    "prompt" => "The speaker is attending a technical software engineering interview in India. Common technical words and university names: B.Tech, KIIT, AI, ML, Python, React, JavaScript."
);

$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => "https://api.groq.com/openai/v1/audio/transcriptions",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $postData,
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $GROQ_API_KEY"
    ),
));
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$transcriptionData = json_decode($response, true);

if ($httpcode !== 200 || !isset($transcriptionData['text'])) {
    http_response_code(500);
    $errorMsg = isset($transcriptionData['error']['message']) ? $transcriptionData['error']['message'] : "Transcription failed.";
    echo json_encode(["status" => "error", "message" => "Transcription API Error: " . $errorMsg]);
    exit;
}

$transcript = $transcriptionData['text'];

// ---------------------------------------------------------
// STEP 2: Generate Evaluation using Groq LLM API
// ---------------------------------------------------------
$prompt = "You are an expert technical recruiter and hiring manager. Provide a brief, concise executive summary of the following interview transcript using bullet points. Please intelligently auto-correct obvious speech-to-text transcription errors (for example, interpreting 'B.T.E.' as 'B.Tech', or 'KIT' as 'KIIT'). At the very end of your response, you MUST clearly state the final hiring decision as exactly '**VERDICT: SELECTED**' or '**VERDICT: REJECTED**', along with a one-sentence justification. Be decisive based on the candidate's clarity, skills, and relevance to the role.\n\nTranscript:\n" . $transcript;

$llmData = json_encode([
    "model" => "llama-3.3-70b-versatile", // Using Llama 3.3 on Groq
    "messages" => [
        [
            "role" => "system",
            "content" => "You are a helpful and precise HR assistant."
        ],
        [
            "role" => "user",
            "content" => $prompt
        ]
    ]
]);

$ch2 = curl_init();
curl_setopt_array($ch2, array(
    CURLOPT_URL => "https://api.groq.com/openai/v1/chat/completions",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $llmData,
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer $GROQ_API_KEY",
        "Content-Type: application/json"
    ),
));
$response2 = curl_exec($ch2);
$httpcode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);

$summaryData = json_decode($response2, true);

if ($httpcode2 !== 200 || !isset($summaryData['choices'][0]['message']['content'])) {
    http_response_code(500);
    $errorMsg = isset($summaryData['error']['message']) ? $summaryData['error']['message'] : "Raw Response: " . $response2;
    echo json_encode(["status" => "error", "message" => "Groq API Error: " . $errorMsg]);
    exit;
}

$summary = $summaryData['choices'][0]['message']['content'];



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
