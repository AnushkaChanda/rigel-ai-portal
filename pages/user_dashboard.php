<?php
// user_dashboard.php
// This file is included from dashboard.php, so session is already started and db connected.
?>

<div class="dashboard-container fade-in-up">
    <div class="dashboard-header-modern">
        <div class="header-content">
            <div class="badge-status">
                <div class="pulse-dot"></div>
                Session Active
            </div>
            <h1>Welcome, <span class="highlight"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>!</h1>
            <p>Your account type: <strong><?php echo htmlspecialchars(ucfirst($_SESSION['account_type'])); ?></strong>. Track your progress and jump right back into practice.</p>
        </div>
        <div class="header-illustration">
            <i class="fa-solid fa-user-graduate header-icon"></i>
        </div>
    </div>

    <div class="stats-grid" style="display: none;">
        <!-- Stats hidden as requested -->
    </div>


    
    <div class="section-title" style="margin-top: 4rem;">
        <h2>Rigel Foundation Internship Tracks</h2>
        <div class="title-underline"></div>
        <p style="color: var(--text-muted); margin-top: 1rem;">Choose from our flagship internship programs designed to provide real-world exposure, mentorship, and certified professional experience.</p>
    </div>

    <div class="features-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
        <?php
        try {
            $stmt = $pdo->query("SELECT * FROM internships ORDER BY id ASC");
            while ($internship = $stmt->fetch()) {
        ?>
        <div class="feature-card" style="padding: 2rem; border-radius: 16px; background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); box-shadow: var(--shadow-md); transition: transform 0.3s; display: flex; flex-direction: column;">
            <div class="icon-wrapper" style="width: 50px; height: 50px; background: <?php echo htmlspecialchars($internship['icon_bg'] ?: 'linear-gradient(135deg, #38b6ff, #004aad)'); ?>; border-radius: 12px; color: white; display: flex; justify-content: center; align-items: center; font-size: 1.2rem; margin-bottom: 1.2rem;">
                <?php echo $internship['icon_svg'] ?: '<i class="fa-solid fa-briefcase"></i>'; ?>
            </div>
            <h3 style="font-size: 1.3rem; margin-bottom: 0.8rem; color: var(--text-dark);"><?php echo htmlspecialchars($internship['title']); ?></h3>
            <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 1.2rem; flex-grow: 1;">
                <?php echo htmlspecialchars($internship['short_description']); ?>
            </p>
            <ul style="list-style: none; padding: 0; margin-bottom: 1.2rem; font-size: 0.85rem; color: var(--text-dark);">
                <li style="margin-bottom: 0.4rem;"><strong>Duration:</strong> <?php echo htmlspecialchars($internship['duration']); ?></li>
                <li style="margin-bottom: 0.4rem;"><strong>Credits:</strong> <?php echo htmlspecialchars($internship['credits']); ?></li>
                <li style="margin-bottom: 0.4rem;"><strong><?php echo htmlspecialchars($internship['focus_title']); ?>:</strong> <?php echo htmlspecialchars($internship['focus']); ?></li>
            </ul>
            <a href="internship_details.php?track=<?php echo urlencode($internship['slug']); ?>" class="btn btn-outline" style="width: 100%; border-radius: 8px; font-size: 0.9rem; padding: 0.6rem;">View Full Details</a>
        </div>
        <?php
            }
        } catch (PDOException $e) {
            echo "<p style='color: red;'>Unable to load internships.</p>";
        }
        ?>
    </div>
</div>
