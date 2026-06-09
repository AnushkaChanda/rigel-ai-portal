<?php
session_start();
// No login required — open to all applicants

$track = $_GET['track'] ?? 'default';

$trackTitles = [
    'skillsphere' => 'SkillSphere Internship',
    'quickpro' => 'QuickPro Internship',
    'devsphere' => 'DevSphere Internship',
    'psyedge' => 'PsyEdge Internship',
    'default' => 'Rigel Internship'
];

$title = $trackTitles[$track] ?? $trackTitles['default'];

include '../includes/header.php';
?>

<div class="page-content fade-in-up">
    <a href="../index.php" class="btn btn-outline" style="margin-bottom: 2rem; display: inline-flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-arrow-left" style="font-size:0.8rem;"></i> Back to Home
    </a>

    <div style="text-align: center; margin-bottom: 2rem;">
        <h2 style="font-size: 2rem; margin-bottom: 0.4rem; color: #0d2149;"><?php echo htmlspecialchars($title); ?> Application</h2>
        <p style="color: var(--text-secondary); font-size: 0.95rem; max-width: 500px; margin: 0 auto;">Complete your details and process the simulated application fee to begin the AI Interview Session.</p>
    </div>

    <div class="glass-container" style="max-width: 700px; margin: 0 auto;">
        <form id="applicationForm" onsubmit="processMockPayment(event)">
            <h3 style="margin-bottom: 1.5rem; color: #0d2149; border-bottom: 1px solid var(--border-light); padding-bottom: 0.5rem; font-size: 1.1rem;">Applicant Details</h3>
            
            <div class="form-group">
                <label for="fullName">Full Name</label>
                <input type="text" id="fullName" class="form-control" value="<?php echo htmlspecialchars($_SESSION['full_name'] ?? ''); ?>" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" class="form-control" placeholder="your.email@example.com" required>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" class="form-control" placeholder="+91 9876543210" required>
            </div>

            <div class="form-group">
                <label for="college">College / University</label>
                <input type="text" id="college" class="form-control" placeholder="Enter your institution name" required>
            </div>

            <h3 style="margin-bottom: 1.2rem; color: #0d2149; border-bottom: 1px solid var(--border-light); padding-bottom: 0.5rem; font-size: 1.1rem;">Payment Information</h3>
            
            <div style="background: rgba(16, 185, 129, 0.04); border: 1px solid rgba(16, 185, 129, 0.15); border-radius: 12px; padding: 1.2rem 1.5rem; margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.4rem; color: var(--text-primary); font-size: 0.95rem;">
                    <span>Application Registration Fee:</span>
                    <span style="font-weight: 600;">₹1.00</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: var(--text-muted);">
                    <span>Taxes & Processing:</span>
                    <span>₹0.00</span>
                </div>
                <hr style="border: 0; border-top: 1px solid rgba(16, 185, 129, 0.15); margin: 0.8rem 0;">
                <div style="display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 700; color: #10b981;">
                    <span>Total to Pay:</span>
                    <span>₹1.00</span>
                </div>
            </div>

            <div id="paymentStatus" style="display: none; text-align: center; margin-bottom: 1.2rem; padding: 0.8rem; border-radius: 10px;"></div>

            <button type="submit" id="payBtn" class="btn btn-primary" style="width: 100%; padding: 0.9rem; font-size: 1rem; border-radius: 12px;">
                <i class="fa-solid fa-lock"></i> Pay ₹1 & Start Interview
            </button>
        </form>
    </div>
</div>

<script>
function processMockPayment(e) {
    e.preventDefault();
    
    const btn = document.getElementById('payBtn');
    const statusBox = document.getElementById('paymentStatus');
    const fullName = document.getElementById('fullName').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const college = document.getElementById('college').value;
    
    // Save applicant details to session via a quick fetch
    fetch('save_applicant_session.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ full_name: fullName, email: email, phone: phone, college: college })
    });
    
    // Disable button and show processing state
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing Secure Payment...';
    btn.style.opacity = '0.7';
    
    statusBox.style.display = 'block';
    statusBox.style.background = 'rgba(56, 182, 255, 0.06)';
    statusBox.style.color = '#0d2149';
    statusBox.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Contacting Payment Gateway...';
    
    // Simulate network delay
    setTimeout(() => {
        statusBox.style.background = 'rgba(16, 185, 129, 0.06)';
        statusBox.style.color = '#10b981';
        statusBox.innerHTML = '<i class="fa-solid fa-circle-check"></i> Payment Successful! Redirecting to Interview Session...';
        
        btn.innerHTML = '<i class="fa-solid fa-check"></i> Paid ₹1.00';
        btn.style.background = '#10b981';
        
        // Redirect to interview session
        setTimeout(() => {
            window.location.href = 'interview_session.php?track=<?php echo urlencode($track); ?>';
        }, 1500);
        
    }, 2500);
}
</script>

<?php include '../includes/footer.php'; ?>
