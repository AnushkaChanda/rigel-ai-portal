<?php
// admin_dashboard.php
// This file is included from dashboard.php, so session is already started and db connected.

// Fetch quick stats for the admin
$total_users = 0;
$total_interviews = 0;

try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $total_users = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM interview_sessions");
    $total_interviews = $stmt->fetchColumn();
} catch (PDOException $e) {
    // Log error in a real app
}
?>

<div class="dashboard-container fade-in-up">
    <div class="dashboard-header">
        <h1>Admin Overview</h1>
        <p>Monitor platform usage and manage system configurations.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <i class="fa-solid fa-users icon"></i>
            <h3>Total Users</h3>
            <div class="number"><?php echo number_format($total_users); ?></div>
        </div>
        <div class="stat-card">
            <i class="fa-solid fa-microphone-lines icon"></i>
            <h3>Mock Interviews Completed</h3>
            <div class="number"><?php echo number_format($total_interviews); ?></div>
        </div>
        <div class="stat-card">
            <i class="fa-solid fa-file-invoice icon"></i>
            <h3>Resumes Analyzed</h3>
            <div class="number">--</div> <!-- Placeholder for future expansion -->
        </div>
    </div>

    <div class="action-grid" style="margin-top: 2rem;">
        <div class="action-card">
            <h3>Manage Questions</h3>
            <p>Add or edit the AI preset interview questions.</p>
            <button class="btn btn-primary" onclick="alert('Module coming in Phase 9')">Manage</button>
        </div>
        <div class="action-card">
            <h3>User Management</h3>
            <p>View user progress and manage accounts.</p>
            <button class="btn btn-outline" onclick="alert('Module coming in Phase 9')">View Users</button>
        </div>
    </div>
</div>
