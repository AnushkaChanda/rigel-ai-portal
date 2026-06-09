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
            // Check whitelist
            $stmt_w = $pdo->prepare("SELECT role FROM admin_whitelist WHERE email = ?");
            $stmt_w->execute([$email]);
            $w_row = $stmt_w->fetch();
            if ($w_row) {
                $account_type = $w_row['role'] ?? 'admin';
            }
            
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Rigel AI Portal</title>
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

    <div class="glass-card rounded-2xl w-full max-w-5xl relative z-10 flex flex-col md:flex-row overflow-hidden shadow-2xl" style="padding: 0;">
        
        <!-- Left Side: Banner -->
        <div class="w-full md:w-1/2 relative hidden md:block border-r border-slate-700/50" style="background-image: url('../images/rigel_banner.png'); background-size: cover; background-position: center;">
        </div>

        <!-- Right Side: Register Form -->
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-tr from-blue-500 to-purple-500 rounded-2xl mx-auto flex items-center justify-center mb-4 shadow-lg transform rotate-3">
                    <i class="fa-solid fa-user-plus text-2xl text-white -rotate-3"></i>
                </div>
                <h2 class="text-3xl font-bold mb-2">Create an Account</h2>
                <p class="text-slate-400">Join Rigel to accelerate your career prep</p>
            </div>
            
            <?php if($error): ?>
                <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-4 rounded-xl mb-6 flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-lg"></i> 
                    <span class="text-sm font-medium"><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="bg-green-500/10 border border-green-500/50 text-green-400 p-4 rounded-xl mb-6 flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-lg"></i> 
                    <span class="text-sm font-medium"><?php echo htmlspecialchars($success); ?></span>
                </div>
            <?php else: ?>
                <form action="register.php" method="POST" class="space-y-4">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-slate-300 mb-1.5">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-user text-slate-400"></i>
                            </div>
                            <input type="text" name="full_name" id="full_name" class="input-glass w-full rounded-xl py-2.5 pl-10 pr-4 text-sm" placeholder="e.g. John Doe" required value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-envelope text-slate-400"></i>
                            </div>
                            <input type="email" name="email" id="email" class="input-glass w-full rounded-xl py-2.5 pl-10 pr-4 text-sm" placeholder="Enter your email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        </div>
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-lock text-slate-400"></i>
                            </div>
                            <input type="password" name="password" id="password" class="input-glass w-full rounded-xl py-2.5 pl-10 pr-10 text-sm" placeholder="Create a strong password" required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-slate-400 hover:text-slate-200 transition-colors" onclick="togglePassword()">
                                <i class="fa-solid fa-eye" id="toggleEye"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="account_type" class="block text-sm font-medium text-slate-300 mb-1.5">I am a...</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-briefcase text-slate-400"></i>
                            </div>
                            <select name="account_type" id="account_type" class="input-glass w-full rounded-xl py-2.5 pl-10 pr-4 text-sm appearance-none" required>
                                <option value="student" style="color: black;">Student</option>
                                <option value="fresher" style="color: black;">Fresher</option>
                                <option value="professional" style="color: black;">Professional</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-chevron-down text-slate-400 text-xs"></i>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-gradient w-full text-white font-semibold rounded-xl py-3 mt-4 text-sm shadow-lg">
                        Sign Up <i class="fa-solid fa-arrow-right-to-bracket ml-2 text-xs"></i>
                    </button>
                </form>
            <?php endif; ?>
            
            <div class="mt-6 text-center text-sm text-slate-400">
                Already have an account? <a href="login.php" class="text-blue-400 font-semibold hover:text-blue-300 hover:underline transition-all">Log In</a>
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
