<?php
session_start();
require_once '../includes/db_connect.php';

$error = '';
$success = '';
$token_valid = false;
$user_id = null;

// Validate Token via GET or POST
$token = $_GET['token'] ?? $_POST['token'] ?? '';

if (empty($token)) {
    $error = "Invalid or missing password reset token.";
} else {
    // Check if token exists and is not expired
    $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $token_valid = true;
        $user_id = $user['id'];
    } else {
        $error = "This password reset link is invalid or has expired.";
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $token_valid) {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($password) || empty($confirm_password)) {
        $error = "Please fill out all fields.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } else {
        // Hash the new password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Update database and clear the reset token
        $update = $pdo->prepare("UPDATE users SET password_hash = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
        $update->execute([$password_hash, $user_id]);

        $success = "Your password has been successfully reset!";
        $token_valid = false; // Hide the form after success
    }
}
?>
<?php include '../includes/header.php'; ?>

<main class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h2>Reset Password</h2>
            <p>Enter your new password below.</p>
        </div>
        
        <?php if($error): ?>
            <div class="alert alert-danger" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #ef4444;"><i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success" style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border: 1px solid #10b981;"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($success); ?></div>
            <div style="text-align: center; margin-top: 1rem;">
                <a href="login.php" class="btn btn-primary">Go to Login</a>
            </div>
        <?php elseif($token_valid): ?>
            <form action="reset_password.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm new password" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
