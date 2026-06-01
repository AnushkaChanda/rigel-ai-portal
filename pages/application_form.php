<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

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

<main class="page-content" style="padding: 8rem 2rem 4rem; min-height: 80vh; background-color: var(--bg-main);">
    <div class="container" style="max-width: 800px; margin: 0 auto;">
        
        <div style="text-align: center; margin-bottom: 2rem;" class="fade-in-up">
            <h2 style="font-size: 2.5rem; margin-bottom: 0.5rem; color: var(--primary-blue);"><?php echo htmlspecialchars($title); ?> Application</h2>
            <p style="color: var(--text-muted); font-size: 1.1rem;">Complete your application details and securely process your simulated application fee to begin the AI Interview Session immediately.</p>
        </div>

        <div class="auth-container fade-in-up" style="max-width: 100%; border-radius: 20px; padding: 3rem; background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); box-shadow: var(--shadow-lg);">
            <form id="applicationForm" onsubmit="processMockPayment(event)">
                <h3 style="margin-bottom: 1.5rem; color: var(--text-dark); border-bottom: 1px solid var(--border-light); padding-bottom: 0.5rem;">Applicant Details</h3>
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="fullName" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-dark);">Full Name</label>
                    <input type="text" id="fullName" class="form-control" value="<?php echo htmlspecialchars($_SESSION['full_name'] ?? ''); ?>" required style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: var(--bg-card); color: var(--text-dark);">
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-dark);">Email Address</label>
                    <input type="email" id="email" class="form-control" placeholder="your.email@example.com" required style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: var(--bg-card); color: var(--text-dark);">
                </div>
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="phone" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-dark);">Phone Number</label>
                    <input type="tel" id="phone" class="form-control" placeholder="+91 9876543210" required style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: var(--bg-card); color: var(--text-dark);">
                </div>

                <div class="form-group" style="margin-bottom: 2rem;">
                    <label for="college" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--text-dark);">College / University</label>
                    <input type="text" id="college" class="form-control" placeholder="Enter your institution name" required style="width: 100%; padding: 0.8rem; border-radius: 8px; border: 1px solid var(--border-light); background: var(--bg-card); color: var(--text-dark);">
                </div>

                <h3 style="margin-bottom: 1.5rem; color: var(--text-dark); border-bottom: 1px solid var(--border-light); padding-bottom: 0.5rem;">Payment Information</h3>
                
                <div style="background: rgba(16, 185, 129, 0.05); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: var(--text-dark);">
                        <span>Application Registration Fee:</span>
                        <span style="font-weight: 600;">₹1.00</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.9rem; color: var(--text-muted);">
                        <span>Taxes & Processing:</span>
                        <span>₹0.00</span>
                    </div>
                    <hr style="border: 0; border-top: 1px solid rgba(16, 185, 129, 0.2); margin: 1rem 0;">
                    <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: 700; color: #10b981;">
                        <span>Total to Pay:</span>
                        <span>₹1.00</span>
                    </div>
                </div>

                <div id="paymentStatus" style="display: none; text-align: center; margin-bottom: 1.5rem; padding: 1rem; border-radius: 8px;">
                    <!-- Status injected via JS -->
                </div>

                <button type="submit" id="payBtn" class="btn btn-primary" style="width: 100%; padding: 1.2rem; font-size: 1.1rem; border-radius: 12px; display: flex; justify-content: center; align-items: center; gap: 10px; cursor: pointer;">
                    <i class="fa-solid fa-lock"></i> Pay ₹1 & Start Interview
                </button>
            </form>
        </div>
    </div>
</main>

<script>
function processMockPayment(e) {
    e.preventDefault();
    
    const btn = document.getElementById('payBtn');
    const statusBox = document.getElementById('paymentStatus');
    
    // Disable button and show processing state
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Processing Secure Payment...';
    btn.style.opacity = '0.7';
    
    statusBox.style.display = 'block';
    statusBox.style.background = 'rgba(56, 182, 255, 0.1)';
    statusBox.style.color = 'var(--primary-blue)';
    statusBox.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Contacting Payment Gateway...';
    
    // Simulate network delay
    setTimeout(() => {
        statusBox.style.background = 'rgba(16, 185, 129, 0.1)';
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
