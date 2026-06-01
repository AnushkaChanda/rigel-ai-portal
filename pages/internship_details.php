<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include '../includes/header.php';

$track = $_GET['track'] ?? '';

$internships = [
    'skillsphere' => [
        'title' => 'SkillSphere Internship',
        'desc' => 'A complete skill-based on-ground hands-on-training of the day-to-day activity of an NGO working for the society. The internship duration is 45 working days giving an intern 45–60 hour working credits (maximum up to 90 hours). The intern will work with his/her fellow teammates to accomplish a team goal (Target) set by the Internship Mentor. Each team will be guided by a Mentor, who will be your go-to person during this duration.',
        'learning' => [
            'Group Discussion and Panel-Interview',
            'Key Processes of NGO operation',
            'NGO hierarchy',
            'Fund Allocation and Fund Management',
            'Awareness & PR activities for brand building',
            'Event Planning & Management',
            'Content Marketing & Lead Generation',
            'On-ground crisis management and troubleshooting',
            'Report writing & presentation'
        ],
        'perks' => [
            'Internship Kit',
            'ID Card',
            'Certificate of Completion',
            'Letter of Recommendation',
            'Certificate of Merit (Outstanding Performances)',
            'Food Packets on Certificate Distribution Day',
            'Gifts/Stipend (based on performance)',
            'Official Merchandise'
        ],
        'fees' => [
            'Course Fee: ₹399/- (excluding platform fees)',
            'Merchandise Fee: ₹149/-',
            'No Hidden Charges or extra amounts payable.'
        ]
    ],
    'quickpro' => [
        'title' => 'QuickPro Internship',
        'desc' => 'A complete learning and theoretical internship, mainly focussing on building key concepts of working & foundational challenges and developments, with relatively less emphasis on hands-on-training of the day-to-day activity. The program entails experts and senior officials from different fields to join and train the interns with different modules, explaining the on-ground realities at the ease of their study desk. The duration of the program is 21 working days giving an intern 30–45 hour working credits.',
        'learning' => [
            'Group Discussion & Panel-Interview',
            'Key Processes of NGO operation',
            'NGO hierarchy',
            'Documentations and Legal Compliances of NGO operation',
            'Project Planning, Report building and Funding Opportunities',
            'Content Management & use of AI tools',
            'Crisis Management & Case-Study',
            'Written & Viva Exam'
        ],
        'perks' => [
            'Internship Kit',
            'ID card (optional)',
            'Hybrid Mode of Learning',
            'Certificate of Completion',
            'Letter of Recommendation',
            'Certificate of Merit (for outstanding performances)',
            'Food packets on Certificate Distribution Day',
            'Official Merchandise'
        ],
        'fees' => [
            'Course Fees: ₹1299/- (excluding platform fees)',
            'Merchandise Fees: ₹149/-',
            'No Hidden Charges or extra amounts payable.'
        ]
    ],
    'devsphere' => [
        'title' => 'DevSphere Internship',
        'desc' => 'A developers arena with codes, designs and writing, where interns get to directly work on the official website of the foundation, making the site look vibrant and innovative with their skills on prowess, letting our audience sigh with awe. Brilliance on every click is the ideology that brings our Chief Technical Officer (CTO) on-board to direct and teach them the hunt for innovation through screens. The duration of internship is 60 days giving an intern a total of 120–150 hour working credits (maximum up to 200 hours).',
        'learning' => [
            'SEO development and Management',
            'Front-end Website Development',
            'Back-end Website Development',
            'Regular Updation & Innovation',
            'Glitch & Technical Bug Fixes',
            'Content Management',
            'Graphic and Product Designing',
            'Webpage Development',
            'Report Writing and Presentation'
        ],
        'perks' => [
            'Internship Kit',
            'ID Card (optional)',
            'Hybrid Mode of Internship',
            'Certificate of Completion',
            'Letter of Recommendation',
            'Certificate of Merit (for innovative tech development)',
            'Food Packets on Certificate Distribution Day',
            'Stipends/gifts (for excellent performances)',
            'Official Merchandise'
        ],
        'fees' => [
            'Course Fees: ₹999/- (excluding platform fees)',
            'Merchandise Fees: ₹149/-',
            'No Hidden Charges or extra amounts payable.'
        ]
    ],
    'psyedge' => [
        'title' => 'PsyEdge Internship',
        'desc' => 'A specially designed internship program for the students of Psychology and Sociology disciplines in their Undergrads. In this program, interns get an idea of reading, understanding and scouting proper research orientation of the public domain, with reference to the current society. This program gives the young minds an opportunity to explore what has been learnt in the books and handle large amounts of data and interpret them into meaningful explanation. The duration for the internship is of 60 working days giving an intern 120–150 working hours. Each group to be mentored by an expert/associated to the field of psychology/sociology for a subsequent number of years.',
        'learning' => [
            'Group Discussion and Panel-Interview',
            'Reading and Understanding Research Journals/Articles',
            'Scouting for Research Topics',
            'Field & Online Survey',
            'Open-Discussion and Interaction',
            'Assessing and handling large dataset',
            'Compiling the datasets with different softwares',
            'Interpretation & Explanation of the data',
            'Report Writing and Presentation'
        ],
        'perks' => [
            'Internship Kit',
            'ID card',
            'Certificate of Completion',
            'Letter of Recommendation',
            'Certificate of Merit (for outstanding performances)',
            'Gifts/Stipend (based on performance)',
            'Food packets on Certificate Distribution Day',
            'Official Merchandise'
        ],
        'fees' => [
            'Course Fees: ₹599/- (excluding platform fees)',
            'Merchandise Fees: ₹149/-',
            'No Hidden Charges or extra amounts payable.'
        ]
    ]
];

