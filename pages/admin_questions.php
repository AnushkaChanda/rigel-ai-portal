<?php
session_start();
require_once '../includes/db_connect.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['account_type'], ['admin', 'superadmin'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_questions'])) {
    $track_slug = trim($_POST['track_slug']);
    // Split by newline, trim, remove empty, json encode
    $questions_array = array_filter(array_map('trim', explode("\n", $_POST['questions'])));
    $questions_json = json_encode(array_values($questions_array));
    
    try {
        // Check if exists
        $stmt = $pdo->prepare("SELECT id FROM interview_questions WHERE track_slug = ?");
        $stmt->execute([$track_slug]);
        if ($stmt->fetch()) {
            $stmt = $pdo->prepare("UPDATE interview_questions SET questions = ? WHERE track_slug = ?");
            $stmt->execute([$questions_json, $track_slug]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO interview_questions (track_slug, questions) VALUES (?, ?)");
            $stmt->execute([$track_slug, $questions_json]);
        }
        $success = "Questions updated successfully for track: " . htmlspecialchars($track_slug);
    } catch (PDOException $e) {
        $error = "Error updating questions: " . $e->getMessage();
    }
}

$selected_track = $_GET['track'] ?? 'default';

// Fetch all internships for the dropdown
$internships = [];
try {
    $internships = $pdo->query("SELECT slug, title FROM internships ORDER BY title ASC")->fetchAll();
} catch (PDOException $e) {}
// Add default option to the beginning
array_unshift($internships, ['slug' => 'default', 'title' => 'Default (Fallback)']);

// Fetch questions for the selected track
$current_questions = '';
try {
    $stmt = $pdo->prepare("SELECT questions FROM interview_questions WHERE track_slug = ?");
    $stmt->execute([$selected_track]);
    $row = $stmt->fetch();
    if ($row && !empty($row['questions'])) {
        $arr = json_decode($row['questions'], true);
        if (is_array($arr)) {
            $current_questions = implode("\n", $arr);
        }
    }
} catch (PDOException $e) {}

include '../includes/header.php';
?>
<main style="padding: 100px 20px 40px; max-width: 1000px; margin: 0 auto; min-height: 80vh;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: var(--primary-blue); font-size: 2.5rem;">Manage Questions</h1>
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

    <div style="background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); border-radius: 16px; padding: 2rem; box-shadow: var(--shadow-md);">
        
        <form action="" method="GET" style="margin-bottom: 2rem; padding-bottom: 2rem; border-bottom: 1px solid var(--border-light);">
            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted); font-weight: 500;">Select Internship Track to Manage:</label>
            <div style="display: flex; gap: 1rem;">
                <select name="track" style="flex-grow: 1; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;" onchange="this.form.submit()">
                    <?php foreach($internships as $internship): ?>
                        <option value="<?php echo htmlspecialchars($internship['slug']); ?>" <?php if($selected_track === $internship['slug']) echo 'selected'; ?> style="color: black;">
                            <?php echo htmlspecialchars($internship['title']); ?> (<?php echo htmlspecialchars($internship['slug']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <form action="?track=<?php echo urlencode($selected_track); ?>" method="POST">
            <input type="hidden" name="track_slug" value="<?php echo htmlspecialchars($selected_track); ?>">
            
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-dark); font-weight: 500;">
                    Interview Questions for "<?php echo htmlspecialchars($selected_track); ?>"
                </label>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">Enter each question on a new line. These questions will be shown one-by-one to the applicant during the AI mock interview.</p>
                
                <textarea name="questions" rows="12" required style="width: 100%; padding: 1rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white; line-height: 1.6; font-size: 1.05rem;" placeholder="e.g. Introduce yourself.&#10;What are your strengths?"><?php echo htmlspecialchars($current_questions); ?></textarea>
            </div>

            <button type="submit" name="update_questions" class="btn btn-primary" style="padding: 0.8rem 2rem; font-size: 1rem;">
                <i class="fa-solid fa-save"></i> Save Questions
            </button>
        </form>

    </div>
</main>
<?php include '../includes/footer.php'; ?>
