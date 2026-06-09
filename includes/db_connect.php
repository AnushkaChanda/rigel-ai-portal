<?php
require_once __DIR__ . '/env_loader.php';

// Configuration for Database connection
$host = getenv('DB_HOST') ?: null;
$port = getenv('DB_PORT') ?: '3306';
$dbname = getenv('DB_NAME') ?: null;
$username = getenv('DB_USER') ?: null;
$password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : null;

// Fallback to original auto-detection if environment variables are not fully set
if (!$host || !$dbname || !$username) {
    $isLocal = false;
    if (isset($_SERVER['HTTP_HOST'])) {
        if ($_SERVER['HTTP_HOST'] === 'localhost' || 
            $_SERVER['HTTP_HOST'] === '127.0.0.1' || 
            strpos($_SERVER['HTTP_HOST'], 'localhost:') !== false || 
            strpos($_SERVER['HTTP_HOST'], '127.0.0.1:') !== false) {
            $isLocal = true;
        }
    } else {
        // Default to local development when running via CLI
        $isLocal = (php_sapi_name() === 'cli');
    }

    if ($isLocal) {
        // Local development settings
        $host = '127.0.0.1'; 
        $port = '3306';      
        $dbname = 'rigel_db2'; 
        $username = 'root';
        $password = ''; 
    } else {
        // ==========================================
        // HOSTINGER LIVE DATABASE SETTINGS
        // ==========================================
        $host = 'localhost'; // Use localhost when the code is hosted on Hostinger
        $port = '3306';      
        $dbname = 'u795710498_careerai'; 
        $username = 'u795710498_careeruser';  
        $password = 'Rigel@2025';
    }
}


