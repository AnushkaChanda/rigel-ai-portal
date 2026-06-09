<?php 
require_once 'includes/db_connect.php';
include 'includes/header.php'; 
?>

<!-- ========== HERO: Left text + Right brochure ========== -->
<section class="hero-split">
    <div class="hero-text-panel fade-in-up">
        <div class="hero-text-panel-content">
            <div class="hero-decorative-line"></div>
            <h1 class="hero-title">
                <span class="hero-title-main">Rigel</span>
                <span class="hero-title-accent">Career Portal</span>
            </h1>
            <p class="hero-tagline">Empowering Futures Through AI-Driven Innovation</p>
            <div class="hero-divider"></div>
            <p class="hero-org-info">A Government of India Registered Non-Profit Organisation<br>Section 8 &bull; Licence No. 151249</p>
            <a href="#internships" class="btn btn-primary btn-large hero-cta">
                Explore Programs <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="hero-image-panel">
        <div class="hero-image-panel-overlay"></div>
        <img src="images/untitled_brochure.png" alt="Rigel Foundation — A Government of India Registered Non-Profit Organisation, Section 8, Licence No. 151249">
    </div>
</section>

<!-- ========== INTERNSHIPS ========== -->
<section class="section-container" id="internships">
    <div class="section-header fade-in-up">
        <div class="section-divider"></div>
        <h2>Flagship Internship Programs</h2>
        <p>Choose from our certified programs designed to provide real-world exposure, mentorship, and professional experience.</p>
    </div>

    <div class="internships-grid">
        <?php
        $colors = ['red', 'blue', 'green', 'purple'];
        $colorIndex = 0;
        try {
            $stmt = $pdo->query("SELECT * FROM internships ORDER BY id ASC LIMIT 4");
            while ($internship = $stmt->fetch()) {
                $color = $colors[$colorIndex % 4];
                $colorIndex++;
        ?>
        <div class="internship-card fade-in-up stagger-<?php echo $colorIndex; ?>" data-color="<?php echo $color; ?>">
            <div class="card-icon" style="background: <?php echo htmlspecialchars($internship['icon_bg'] ?: 'linear-gradient(135deg, #38b6ff, #004aad)'); ?>;">
                <?php echo $internship['icon_svg'] ?: '<i class="fa-solid fa-briefcase"></i>'; ?>
            </div>
            <h3><?php echo htmlspecialchars($internship['title']); ?></h3>
            <p class="card-desc"><?php echo htmlspecialchars($internship['short_description']); ?></p>
            <div class="card-meta">
                <span class="card-meta-item"><i class="fa-regular fa-clock"></i> <?php echo htmlspecialchars($internship['duration']); ?></span>
                <span class="card-meta-item"><i class="fa-solid fa-award"></i> <?php echo htmlspecialchars($internship['credits']); ?></span>
                <span class="card-meta-item"><i class="fa-solid fa-bullseye"></i> <?php echo htmlspecialchars($internship['focus']); ?></span>
            </div>
            <div class="card-actions">
                <a href="pages/internship_details.php?track=<?php echo urlencode($internship['slug']); ?>" class="btn btn-outline">Learn More</a>
                <a href="pages/application_form.php?track=<?php echo urlencode($internship['slug']); ?>" class="btn btn-primary">Apply Now <i class="fa-solid fa-arrow-right" style="font-size:0.75rem;"></i></a>
            </div>
        </div>
        <?php
            }
        } catch (PDOException $e) {
            echo "<p style='color: red; text-align: center;'>Unable to load internships.</p>";
        }
        ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

