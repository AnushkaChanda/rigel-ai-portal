<?php
session_start();
require_once '../includes/db_connect.php';

// Check if user is admin
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['account_type'], ['admin', 'superadmin'])) {
    header("Location: dashboard.php");
    exit;
}

$action = $_GET['action'] ?? 'list';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_internship'])) {
        $slug = trim($_POST['slug']);
        $title = trim($_POST['title']);
        $short_desc = trim($_POST['short_description']);
        $desc = trim($_POST['description']);
        $duration = trim($_POST['duration']);
        $credits = trim($_POST['credits']);
        $focus_title = trim($_POST['focus_title']);
        $focus = trim($_POST['focus']);
        $link = trim($_POST['link']);
        $icon_svg = trim($_POST['icon_svg']);
        $icon_bg = trim($_POST['icon_bg']);
        $contact_email = trim($_POST['contact_email'] ?? '');
        
        // Handle JSON fields (split by newline and encode)
        $learning_outcomes = json_encode(array_filter(array_map('trim', explode("\n", $_POST['learning_outcomes']))));
        $perks = json_encode(array_filter(array_map('trim', explode("\n", $_POST['perks']))));
        $fees = json_encode(array_filter(array_map('trim', explode("\n", $_POST['fees']))));
        
        try {
            $stmt = $pdo->prepare("INSERT INTO internships (slug, title, short_description, description, duration, credits, focus_title, focus, learning_outcomes, perks, fees, link, icon_svg, icon_bg, contact_email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$slug, $title, $short_desc, $desc, $duration, $credits, $focus_title, $focus, $learning_outcomes, $perks, $fees, $link, $icon_svg, $icon_bg, $contact_email]);
            $success = "Internship added successfully.";
            $action = 'list';
        } catch (PDOException $e) {
            $error = "Error adding internship: " . $e->getMessage();
        }
    } elseif (isset($_POST['edit_internship'])) {
        $id = $_POST['id'];
        $slug = trim($_POST['slug']);
        $title = trim($_POST['title']);
        $short_desc = trim($_POST['short_description']);
        $desc = trim($_POST['description']);
        $duration = trim($_POST['duration']);
        $credits = trim($_POST['credits']);
        $focus_title = trim($_POST['focus_title']);
        $focus = trim($_POST['focus']);
        $link = trim($_POST['link']);
        $icon_svg = trim($_POST['icon_svg']);
        $icon_bg = trim($_POST['icon_bg']);
        $contact_email = trim($_POST['contact_email'] ?? '');
        
        $learning_outcomes = json_encode(array_filter(array_map('trim', explode("\n", $_POST['learning_outcomes']))));
        $perks = json_encode(array_filter(array_map('trim', explode("\n", $_POST['perks']))));
        $fees = json_encode(array_filter(array_map('trim', explode("\n", $_POST['fees']))));
        
        try {
            $stmt = $pdo->prepare("UPDATE internships SET slug=?, title=?, short_description=?, description=?, duration=?, credits=?, focus_title=?, focus=?, learning_outcomes=?, perks=?, fees=?, link=?, icon_svg=?, icon_bg=?, contact_email=? WHERE id=?");
            $stmt->execute([$slug, $title, $short_desc, $desc, $duration, $credits, $focus_title, $focus, $learning_outcomes, $perks, $fees, $link, $icon_svg, $icon_bg, $contact_email, $id]);
            $success = "Internship updated successfully.";
            $action = 'list';
        } catch (PDOException $e) {
            $error = "Error updating internship: " . $e->getMessage();
        }
    } elseif (isset($_POST['delete_internship'])) {
        $id = $_POST['id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM internships WHERE id=?");
            $stmt->execute([$id]);
            $success = "Internship deleted successfully.";
            $action = 'list';
        } catch (PDOException $e) {
            $error = "Error deleting internship: " . $e->getMessage();
        }
    }
}

if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM internships WHERE id=?");
    $stmt->execute([$_GET['id']]);
    $internship = $stmt->fetch();
    if (!$internship) $action = 'list';
}

include '../includes/header.php';
?>