try {
    // Connect to MySQL server WITHOUT specifying the database yet
    $pdo = new PDO("mysql:host=$host;port=$port;charset=utf8", $username, $password);
    
    // Set PDO error mode to exception to enforce strict error catching
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Auto-create the database if you haven't manually created it in phpMyAdmin
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
    
    // Now select the database
    $pdo->exec("USE `$dbname`");
    
    // Auto-create the users table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS `users` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `full_name` VARCHAR(255) NOT NULL,
        `email` VARCHAR(255) UNIQUE NOT NULL,
        `password_hash` VARCHAR(255) NOT NULL,
        `account_type` VARCHAR(50) DEFAULT 'student',
        `last_login` TIMESTAMP NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Safely add last_login to existing tables without breaking if it already exists
    try {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `last_login` TIMESTAMP NULL");
    } catch (PDOException $e) {}
    
    // Safely add profile customization fields
    try {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `phone_number` VARCHAR(20) NULL");
    } catch (PDOException $e) {}
    try {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `bio` TEXT NULL");
    } catch (PDOException $e) {}
    try {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `linkedin_url` VARCHAR(255) NULL");
    } catch (PDOException $e) {}
    try {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `github_url` VARCHAR(255) NULL");
    } catch (PDOException $e) {}
    try {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `profile_picture` VARCHAR(255) NULL");
    } catch (PDOException $e) {}
    
    // Safely add password reset fields
    try {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `reset_token` VARCHAR(64) NULL");
    } catch (PDOException $e) {}
    try {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `reset_expires` DATETIME NULL");
    } catch (PDOException $e) {}
    
    // Auto-create internships table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `internships` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `slug` VARCHAR(100) UNIQUE NOT NULL,
        `title` VARCHAR(255) NOT NULL,
        `short_description` TEXT NOT NULL,
        `description` TEXT NOT NULL,
        `duration` VARCHAR(100),
        `credits` VARCHAR(100),
        `focus_title` VARCHAR(50) DEFAULT 'Focus',
        `focus` VARCHAR(100),
        `learning_outcomes` TEXT,
        `perks` TEXT,
        `fees` TEXT,
        `link` VARCHAR(255),
        `icon_svg` TEXT,
        `icon_bg` VARCHAR(255),
        `contact_email` VARCHAR(255),
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Safely add contact_email to existing internships table without breaking
    try {
        $pdo->exec("ALTER TABLE `internships` ADD COLUMN `contact_email` VARCHAR(255) NULL");
    } catch (PDOException $e) {}
    
    // Check if empty and seed
    $stmt = $pdo->query("SELECT COUNT(*) FROM internships");
    if ($stmt->fetchColumn() == 0) {
        $seed_internships = [
            // SkillSphere
            ['skillsphere', 'SkillSphere Internship', 'A complete hands-on on-ground internship where interns work in teams under mentors to experience NGO execution, PR activities, and real-time troubleshooting.', 'A complete skill-based on-ground hands-on-training of the day-to-day activity of an NGO working for the society. The internship duration is 45 working days giving an intern 45–60 hour working credits (maximum up to 90 hours). The intern will work with his/her fellow teammates to accomplish a team goal (Target) set by the Internship Mentor. Each team will be guided by a Mentor, who will be your go-to person during this duration.', '45 Working Days', '45–60 Hours', 'Focus', 'Fund Management, PR', json_encode(['Group Discussion and Panel-Interview', 'Key Processes of NGO operation', 'NGO hierarchy', 'Fund Allocation and Fund Management', 'Awareness & PR activities for brand building', 'Event Planning & Management', 'Content Marketing & Lead Generation', 'On-ground crisis management and troubleshooting', 'Report writing & presentation']), json_encode(['Internship Kit', 'ID Card', 'Certificate of Completion', 'Letter of Recommendation', 'Certificate of Merit (Outstanding Performances)', 'Food Packets on Certificate Distribution Day', 'Gifts/Stipend (based on performance)', 'Official Merchandise']), json_encode(['Course Fee: ₹399/- (excluding platform fees)', 'Merchandise Fee: ₹149/-', 'No Hidden Charges or extra amounts payable.']), 'https://forms.rigelfoundation.org.in/skillsphere-internship-registration-2026/', '<svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>', 'linear-gradient(135deg, #ff4d6d, #ff758c)'],
            // QuickPro
            ['quickpro', 'QuickPro Internship', 'A fast-paced theoretical internship focused on building strong foundational knowledge of NGO operations, legal compliance, and career readiness.', 'A complete learning and theoretical internship, mainly focussing on building key concepts of working & foundational challenges and developments, with relatively less emphasis on hands-on-training of the day-to-day activity. The program entails experts and senior officials from different fields to join and train the interns with different modules, explaining the on-ground realities at the ease of their study desk. The duration of the program is 21 working days giving an intern 30–45 hour working credits.', '21 Working Days', '30–45 Hours', 'Mode', 'Hybrid Learning', json_encode(['Group Discussion & Panel-Interview', 'Key Processes of NGO operation', 'NGO hierarchy', 'Documentations and Legal Compliances of NGO operation', 'Project Planning, Report building and Funding Opportunities', 'Content Management & use of AI tools', 'Crisis Management & Case-Study', 'Written & Viva Exam']), json_encode(['Internship Kit', 'ID card (optional)', 'Hybrid Mode of Learning', 'Certificate of Completion', 'Letter of Recommendation', 'Certificate of Merit (for outstanding performances)', 'Food packets on Certificate Distribution Day', 'Official Merchandise']), json_encode(['Course Fees: ₹1299/- (excluding platform fees)', 'Merchandise Fees: ₹149/-', 'No Hidden Charges or extra amounts payable.']), 'https://forms.rigelfoundation.org.in/quickpro-internship-registration-2026/', '<svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>', 'linear-gradient(135deg, #38b6ff, #004aad)'],
            // DevSphere
            ['devsphere', 'DevSphere Internship', 'A technical arena for developers and designers to work directly on Rigel’s official platforms, building web solutions, fixing bugs, and improving UI/UX.', 'A developers arena with codes, designs and writing, where interns get to directly work on the official website of the foundation, making the site look vibrant and innovative with their skills on prowess, letting our audience sigh with awe. Brilliance on every click is the ideology that brings our Chief Technical Officer (CTO) on-board to direct and teach them the hunt for innovation through screens. The duration of internship is 60 days giving an intern a total of 120–150 hour working credits (maximum up to 200 hours).', '60 Days', '120–150 Hours', 'Skills', 'Frontend, Backend, SEO', json_encode(['SEO development and Management', 'Front-end Website Development', 'Back-end Website Development', 'Regular Updation & Innovation', 'Glitch & Technical Bug Fixes', 'Content Management', 'Graphic and Product Designing', 'Webpage Development', 'Report Writing and Presentation']), json_encode(['Internship Kit', 'ID Card (optional)', 'Hybrid Mode of Internship', 'Certificate of Completion', 'Letter of Recommendation', 'Certificate of Merit (for innovative tech development)', 'Food Packets on Certificate Distribution Day', 'Stipends/gifts (for excellent performances)', 'Official Merchandise']), json_encode(['Course Fees: ₹999/- (excluding platform fees)', 'Merchandise Fees: ₹149/-', 'No Hidden Charges or extra amounts payable.']), 'https://forms.rigelfoundation.org.in/devsphere-internship-registration-2026/', '<svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="2" y1="20" x2="22" y2="20"></line></svg>', 'linear-gradient(135deg, #10b981, #059669)'],
            // PsyEdge
            ['psyedge', 'PsyEdge Internship', 'A research-oriented internship for Psychology and Sociology students involving survey design, handling datasets, interpreting results, and preparing reports.', 'A specially designed internship program for the students of Psychology and Sociology disciplines in their Undergrads. In this program, interns get an idea of reading, understanding and scouting proper research orientation of the public domain, with reference to the current society. This program gives the young minds an opportunity to explore what has been learnt in the books and handle large amounts of data and interpret them into meaningful explanation. The duration for the internship is of 60 working days giving an intern 120–150 working hours. Each group to be mentored by an expert/associated to the field of psychology/sociology for a subsequent number of years.', '60 Working Days', '120–150 Hours', 'Work', 'Field + Online Surveys', json_encode(['Group Discussion and Panel-Interview', 'Reading and Understanding Research Journals/Articles', 'Scouting for Research Topics', 'Field & Online Survey', 'Open-Discussion and Interaction', 'Assessing and handling large dataset', 'Compiling the datasets with different softwares', 'Interpretation & Explanation of the data', 'Report Writing and Presentation']), json_encode(['Internship Kit', 'ID card', 'Certificate of Completion', 'Letter of Recommendation', 'Certificate of Merit (for outstanding performances)', 'Gifts/Stipend (based on performance)', 'Food packets on Certificate Distribution Day', 'Official Merchandise']), json_encode(['Course Fees: ₹599/- (excluding platform fees)', 'Merchandise Fees: ₹149/-', 'No Hidden Charges or extra amounts payable.']), 'https://forms.rigelfoundation.org.in/psyedge-internship-registration-2026/', '<svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 1.98-3A2.5 2.5 0 0 1 9.5 2Z"></path><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-1.98-3A2.5 2.5 0 0 0 14.5 2Z"></path></svg>', 'linear-gradient(135deg, #8b5cf6, #6d28d9)']
        ];
        
        $insert = $pdo->prepare("INSERT INTO internships (slug, title, short_description, description, duration, credits, focus_title, focus, learning_outcomes, perks, fees, link, icon_svg, icon_bg) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach($seed_internships as $internship) {
            $insert->execute($internship);
        }
    }
    
    // Auto-create admin_whitelist table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `admin_whitelist` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `email` VARCHAR(255) UNIQUE NOT NULL,
        `role` VARCHAR(50) DEFAULT 'admin',
        `added_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Safely add role to existing admin_whitelist table without breaking
    try {
        $pdo->exec("ALTER TABLE `admin_whitelist` ADD COLUMN `role` VARCHAR(50) DEFAULT 'admin'");
    } catch (PDOException $e) {}
    
    // Seed default superadmin
    try {
        $pdo->exec("INSERT IGNORE INTO `admin_whitelist` (email, role) VALUES ('admin@rigel.com', 'superadmin')");
        // Ensure it's superadmin if it already existed
        $pdo->exec("UPDATE `admin_whitelist` SET role = 'superadmin' WHERE email = 'admin@rigel.com'");
        $pdo->exec("UPDATE `users` SET account_type = 'superadmin' WHERE email = 'admin@rigel.com'");
    } catch (PDOException $e) {}
    
    // Auto-create interview_questions table
    $pdo->exec("CREATE TABLE IF NOT EXISTS `interview_questions` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `track_slug` VARCHAR(100) UNIQUE NOT NULL,
        `questions` TEXT NOT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Check if empty and seed questions
    $stmt_q = $pdo->query("SELECT COUNT(*) FROM interview_questions");
    if ($stmt_q->fetchColumn() == 0) {
        $default_questions = [
            "Introduce yourself.",
            "Which domain do you want to work for?",
            "What works do you have in that domain?",
            "What do you think will be the outcome of this internship?"
        ];
        $json_q = json_encode($default_questions);
        
        $seed_q = [
            ['skillsphere', $json_q],
            ['quickpro', $json_q],
            ['devsphere', $json_q],
            ['psyedge', $json_q],
            ['default', $json_q]
        ];
        
        $insert_q = $pdo->prepare("INSERT INTO interview_questions (track_slug, questions) VALUES (?, ?)");
        foreach($seed_q as $q) {
            $insert_q->execute($q);
        }
    }
    
    // Setting default fetch mode to Associative Arrays
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If the request is expecting JSON (like our fetch API), return a JSON error
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Database Connection failed: ' . $e->getMessage()]);
    exit;
}
?>
