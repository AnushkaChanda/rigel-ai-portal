<?php
session_start();
// Admin check
if (!isset($_SESSION['user_id']) || $_SESSION['account_type'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

require_once '../includes/db_connect.php';
include '../includes/header.php';

try {
    // If the table doesn't exist yet, this will throw an error, which we catch gracefully.
    $stmt = $pdo->query("SELECT * FROM interviews ORDER BY created_at DESC");
    $interviews = $stmt->fetchAll();
} catch (PDOException $e) {
    $interviews = [];
    $db_error = "No interviews found or database not initialized.";
}
?>

<main class="page-content" style="padding: 8rem 2rem 4rem; min-height: 80vh; background-color: var(--bg-main);">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        
        <div style="text-align: center; margin-bottom: 3rem;" class="fade-in-up">
            <h2 style="font-size: 2.5rem; margin-bottom: 0.5rem;"><i class="fa-solid fa-folder-open" style="color: var(--primary-blue);"></i> Interview Reports</h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                Admin Dashboard: View all AI-analyzed candidate interviews.
            </p>
        </div>

        <?php if (isset($db_error)): ?>
            <div style="background: rgba(220, 38, 38, 0.1); border: 1px solid rgba(220, 38, 38, 0.3); color: #dc2626; padding: 1.5rem; border-radius: 12px; text-align: center; margin-bottom: 2rem;">
                <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($db_error); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($interviews) && !isset($db_error)): ?>
            <div style="text-align: center; padding: 4rem; background: var(--bg-card); border-radius: 16px; border: 1px solid var(--glass-border);">
                <i class="fa-solid fa-inbox" style="font-size: 4rem; color: var(--text-muted); margin-bottom: 1rem; opacity: 0.5;"></i>
                <h3 style="color: var(--text-dark);">No Reports Yet</h3>
                <p style="color: var(--text-muted);">As candidates complete their mock interviews, their AI summaries will appear here.</p>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2rem;">
                <?php foreach ($interviews as $interview): ?>
                    <div class="glass-card fade-in-up" style="position: relative; display: flex; flex-direction: column; align-items: flex-start; padding: 2rem; border-radius: 16px; background: var(--bg-card); border: 1px solid var(--glass-border); box-shadow: var(--shadow-sm); transition: transform 0.3s;">
                        <div style="display: flex; justify-content: space-between; width: 100%; margin-bottom: 1rem; border-bottom: 1px solid var(--border-light); padding-bottom: 1rem;">
                            <span style="font-weight: 600; color: var(--primary-blue);"><i class="fa-solid fa-user-circle"></i> Candidate #<?php echo $interview['id']; ?></span>
                            <span style="font-size: 0.85rem; color: var(--text-muted);"><i class="fa-regular fa-clock"></i> <?php echo date('M j, Y g:i A', strtotime($interview['created_at'])); ?></span>
                        </div>
                        
                        <div style="margin-bottom: 1.5rem; width: 100%; background: var(--bg-main); border-radius: 14px; border: 1px solid var(--border-light); overflow: hidden; box-shadow: var(--shadow-sm);">
                            <div style="background: linear-gradient(90deg, rgba(56, 182, 255, 0.1), transparent); padding: 1rem 1.5rem; border-bottom: 1px solid var(--border-light); display: flex; align-items: center; gap: 0.8rem;">
                                <div style="background: linear-gradient(135deg, #004aad, #38b6ff); width: 32px; height: 32px; border-radius: 8px; display: flex; justify-content: center; align-items: center; box-shadow: 0 4px 10px rgba(56, 182, 255, 0.3);">
                                    <i class="fa-solid fa-wand-magic-sparkles" style="color: white; font-size: 0.9rem;"></i>
                                </div>
                                <h4 style="font-size: 1.1rem; color: var(--text-dark); margin: 0; font-weight: 700; letter-spacing: 0.5px;">AI Summary Report</h4>
                            </div>
                            <div style="padding: 1.5rem; color: var(--text-light-shade); font-size: 0.95rem; line-height: 1.8; position: relative;">
                                <div style="position: relative; z-index: 1; padding-left: 1rem; border-left: 3px solid var(--primary-blue);">
                                    <?php echo nl2br(htmlspecialchars($interview['summary'])); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div style="width: 100%; margin-top: auto;">
                            <details style="background: rgba(255, 255, 255, 0.02); padding: 1.2rem; border-radius: 14px; border: 1px solid var(--border-light); cursor: pointer; transition: all 0.3s ease; box-shadow: var(--shadow-sm);">
                                <summary style="color: var(--text-dark); font-size: 0.95rem; font-weight: 600; outline: none; list-style: none; display: flex; align-items: center; gap: 10px; transition: color 0.3s;">
                                    <div style="background: rgba(255, 77, 109, 0.1); color: #ff4d6d; width: 30px; height: 30px; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                                        <i class="fa-solid fa-file-waveform"></i>
                                    </div>
                                    <span style="flex-grow: 1;">View Full Transcript</span>
                                    <i class="fa-solid fa-chevron-down" style="font-size: 0.8rem; color: var(--text-muted);"></i>
                                </summary>
                                <div style="margin-top: 1.2rem; padding: 1.2rem; background: var(--bg-main); border-radius: 10px; border: 1px solid var(--border-light); font-size: 0.9rem; color: var(--text-muted); line-height: 1.7; max-height: 250px; overflow-y: auto; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                                    <div style="font-family: 'Consolas', 'Courier New', monospace; font-size: 0.85rem;">
                                        <?php echo nl2br(htmlspecialchars($interview['transcript'])); ?>
                                    </div>
                                </div>
                            </details>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
    </div>
</main>

<style>
.glass-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
    border-color: rgba(56, 182, 255, 0.3);
}
</style>

<?php include '../includes/footer.php'; ?>
