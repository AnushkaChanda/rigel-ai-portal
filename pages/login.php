<?php
session_start();
require_once '../includes/db_connect.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = "Please enter your email and password.";
    } else {
        $stmt = $pdo->prepare("SELECT id, full_name, password_hash, account_type FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Setup Session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['account_type'] = $user['account_type'];
            
            // Update last_login timestamp
            $upd = $pdo->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
            $upd->execute([$user['id']]);

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<?php include '../includes/header.php'; ?>

<main class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h2>Welcome Back!</h2>
            <p>Log in to access your dashboard</p>
        </div>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                <div style="text-align: right; margin-top: 0.5rem; font-size: 0.9rem;">
                    <a href="forgot_password.php" style="color: var(--primary-color); text-decoration: none;">Forgot Password?</a>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block">Log In</button>
        </form>
        
        <div class="auth-footer">
            Don't have an account yet? <a href="register.php">Sign Up</a>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
