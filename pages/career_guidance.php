<?php
session_start();
require_once '../includes/db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$generated_roadmap = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_role = htmlspecialchars($_POST['current_role'] ?? '');
    $target_role = htmlspecialchars($_POST['target_role'] ?? '');
    $skills = htmlspecialchars($_POST['skills'] ?? '');
    $timeframe = htmlspecialchars($_POST['timeframe'] ?? '');

    // Temporary mock data for Phase 5 UI testing (AI integration in Phase 10)
    $generated_roadmap = [
        "title" => "Your Personalized Roadmap to " . $target_role,
        "steps" => [
            [
                "phase" => "Phase 1: Foundation (Weeks 1-4)", 
                "title" => "Skill Gap Analysis & Core Concepts", 
                "desc" => "Since your current role is '$current_role', focus on brushing up core concepts related to $target_role. Enhance your current skills in $skills by taking introductory online courses."
            ],
            [
                "phase" => "Phase 2: Deep Dive (Weeks 5-8)", 
                "title" => "Advanced Concepts & Projects", 
                "desc" => "Build 2-3 mid-level projects to showcase your abilities. Focus on applying $skills in real-world scenarios."
            ],
            [
                "phase" => "Phase 3: Readiness (Weeks 9-12)", 
                "title" => "Interview Prep & Polish", 
                "desc" => "Utilize the Rigel Mock Interview module to refine your behavioral and technical interview skills. Update your resume and start applying."
            ]
        ]
    ];
}
?>
<?php include '../includes/header.php'; ?>

<main class="page-content" style="padding: 6rem 2rem 4rem; min-height: 80vh; background-color: var(--bg-main);">
    <div class="container" style="max-width: 800px; margin: 0 auto;">
        
        <div style="text-align: center; margin-bottom: 3rem;" class="fade-in-up">
            <h2 style="font-size: 2.5rem; margin-bottom: 1rem;"><i class="fa-solid fa-compass" style="color: var(--primary-blue);"></i> AI Career Guidance</h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                Tell us about your background and goals, and our AI will generate a personalized learning roadmap to guide your journey.
            </p>
        </div>

        <?php if (!$generated_roadmap): ?>
        <div style="background: var(--bg-card); padding: 3rem; border-radius: 20px; box-shadow: var(--shadow-md); border: 1px solid var(--border-light);" class="fade-in-up">
            <form method="POST" action="career_guidance.php">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);">Current Status / Role</label>
                    <input type="text" name="current_role" class="form-control" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid var(--border-light); border-radius: 8px; font-family: inherit;" placeholder="e.g., 3rd Year CS Student, Junior Developer" required>
                </div>
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);">Target Role</label>
                    <input type="text" name="target_role" class="form-control" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid var(--border-light); border-radius: 8px; font-family: inherit;" placeholder="e.g., Machine Learning Engineer, Data Analyst" required>
                </div>
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);">Current Core Skills</label>
                    <input type="text" name="skills" class="form-control" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid var(--border-light); border-radius: 8px; font-family: inherit;" placeholder="e.g., Python, SQL, basic Data Structures (comma separated)" required>
                </div>
                
                <div class="form-group" style="margin-bottom: 2rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-dark);">Timeline to Goal</label>
                    <select name="timeframe" class="form-control" style="width: 100%; padding: 0.8rem 1rem; border: 1px solid var(--border-light); border-radius: 8px; font-family: inherit; background-color: white;" required>
                        <option value="3 Months">3 Months</option>
                        <option value="6 Months">6 Months</option>
                        <option value="1 Year">1 Year</option>
                        <option value="More than 1 Year">More than 1 Year</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary btn-large" style="width: 100%; display: flex; justify-content: center; align-items: center; gap: 0.5rem;">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Generate My Roadmap
                </button>
            </form>
        </div>
        <?php else: ?>
        
        <!-- Generated Roadmap UI -->
        <div class="roadmap-result fade-in-up" style="background: var(--bg-card); padding: 3rem; border-radius: 20px; box-shadow: var(--shadow-lg); border: 1px solid var(--border-light);">
            <div style="text-align: center; margin-bottom: 3rem;">
                <span class="badge" style="background: rgba(56, 182, 255, 0.15); color: var(--primary-blue); padding: 0.5rem 1rem; border-radius: 50px; font-weight: 600; font-size: 0.9rem; margin-bottom: 1rem; display: inline-block;">Roadmap Generated</span>
                <h3 style="color: var(--dark-blue); font-size: 2rem;"><i class="fa-solid fa-map-location-dot" style="color: var(--secondary-blue);"></i> <?php echo $generated_roadmap['title']; ?></h3>
            </div>
            
            <div class="timeline" style="border-left: 3px solid var(--border-light); padding-left: 2rem; margin-left: 1rem; position: relative;">
                <?php foreach($generated_roadmap['steps'] as $index => $step): ?>
                <div class="timeline-step" style="position: relative; margin-bottom: 2.5rem;">
                    <!-- Timeline Dot -->
                    <div style="position: absolute; left: -2.6rem; top: 0; background: white; border: 3px solid var(--primary-blue); width: 18px; height: 18px; border-radius: 50%;"></div>
                    
                    <span style="font-weight: 700; color: var(--secondary-blue); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo $step['phase']; ?></span>
                    <h5 style="margin: 0.5rem 0; font-size: 1.25rem; color: var(--dark-blue);"><?php echo $step['title']; ?></h5>
                    <p style="color: var(--text-muted); font-size: 1rem; line-height: 1.6;"><?php echo $step['desc']; ?></p>
                </div>
                <?php endforeach; ?>
                
                <!-- Final Goal Node -->
                <div class="timeline-step" style="position: relative;">
                    <div style="position: absolute; left: -2.7rem; top: 0; background: var(--primary-blue); color: white; width: 22px; height: 22px; border-radius: 50%; display: flex; justify-content: center; align-items: center; box-shadow: var(--shadow-glow);">
                        <i class="fa-solid fa-check" style="font-size: 0.7rem;"></i>
                    </div>
                    <h5 style="margin: 0; font-size: 1.25rem; color: var(--primary-blue);">Goal Reached</h5>
                    <p style="color: var(--text-muted); font-size: 0.95rem;">You are now ready for the <?php echo htmlspecialchars($_POST['target_role']); ?> role!</p>
                </div>
            </div>

            <div style="margin-top: 3.5rem; text-align: center; display: flex; justify-content: center; gap: 1rem;">
                <a href="career_guidance.php" class="btn btn-outline"><i class="fa-solid fa-rotate-right"></i> Generate Another</a>
                <a href="mock_interview.php" class="btn btn-primary"><i class="fa-solid fa-microphone-lines"></i> Start Mock Interview</a>
            </div>
        </div>

        <?php endif; ?>

    </div>
</main>

<?php include '../includes/footer.php'; ?>
