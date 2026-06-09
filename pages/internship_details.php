<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// No login required — open to all applicants
include '../includes/header.php';
require_once '../includes/db_connect.php';

$track = $_GET['track'] ?? '';

try {
    $stmt = $pdo->prepare("SELECT * FROM internships WHERE slug = ?");
    $stmt->execute([$track]);
    $details = $stmt->fetch();
    
    if (!$details) {
        echo "<script>window.location.href='../index.php';</script>";
        exit;
    }
    
    // Decode JSON fields
    $details['learning'] = json_decode($details['learning_outcomes'], true) ?: [];
    $details['perks'] = json_decode($details['perks'], true) ?: [];
    $details['fees'] = json_decode($details['fees'], true) ?: [];
    $details['desc'] = $details['description'];
} catch (PDOException $e) {
    echo "<script>window.location.href='../index.php';</script>";
    exit;
}
?>

<div class="page-content fade-in-up">
    <a href="../index.php" class="btn btn-outline" style="margin-bottom: 2rem; display: inline-flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-arrow-left" style="font-size:0.8rem;"></i> Back to Home
    </a>
    
    <div class="glass-container">
        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
            <div class="card-icon" style="background: <?php echo htmlspecialchars($details['icon_bg'] ?: 'linear-gradient(135deg, #38b6ff, #004aad)'); ?>; width: 56px; height: 56px; border-radius: 14px; color: white; display: flex; justify-content: center; align-items: center; font-size: 1.3rem; flex-shrink: 0;">
                <?php echo $details['icon_svg'] ?: '<i class="fa-solid fa-briefcase"></i>'; ?>
            </div>
            <div>
                <h1 style="font-size: 2rem; color: #0d2149; margin-bottom: 0.2rem;"><?php echo htmlspecialchars($details['title']); ?></h1>
                <div class="card-meta" style="margin-bottom: 0;">
                    <span class="card-meta-item"><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($details['duration']); ?></span>
                    <span class="card-meta-item"><i class="fa-solid fa-award"></i> <?php echo htmlspecialchars($details['credits']); ?></span>
                    <span class="card-meta-item"><i class="fa-solid fa-bullseye"></i> <?php echo htmlspecialchars($details['focus']); ?></span>
                </div>
            </div>
        </div>

        <p style="font-size: 1.02rem; line-height: 1.8; color: var(--text-secondary); margin-bottom: 2.5rem;"><?php echo htmlspecialchars($details['desc']); ?></p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2.5rem;">
            <div>
                <h3 style="color: #0d2149; margin-bottom: 1rem; font-size: 1.2rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-book-open" style="color: #38b6ff;"></i> Learning Outcomes
                </h3>
                <ul style="list-style-type: none; padding-left: 0;">
                    <?php foreach($details['learning'] as $item): ?>
                        <li style="margin-bottom: 0.7rem; padding-left: 22px; position: relative; color: var(--text-secondary); font-size: 0.92rem;">
                            <i class="fa-solid fa-check" style="position: absolute; left: 0; top: 3px; color: #10b981; font-size: 0.75rem;"></i>
                            <?php echo htmlspecialchars($item); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div>
                <h3 style="color: #0d2149; margin-bottom: 1rem; font-size: 1.2rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-gift" style="color: #f59e0b;"></i> Perks & Benefits
                </h3>
                <ul style="list-style-type: none; padding-left: 0;">
                    <?php foreach($details['perks'] as $item): ?>
                        <li style="margin-bottom: 0.7rem; padding-left: 22px; position: relative; color: var(--text-secondary); font-size: 0.92rem;">
                            <i class="fa-solid fa-star" style="position: absolute; left: 0; top: 3px; color: #f59e0b; font-size: 0.7rem;"></i>
                            <?php echo htmlspecialchars($item); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        
        <!-- Fees & Action -->
        <div style="margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid var(--border-light); text-align: center;">
            <?php 
            $is_additional = ($details['id'] > 4); 
            $contact_mail = !empty($details['contact_email']) ? $details['contact_email'] : 'xyz@gmail.com';
            ?>

            <?php if ($is_additional): ?>
                <h3 style="color: #0d2149; margin-bottom: 1.2rem; font-size: 1.2rem;">Contact Admin to Apply</h3>
                <div style="display: inline-block; text-align: left; background: rgba(56, 182, 255, 0.05); padding: 1.2rem 2rem; border-radius: 12px; border: 1px solid rgba(56, 182, 255, 0.15);">
                    <p style="margin-bottom: 0; font-size: 1rem; color: var(--text-secondary); font-weight: 500;">Mail to <a href="mailto:<?php echo htmlspecialchars($contact_mail); ?>" style="color: #38b6ff; text-decoration: underline;"><?php echo htmlspecialchars($contact_mail); ?></a> for registration and details.</p>
                </div>
            <?php elseif (!empty($details['fees']) && count($details['fees']) > 0): ?>
                <h3 style="color: #0d2149; margin-bottom: 1.2rem; font-size: 1.2rem;">Course Fees</h3>
                <div style="display: inline-block; text-align: left; background: rgba(13, 33, 73, 0.03); padding: 1.2rem 2rem; border-radius: 12px; border: 1px solid var(--border-light);">
                    <?php foreach($details['fees'] as $item): ?>
                        <p style="margin-bottom: 0.4rem; font-size: 1rem; color: var(--text-secondary); font-weight: 500;"><?php echo htmlspecialchars($item); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($details['link'])): ?>
                <div style="margin-top: 2rem;">
                    <a href="application_form.php?track=<?php echo urlencode($details['slug']); ?>" class="btn btn-primary btn-large" style="padding: 0.9rem 2.5rem;">
                        Apply Now <i class="fa-solid fa-arrow-right" style="font-size:0.8rem;"></i>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
