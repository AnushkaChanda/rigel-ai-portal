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

    <div class="action-grid" style="margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
        <div class="action-card" style="padding: 2rem; border-radius: 16px; background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); box-shadow: var(--shadow-sm);">
            <h3 style="margin-bottom: 0.5rem; color: var(--text-dark);">Manage Internships</h3>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem;">Add, edit, or remove custom internship programs.</p>
            <a href="admin_internships.php" class="btn btn-primary" style="display: inline-block;">Manage</a>
        </div>
        <div class="action-card" style="padding: 2rem; border-radius: 16px; background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); box-shadow: var(--shadow-sm);">
            <h3 style="margin-bottom: 0.5rem; color: var(--text-dark);">Manage Questions</h3>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem;">Add or edit the AI preset interview questions.</p>
            <a href="admin_questions.php" class="btn btn-primary" style="display: inline-block;">Manage</a>
        </div>
        <div class="action-card" style="padding: 2rem; border-radius: 16px; background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); box-shadow: var(--shadow-sm);">
            <h3 style="margin-bottom: 0.5rem; color: var(--text-dark);">User Management</h3>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem;">View user progress and manage accounts.</p>
            <a href="admin_users.php" class="btn btn-outline" style="display: inline-block;">View Users</a>
        </div>
        <div class="action-card" style="padding: 2rem; border-radius: 16px; background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); box-shadow: var(--shadow-sm);">
            <h3 style="margin-bottom: 0.5rem; color: var(--text-dark);">Interview Reports</h3>
            <p style="color: var(--text-muted); margin-bottom: 1.5rem; font-size: 0.95rem;">View AI summaries and raw transcripts.</p>
            <a href="reports.php" class="btn btn-primary" style="display: inline-block;">View Reports</a>
        </div>
    </div>
</div>
