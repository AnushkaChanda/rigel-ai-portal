<?php
// Saves applicant details to the PHP session so the email system can use them
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    if (!empty($data['full_name'])) $_SESSION['full_name'] = $data['full_name'];
    if (!empty($data['email']))     $_SESSION['user_email'] = $data['email'];
    if (!empty($data['phone']))     $_SESSION['user_phone'] = $data['phone'];
    if (!empty($data['college']))   $_SESSION['user_college'] = $data['college'];
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data received']);
}
?>
