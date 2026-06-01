<?php
session_start();
require_once '../includes/db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $error = "Please enter your email address.";
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id, full_name FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Generate token
            $token = bin2hex(random_bytes(16));

            // Store token in database with 1 hour expiration using MySQL NOW() to prevent timezone mismatches
            $update = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?");
            $update->execute([$token, $user['id']]);

            // Construct reset link
            // Assuming local development uses localhost:8000
            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
            $host = $_SERVER['HTTP_HOST'] ?: 'localhost:8000';
            $reset_link = "$protocol://$host/pages/reset_password.php?token=$token";

            // Send Email using PHPMailer
            require '../includes/PHPMailer/Exception.php';
            require '../includes/PHPMailer/PHPMailer.php';
            require '../includes/PHPMailer/SMTP.php';

            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'chandaanushka25@gmail.com'; // Admin Gmail
                $mail->Password   = 'qsab xkej kowp rrtj';   // App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;
                $mail->setFrom('no-reply@rigelfoundation.org.in', 'Rigel Foundation');

                // Recipients
                $mail->addAddress($email, $user['full_name']);

                // Content
                $mail->isHTML(true);
                $mail->Subject = "Password Reset Request - Rigel Foundation";
                $mail->Body    = "
                <html>
                <head><title>Password Reset</title></head>
                <body>
                <h2>Hello {$user['full_name']},</h2>
                <p>We received a request to reset your password for your Rigel Foundation account.</p>
                <p>Click the link below to set a new password. This link will expire in 1 hour.</p>
                <p><a href='{$reset_link}' style='display:inline-block;padding:10px 15px;background-color:#38b6ff;color:#fff;text-decoration:none;border-radius:5px;'>Reset Password</a></p>
                <br>
                <p>If you did not request a password reset, please ignore this email or contact support if you have concerns.</p>
                <br>
                <p>Best regards,<br>Rigel Foundation Team</p>
                </body>
                </html>";

                $mail->send();
                $success = "A password reset link has been sent to your email address.";
            } catch (Exception $e) {
                $error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            // For security, don't reveal if email doesn't exist, just show success message
            $success = "If that email address is in our database, we will send you an email to reset your password.";
        }
    }
}
?>
<?php include '../includes/header.php'; ?>

<main class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h2>Forgot Password</h2>
            <p>Enter your email address to receive a password reset link.</p>
        </div>
        
        <?php if($error): ?>
            <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #ef4444;"><i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success" style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #10b981;"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($success); ?></div>
        <?php else: ?>
            <form action="forgot_password.php" method="POST">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
            </form>
        <?php endif; ?>
        
        <div class="auth-footer" style="margin-top: 1.5rem; text-align: center;">
            Remembered your password? <a href="login.php" style="color: var(--primary-color); text-decoration: none;">Log In</a>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
