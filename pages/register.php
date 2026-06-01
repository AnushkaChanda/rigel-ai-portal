<?php
require_once '../includes/db_connect.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $account_type = $_POST['account_type'] ?? 'student';

    if (empty($name) || empty($email) || empty($password)) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "This email is already registered. Please log in.";
        } else {
            // Hash password and insert
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (full_name, email, password_hash, account_type) VALUES (?, ?, ?, ?)");
            if ($insert->execute([$name, $email, $hashed, $account_type])) {
                $success = "Account created successfully! You can now log in.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>
<?php include '../includes/header.php'; ?>

<main class="auth-page">
    <div class="auth-container">
        <div class="auth-header">
            <h2>Create an Account</h2>
            <p>Join Rigel to accelerate your career prep</p>
        </div>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if($success): ?>
            <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($success); ?></div>
        <?php else: ?>
            <form action="register.php" method="POST">
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" name="full_name" id="full_name" class="form-control" placeholder="e.g. John Doe" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="john@example.com" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Create a strong password" required>
                </div>
                
                <div class="form-group">
                    <label for="account_type">I am a...</label>
                    <select name="account_type" id="account_type" class="form-control" required>
                        <option value="student">Student</option>
                        <option value="fresher">Fresher</option>
                        <option value="professional">Professional</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Sign Up <i class="fa-solid fa-arrow-right-to-bracket ml-2"></i></button>
            </form>
        <?php endif; ?>
        
        <div class="auth-footer">
            Already have an account? <a href="login.php">Log In</a>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
