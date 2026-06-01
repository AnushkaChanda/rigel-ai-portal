<?php
session_start();
// Shield the route
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include '../includes/db_connect.php';

$user_id = $_SESSION['user_id'];
$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone_number = trim($_POST['phone_number'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $linkedin_url = trim($_POST['linkedin_url'] ?? '');
    $github_url = trim($_POST['github_url'] ?? '');
    
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    try {
        $update_password = false;
        
        // Handle password change if requested
        if (!empty($new_password) || !empty($current_password)) {
            $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $db_pass = $stmt->fetchColumn();
            
            if (empty($current_password) || !password_verify($current_password, $db_pass)) {
                throw new Exception("Incorrect current password.");
            }
            if ($new_password !== $confirm_password) {
                throw new Exception("New passwords do not match.");
            }
            if (strlen($new_password) < 6) {
                throw new Exception("New password must be at least 6 characters.");
            }
            
            $update_password = true;
        }

        if ($update_password) {
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET phone_number = ?, bio = ?, linkedin_url = ?, github_url = ?, password_hash = ? WHERE id = ?");
            $stmt->execute([$phone_number, $bio, $linkedin_url, $github_url, $new_hash, $user_id]);
            $message = "Profile and password updated successfully!";
        } else {
            $stmt = $pdo->prepare("UPDATE users SET phone_number = ?, bio = ?, linkedin_url = ?, github_url = ? WHERE id = ?");
            $stmt->execute([$phone_number, $bio, $linkedin_url, $github_url, $user_id]);
            $message = "Profile updated successfully!";
        }
        $messageType = "success";
    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = "error";
    }
}

// Fetch current user data
$stmt = $pdo->prepare("SELECT full_name, email, account_type, phone_number, bio, linkedin_url, github_url FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="dashboard-container fade-in-up" style="max-width: 800px; margin: 2rem auto; padding: 0 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 style="color: var(--text-dark);">Profile Settings</h2>
        <a href="dashboard.php" class="btn btn-outline" style="border-radius: 8px; padding: 0.5rem 1rem;"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <?php if ($message): ?>
        <div style="padding: 1rem; border-radius: 8px; margin-bottom: 2rem; background: <?php echo $messageType === 'success' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)'; ?>; color: <?php echo $messageType === 'success' ? '#10b981' : '#ef4444'; ?>; border: 1px solid <?php echo $messageType === 'success' ? '#10b981' : '#ef4444'; ?>;">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div style="background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); border-radius: 16px; padding: 2rem; box-shadow: var(--shadow-md);">
        <form method="POST" action="profile.php">
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-dark); font-weight: 500;">Full Name (Read Only)</label>
                <input type="text" value="<?php echo htmlspecialchars($user['full_name']); ?>" readonly style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.05); color: var(--text-muted); cursor: not-allowed;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-dark); font-weight: 500;">Email (Read Only)</label>
                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.05); color: var(--text-muted); cursor: not-allowed;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="phone_number" style="display: block; margin-bottom: 0.5rem; color: var(--text-dark); font-weight: 500;">Phone Number</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>" placeholder="Enter your phone number" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: var(--bg-card); color: var(--text-dark);">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="bio" style="display: block; margin-bottom: 0.5rem; color: var(--text-dark); font-weight: 500;">Bio / Summary</label>
                <textarea id="bio" name="bio" rows="4" placeholder="Tell us a little about yourself..." style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: var(--bg-card); color: var(--text-dark); resize: vertical;"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="linkedin_url" style="display: block; margin-bottom: 0.5rem; color: var(--text-dark); font-weight: 500;">LinkedIn URL</label>
                <input type="url" id="linkedin_url" name="linkedin_url" value="<?php echo htmlspecialchars($user['linkedin_url'] ?? ''); ?>" placeholder="https://linkedin.com/in/yourprofile" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: var(--bg-card); color: var(--text-dark);">
            </div>

            <div style="margin-bottom: 2rem;">
                <label for="github_url" style="display: block; margin-bottom: 0.5rem; color: var(--text-dark); font-weight: 500;">GitHub URL</label>
                <input type="url" id="github_url" name="github_url" value="<?php echo htmlspecialchars($user['github_url'] ?? ''); ?>" placeholder="https://github.com/yourusername" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: var(--bg-card); color: var(--text-dark);">
            </div>
            
            <hr style="margin: 2rem 0; border: none; border-top: 1px solid var(--border-light);">
            <h3 style="margin-bottom: 1.5rem; color: var(--text-dark);">Change Password</h3>

            <div style="margin-bottom: 1.5rem;">
                <label for="current_password" style="display: block; margin-bottom: 0.5rem; color: var(--text-dark); font-weight: 500;">Current Password</label>
                <input type="password" id="current_password" name="current_password" placeholder="Leave blank to keep current password" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: var(--bg-card); color: var(--text-dark);">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label for="new_password" style="display: block; margin-bottom: 0.5rem; color: var(--text-dark); font-weight: 500;">New Password</label>
                <input type="password" id="new_password" name="new_password" placeholder="New Password" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: var(--bg-card); color: var(--text-dark);">
            </div>

            <div style="margin-bottom: 2rem;">
                <label for="confirm_password" style="display: block; margin-bottom: 0.5rem; color: var(--text-dark); font-weight: 500;">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: var(--bg-card); color: var(--text-dark);">
            </div>

            <button type="submit" class="btn" style="width: 100%; padding: 1rem; border-radius: 8px; font-size: 1rem; background: linear-gradient(135deg, #38b6ff, #004aad); color: white; border: none; cursor: pointer; font-weight: 600; box-shadow: 0 4px 15px rgba(56, 182, 255, 0.3); transition: transform 0.3s, box-shadow 0.3s;" onmouseover="this.style.transform='translateY(-2px)';" onmouseout="this.style.transform='translateY(0)';">
                Save Profile Changes
            </button>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