<main style="padding: 100px 20px 40px; max-width: 1200px; margin: 0 auto; min-height: 80vh;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h1 style="color: var(--primary-blue); font-size: 2.5rem;">Manage Internships</h1>
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

    <?php if ($action === 'list'): ?>
        <div style="margin-bottom: 2rem;">
            <a href="?action=add" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New Internship</a>
        </div>
        
        <div style="background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow-md);">
            <table style="width: 100%; border-collapse: collapse; color: var(--text-dark);">
                <thead>
                    <tr style="background: rgba(255,255,255,0.05); border-bottom: 1px solid var(--border-light);">
                        <th style="padding: 1rem; text-align: left;">ID</th>
                        <th style="padding: 1rem; text-align: left;">Title</th>
                        <th style="padding: 1rem; text-align: left;">Slug</th>
                        <th style="padding: 1rem; text-align: left;">Duration</th>
                        <th style="padding: 1rem; text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM internships ORDER BY id DESC");
                    while($row = $stmt->fetch()):
                    ?>
                    <tr style="border-bottom: 1px solid var(--border-light);">
                        <td style="padding: 1rem;"><?php echo $row['id']; ?></td>
                        <td style="padding: 1rem; font-weight: 500;"><?php echo htmlspecialchars($row['title']); ?></td>
                        <td style="padding: 1rem; color: var(--text-muted);"><?php echo htmlspecialchars($row['slug']); ?></td>
                        <td style="padding: 1rem;"><?php echo htmlspecialchars($row['duration']); ?></td>
                        <td style="padding: 1rem; text-align: center;">
                            <a href="?action=edit&id=<?php echo $row['id']; ?>" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; margin-right: 0.5rem;"><i class="fa-solid fa-pen"></i> Edit</a>
                            <form action="" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this internship?');">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="delete_internship" class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.85rem; border-color: #ff4d6d; color: #ff4d6d;"><i class="fa-solid fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php elseif ($action === 'add' || $action === 'edit'): ?>
        <div style="background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); border-radius: 16px; padding: 2rem; box-shadow: var(--shadow-md);">
            <h2 style="margin-bottom: 1.5rem; color: var(--text-dark);"><?php echo $action === 'add' ? 'Add New Internship' : 'Edit Internship'; ?></h2>
            <form action="" method="POST">
                <?php if($action === 'edit'): ?>
                    <input type="hidden" name="id" value="<?php echo $internship['id']; ?>">
                <?php endif; ?>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Title *</label>
                        <input type="text" name="title" required value="<?php echo $action === 'edit' ? htmlspecialchars($internship['title']) : ''; ?>" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Slug (URL friendly) *</label>
                        <input type="text" name="slug" required value="<?php echo $action === 'edit' ? htmlspecialchars($internship['slug']) : ''; ?>" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;">
                    </div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Short Description (For Dashboard) *</label>
                    <textarea name="short_description" required rows="3" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;"><?php echo $action === 'edit' ? htmlspecialchars($internship['short_description']) : ''; ?></textarea>
                </div>
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Full Description (For Details Page) *</label>
                    <textarea name="description" required rows="5" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;"><?php echo $action === 'edit' ? htmlspecialchars($internship['description']) : ''; ?></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Duration</label>
                        <input type="text" name="duration" value="<?php echo $action === 'edit' ? htmlspecialchars($internship['duration']) : ''; ?>" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Credits</label>
                        <input type="text" name="credits" value="<?php echo $action === 'edit' ? htmlspecialchars($internship['credits']) : ''; ?>" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Focus Title (e.g. Focus, Mode, Skills)</label>
                        <input type="text" name="focus_title" value="<?php echo $action === 'edit' ? htmlspecialchars($internship['focus_title']) : 'Focus'; ?>" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Focus Value (e.g. Fund Management, PR)</label>
                        <input type="text" name="focus" value="<?php echo $action === 'edit' ? htmlspecialchars($internship['focus']) : ''; ?>" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Learning Outcomes (One per line)</label>
                        <textarea name="learning_outcomes" rows="6" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;"><?php 
                            if($action === 'edit' && $internship['learning_outcomes']) {
                                $arr = json_decode($internship['learning_outcomes'], true);
                                if(is_array($arr)) echo htmlspecialchars(implode("\n", $arr));
                            }
                        ?></textarea>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Perks (One per line)</label>
                        <textarea name="perks" rows="6" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;"><?php 
                            if($action === 'edit' && $internship['perks']) {
                                $arr = json_decode($internship['perks'], true);
                                if(is_array($arr)) echo htmlspecialchars(implode("\n", $arr));
                            }
                        ?></textarea>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Fees (One per line)</label>
                        <textarea name="fees" rows="6" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;"><?php 
                            if($action === 'edit' && $internship['fees']) {
                                $arr = json_decode($internship['fees'], true);
                                if(is_array($arr)) echo htmlspecialchars(implode("\n", $arr));
                            }
                        ?></textarea>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Application Link</label>
                        <input type="text" name="link" value="<?php echo $action === 'edit' ? htmlspecialchars($internship['link']) : ''; ?>" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Contact Email (For Custom Internships)</label>
                        <input type="email" name="contact_email" value="<?php echo $action === 'edit' ? htmlspecialchars($internship['contact_email'] ?? '') : ''; ?>" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;" placeholder="e.g. xyz@gmail.com">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Icon SVG (HTML code)</label>
                        <input type="text" name="icon_svg" value="<?php echo $action === 'edit' ? htmlspecialchars($internship['icon_svg']) : ''; ?>" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;" placeholder='<svg>...</svg>'>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; color: var(--text-muted);">Icon Background (CSS gradient/color)</label>
                        <input type="text" name="icon_bg" value="<?php echo $action === 'edit' ? htmlspecialchars($internship['icon_bg']) : 'linear-gradient(135deg, #38b6ff, #004aad)'; ?>" style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: rgba(0,0,0,0.2); color: white;">
                    </div>
                </div>

                <div>
                    <button type="submit" name="<?php echo $action === 'add' ? 'add_internship' : 'edit_internship'; ?>" class="btn btn-primary" style="padding: 0.8rem 2rem; font-size: 1rem;"><i class="fa-solid fa-save"></i> Save Internship</button>
                    <a href="?action=list" class="btn btn-outline" style="margin-left: 1rem;">Cancel</a>
                </div>
            </form>
        </div>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>
