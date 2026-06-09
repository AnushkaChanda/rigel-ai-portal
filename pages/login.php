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
            // Check whitelist
            $stmt_w = $pdo->prepare("SELECT role FROM admin_whitelist WHERE email = ?");
            $stmt_w->execute([$email]);
            $w_row = $stmt_w->fetch();
            if ($w_row) {
                $expected_role = $w_row['role'] ?? 'admin';
                if ($user['account_type'] !== $expected_role) {
                    $upd = $pdo->prepare("UPDATE users SET account_type = ? WHERE id = ?");
                    $upd->execute([$expected_role, $user['id']]);
                    $user['account_type'] = $expected_role;
                }
            }

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Rigel AI Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#1e40af',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            color: #f8fafc;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .input-glass {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #f8fafc;
            transition: all 0.3s ease;
        }
        .input-glass:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
            outline: none;
        }
        .btn-gradient {
            background: linear-gradient(to right, #3b82f6, #8b5cf6);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(139, 92, 246, 0.4);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden bg-slate-900">
    <!-- Decorative background blobs -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>

    <div class="glass-card rounded-2xl w-full max-w-md relative z-10 flex flex-col overflow-hidden shadow-2xl" style="padding: 0;">
        
        <!-- Login Form -->
        <div class="w-full p-8 md:p-12 flex flex-col justify-center">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-tr from-blue-500 to-purple-500 rounded-2xl mx-auto flex items-center justify-center mb-4 shadow-lg transform rotate-3">
                    <i class="fa-solid fa-rocket text-2xl text-white -rotate-3"></i>
                </div>
                <h2 class="text-3xl font-bold mb-2">Welcome Back!</h2>
                <p class="text-slate-400">Log in to access the Rigel Career Portal</p>
            </div>
            
            <?php if($error): ?>
                <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-4 rounded-xl mb-6 flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i> 
                    <span class="text-sm font-medium"><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            
            <form action="login.php" method="POST" class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-envelope text-slate-400"></i>
                        </div>
                        <input type="email" name="email" id="email" class="input-glass w-full rounded-xl py-3 pl-10 pr-4 text-sm" placeholder="Enter your email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-sm font-medium text-slate-300">Password</label>
                        <a href="forgot_password.php" class="text-sm text-blue-400 hover:text-blue-300 transition-colors">Forgot Password?</a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-slate-400"></i>
                        </div>
                        <input type="password" name="password" id="password" class="input-glass w-full rounded-xl py-3 pl-10 pr-10 text-sm" placeholder="Enter your password" required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-slate-400 hover:text-slate-200 transition-colors" onclick="togglePassword()">
                            <i class="fa-solid fa-eye" id="toggleEye"></i>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn-gradient w-full text-white font-semibold rounded-xl py-3 mt-6 text-sm shadow-lg">
                    Log In <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                </button>
            </form>
            
            <div class="mt-8 text-center text-sm text-slate-400">
                Don't have an account yet? <a href="register.php" class="text-blue-400 font-semibold hover:text-blue-300 hover:underline transition-all">Sign Up</a>
            </div>
        </div>
    </div>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleEye = document.getElementById('toggleEye');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleEye.classList.remove('fa-eye');
                toggleEye.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleEye.classList.remove('fa-eye-slash');
                toggleEye.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
