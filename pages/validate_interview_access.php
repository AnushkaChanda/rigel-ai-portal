<?php
// Validate interview access based on approved_emails.json and active timeframe
session_start();
header('Content-Type: application/json');

// Set timezone to IST (Asia/Kolkata) as requested
date_default_timezone_set('Asia/Kolkata');

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['email'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Please enter your email address."]);
    exit;
}

$emailInput = trim(strtolower($data['email']));
$jsonPath = '../data/approved_emails.json';

if (!file_exists($jsonPath)) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Configuration error: Approved email database not found."]);
    exit;
}

$approvedEmails = json_decode(file_get_contents($jsonPath), true);
if (!is_array($approvedEmails)) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Configuration error: Failed to parse approved emails."]);
    exit;
}

$foundUser = null;
foreach ($approvedEmails as $entry) {
    if (isset($entry['email']) && strtolower(trim($entry['email'])) === $emailInput) {
        $foundUser = $entry;
        break;
    }
}

if (!$foundUser) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "This email is not registered/authorized for this AI interview."]);
    exit;
}

// Check time frame if specified
$currentTime = time();
$currentDateTimeStr = date('Y-m-d H:i:s');

if (!empty($foundUser['valid_from'])) {
    $validFromTime = strtotime($foundUser['valid_from']);
    if ($validFromTime !== false && $currentTime < $validFromTime) {
        http_response_code(403);
        echo json_encode([
            "status" => "error",
            "message" => "Your interview window has not started yet. It opens on: " . htmlspecialchars($foundUser['valid_from']) . " IST."
        ]);
        exit;
    }
}

if (!empty($foundUser['valid_until'])) {
    $validUntilTime = strtotime($foundUser['valid_until']);
    if ($validUntilTime !== false && $currentTime > $validUntilTime) {
        http_response_code(403);
        echo json_encode([
            "status" => "error",
            "message" => "Your interview window has expired. It closed on: " . htmlspecialchars($foundUser['valid_until']) . " IST."
        ]);
        exit;
    }
}

// Return success with candidate's pre-configured name
echo json_encode([
    "status" => "success",
    "email" => $foundUser['email'],
    "name" => isset($foundUser['name']) ? $foundUser['name'] : ""
]);
exit;