if (!array_key_exists($track, $internships)) {
    echo "<script>window.location.href='dashboard.php';</script>";
    exit;
}

$details = $internships[$track];
?>
<main style="padding: 100px 20px 40px; max-width: 1000px; margin: 0 auto; min-height: 80vh;">
    <a href="dashboard.php" class="btn btn-outline" style="margin-bottom: 2rem; display: inline-flex; align-items: center; gap: 8px;"><svg width="1.2em" height="1.2em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg> Back to Dashboard</a>
    
    <div style="background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); border-radius: 20px; padding: 3rem; box-shadow: var(--shadow-lg);">
        <h1 style="color: var(--primary-blue); margin-bottom: 1.5rem; font-size: 2.5rem;"><?php echo $details['title']; ?></h1>
        <p style="font-size: 1.1rem; line-height: 1.8; color: var(--text-dark); margin-bottom: 2.5rem;"><?php echo $details['desc']; ?></p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 3rem;">
            <div>
                <h3 style="color: var(--secondary-blue); margin-bottom: 1rem; font-size: 1.4rem; display: flex; align-items: center; gap: 8px;"><svg width="1.2em" height="1.2em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg> Learning Outcomes:</h3>
                <ul style="list-style-type: none; padding-left: 0;">
                    <?php foreach($details['learning'] as $item): ?>
                        <li style="margin-bottom: 0.8rem; padding-left: 25px; position: relative; color: var(--text-dark);">
                            <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 0; top: 4px;"><polyline points="20 6 9 17 4 12"></polyline></svg> <?php echo htmlspecialchars($item); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div>
                <h3 style="color: var(--secondary-blue); margin-bottom: 1rem; font-size: 1.4rem; display: flex; align-items: center; gap: 8px;"><svg width="1.2em" height="1.2em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="8" width="18" height="4" rx="1" ry="1"></rect><path d="M12 8v13"></path><path d="M19 12v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-7"></path><path d="M7.5 8a2.5 2.5 0 0 1 0-5A4.8 8 0 0 1 12 8a4.8 8 0 0 1 4.5-5 2.5 2.5 0 0 1 0 5"></path></svg> Perks & Benefits:</h3>
                <ul style="list-style-type: none; padding-left: 0;">
                    <?php foreach($details['perks'] as $item): ?>
                        <li style="margin-bottom: 0.8rem; padding-left: 25px; position: relative; color: var(--text-dark);">
                            <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 0; top: 4px;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg> <?php echo htmlspecialchars($item); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        
        <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-light); text-align: center;">
            <h3 style="color: var(--primary-blue); margin-bottom: 1.5rem; font-size: 1.4rem;">Course Fees</h3>
            <div style="display: inline-block; text-align: left; background: rgba(56, 182, 255, 0.05); padding: 1.5rem 2.5rem; border-radius: 12px; border: 1px solid rgba(56, 182, 255, 0.2);">
                <?php foreach($details['fees'] as $item): ?>
                    <p style="margin-bottom: 0.5rem; font-size: 1.1rem; color: var(--text-dark); font-weight: 500;"><?php echo htmlspecialchars($item); ?></p>
                <?php endforeach; ?>
            </div>
            <div style="margin-top: 2rem;">
                <!-- Link updated to internal application form -->
                <a href="application_form.php?track=<?php echo htmlspecialchars($track); ?>" class="btn btn-primary btn-large" style="padding: 1rem 3rem;">Apply Now</a>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
