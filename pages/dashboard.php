<?php
session_start();
// Shield the route
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<?php include '../includes/db_connect.php'; ?>
<?php include '../includes/header.php'; ?>

<div style="display: flex; justify-content: flex-end; gap: 1rem; padding: 1rem 2rem; max-width: 1200px; margin: 0 auto; width: 100%;">
    <a href="profile.php" class="btn btn-outline" style="border-color: #38b6ff; color: #38b6ff; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; border-radius: 8px; padding: 0.5rem 1rem; transition: background-color 0.3s, color 0.3s;" onmouseover="this.style.backgroundColor='#38b6ff'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#38b6ff';">
        <i class="fa-solid fa-user"></i> Profile Settings
    </a>
    <a href="logout.php" class="btn btn-outline" style="border-color: #ff4d6d; color: #ff4d6d; text-decoration: none; display: flex; align-items: center; gap: 0.5rem; border-radius: 8px; padding: 0.5rem 1rem; transition: background-color 0.3s, color 0.3s;" onmouseover="this.style.backgroundColor='#ff4d6d'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#ff4d6d';">
        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
    </a>
</div>

<?php
// Route based on account type
if (isset($_SESSION['account_type']) && $_SESSION['account_type'] === 'admin') {
    include 'admin_dashboard.php';
} else {
    include 'user_dashboard.php';
}
?>

<?php include '../includes/footer.php'; ?>
