<?php
session_start();

header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../includes/PHPMailer/Exception.php';
require '../includes/PHPMailer/PHPMailer.php';
require '../includes/PHPMailer/SMTP.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['summary'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "No summary provided"]);
    exit;
}

$to_admin = "chandaanushka25@gmail.com";
$summary = $data['summary'];

// Attempt to get user's email from database if they are logged in
$user_email = null;
$user_name = "Candidate";
if (isset($_SESSION['user_id'])) {
    require_once '../includes/db_connect.php';
    $stmt = $pdo->prepare("SELECT email, full_name FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user_row = $stmt->fetch();
    if ($user_row) {
        $user_email = $user_row['email'];
        $user_name = $user_row['full_name'];
    }
}

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    
    // ========================================================================
    // TODO: USER NEEDS TO UPDATE THESE TWO LINES
    // ========================================================================
    $mail->Username   = 'chandaanushka25@gmail.com'; // Your Gmail address
    $mail->Password   = 'qsab xkej kowp rrtj';   // Your Gmail App Password (NOT your normal password)
    // ========================================================================
    
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->setFrom('no-reply@rigelfoundation.org.in', 'career|rigel foundation');

    // 1. Send Email to Rigel Foundation Admin
    $mail->addAddress($to_admin);
    $mail->isHTML(true);
    $mail->Subject = "New Interview Completed - Rigel Foundation";
    $mail->Body    = "
    <html>
    <head><title>New Interview Submission</title></head>
    <body>
    <h2>Rigel Foundation AI Interview App</h2>
    <p>A candidate ({$user_name}) has just completed their automated interview.</p>
    <h3>AI Generated Summary:</h3>
    <p>{$summary}</p>
    <hr>
    <p><em>The full video recording is saved securely on the server.</em></p>
    </body>
    </html>";
    $mail->send();

    // 2. Send Email to the User (if logged in and email found)
    if ($user_email) {
        $mail->clearAddresses(); // Clear previous recipients
        $mail->addAddress($user_email);
        $mail->Subject = "Interview Successfully Completed - Rigel Foundation";
        $mail->Body    = "
        <html>
        <head><title>Interview Completed</title></head>
        <body>
        <h2>Thank You, {$user_name}!</h2>
        <p>You have successfully completed your automated AI interview with the Rigel Foundation.</p>
        <p>Our team will review your application and interview shortly. We will reach out to you with the next steps.</p>
        <br>
        <p>Best regards,<br>Rigel Foundation Team</p>
        </body>
        </html>";
        $mail->send();
    }

    echo json_encode(["status" => "success", "message" => "Emails sent successfully"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
}
?>
