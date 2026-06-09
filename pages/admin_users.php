<?php
session_start();
require_once '../includes/db_connect.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['account_type'], ['admin', 'superadmin'])) {
    header("Location: dashboard.php");
    exit;
}

$is_superadmin = ($_SESSION['account_type'] === 'superadmin');

$error = '';
$success = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $is_superadmin) {
    if ($_POST['action'] === 'delete') {
        $target_user_id = (int)($_POST['user_id'] ?? 0);
        // Prevent admin from deleting themselves
        if ($target_user_id === $_SESSION['user_id']) {
            $error = "You cannot delete your own account here.";
        } else {
            try {
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
                $stmt->execute([$target_user_id]);
                $success = "User deleted successfully.";
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }
        }
    } elseif ($_POST['action'] === 'add_admin_email') {
        $admin_email = trim($_POST['admin_email'] ?? '');
        if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email address.";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO admin_whitelist (email) VALUES (?)");
                $stmt->execute([$admin_email]);
                $success = "Email added to admin whitelist successfully.";
                
                // Also upgrade existing user if they already exist
                $upd = $pdo->prepare("UPDATE users SET account_type = 'admin' WHERE email = ?");
                $upd->execute([$admin_email]);
            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    $error = "This email is already in the admin whitelist.";
                } else {
                    $error = "Database error: " . $e->getMessage();
                }
            }
        }
    }
}

// Fetch users
$search = $_GET['search'] ?? '';
$query = "SELECT id, full_name, email, account_type, created_at, last_login FROM users";
$params = [];
if (!empty($search)) {
    $query .= " WHERE full_name LIKE ? OR email LIKE ?";
    $params = ["%$search%", "%$search%"];
}
$query .= " ORDER BY created_at DESC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Failed to fetch users: " . $e->getMessage();
    $users = [];
}

include '../includes/header.php';
?>
<main style="padding: 100px 20px 40px; max-width: 1200px; margin: 0 auto; min-height: 80vh;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: var(--primary-blue); font-size: 2.5rem;">User Management</h1>
        <a href="dashboard.php" class="btn btn-outline"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    </div>

    <?php if($error): ?>
        <div style="background: rgba(255, 77, 109, 0.1); border: 1px solid #ff4d6d; color: #ff4d6d; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    
    <?php if($success): ?>
        <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid #10b981; color: #10b981; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <i class="fa-solid fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <?php if ($is_superadmin): ?>
    <div style="background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); border-radius: 16px; padding: 2rem; box-shadow: var(--shadow-md); margin-bottom: 2rem;">
        <h2 style="color: var(--text-dark); margin-bottom: 1rem; font-size: 1.5rem;">Add Admin Access</h2>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Enter a user's email address to grant them Admin privileges. When they sign up or log in, their account will automatically be upgraded.</p>
        <form action="" method="POST" style="display: flex; gap: 1rem;">
            <input type="hidden" name="action" value="add_admin_email">
            <input type="email" name="admin_email" placeholder="e.g. team@rigel.com" required style="flex-grow: 1; padding: 0.8rem 1rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;">
            <button type="submit" class="btn btn-primary" style="padding: 0 1.5rem;"><i class="fa-solid fa-user-plus"></i> Grant Access</button>
        </form>
    </div>
    <?php endif; ?>

    <div style="background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); border-radius: 16px; padding: 2rem; box-shadow: var(--shadow-md);">
        <h2 style="color: var(--text-dark); margin-bottom: 1rem; font-size: 1.5rem;">Registered Users</h2>
        <form action="" method="GET" style="margin-bottom: 2rem; display: flex; gap: 1rem;">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by name or email..." style="flex-grow: 1; padding: 0.8rem 1rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;">
            <button type="submit" class="btn btn-primary" style="padding: 0 1.5rem;"><i class="fa-solid fa-search"></i> Search</button>
            <?php if(!empty($search)): ?>
                <a href="admin_users.php" class="btn btn-outline" style="padding: 0.8rem 1.5rem;">Clear</a>
            <?php endif; ?>
        </form>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; color: var(--text-dark);">
                <thead>
                    <tr style="border-bottom: 2px solid var(--border-light);">
                        <th style="padding: 1rem; text-align: left;">ID</th>
                        <th style="padding: 1rem; text-align: left;">Name</th>
                        <th style="padding: 1rem; text-align: left;">Email</th>
                        <th style="padding: 1rem; text-align: left;">Role</th>
                        <th style="padding: 1rem; text-align: left;">Joined Date</th>
                        <th style="padding: 1rem; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($users) > 0): ?>
                        <?php foreach($users as $user): ?>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td style="padding: 1rem;"><?php echo $user['id']; ?></td>
                                <td style="padding: 1rem; font-weight: 500;"><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td style="padding: 1rem;"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td style="padding: 1rem;">
                                    <?php if($user['account_type'] === 'superadmin'): ?>
                                        <span style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; padding: 0.3rem 0.8rem; border-radius: 50px; font-size: 0.85rem; font-weight: 600;">Super Admin</span>
                                    <?php elseif($user['account_type'] === 'admin'): ?>
                                        <span style="background: rgba(16, 185, 129, 0.1); color: #10b981; padding: 0.3rem 0.8rem; border-radius: 50px; font-size: 0.85rem; font-weight: 600;">Admin</span>
                                    <?php else: ?>
                                        <span style="background: rgba(56, 182, 255, 0.1); color: var(--primary-blue); padding: 0.3rem 0.8rem; border-radius: 50px; font-size: 0.85rem; font-weight: 600;">Student</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 1rem; font-size: 0.9rem; color: var(--text-muted);">
                                    <?php echo date('M j, Y', strtotime($user['created_at'])); ?>
                                </td>
                                <td style="padding: 1rem; text-align: right;">
                                    <?php if ($user['id'] === $_SESSION['user_id']): ?>
                                        <span style="color: var(--text-muted); font-size: 0.9rem; font-style: italic;">Current User</span>
                                    <?php elseif ($is_superadmin && $user['account_type'] !== 'superadmin'): ?>
                                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                            <form action="" method="POST" style="display: inline;">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <button type="submit" class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; background: #dc2626; color: white; border: none;" onclick="return confirm('Are you sure you want to completely delete <?php echo addslashes($user['full_name']); ?>? This action cannot be undone.');">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: var(--text-muted); font-size: 0.9rem; font-style: italic;">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="padding: 2rem; text-align: center; color: var(--text-muted);">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php include '../includes/footer.php'; ?>
