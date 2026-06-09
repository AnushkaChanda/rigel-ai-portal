<?php
session_start();
include '../includes/header.php';

$track = $_GET['track'] ?? 'default';

$trackTitles = [
    'skillsphere' => 'SkillSphere AI Interview Session',
    'quickpro' => 'QuickPro AI Interview Session',
    'devsphere' => 'DevSphere AI Interview Session',
    'psyedge' => 'PsyEdge AI Interview Session',
    'default' => 'Rigel AI Interview Session'
];

$title = $trackTitles[$track] ?? $trackTitles['default'];
?>

<div class="page-content fade-in-up">
    <div style="max-width: 1100px; margin: 0 auto; min-height: 70vh; display: flex; flex-direction: column; justify-content: center;">
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <h2 style="font-size: 2rem; margin-bottom: 0.4rem; color: #0d2149;"><i class="fa-solid fa-microphone-lines" style="color: #004aad;"></i> <?php echo htmlspecialchars($title); ?></h2>
            <p style="color: var(--text-secondary); font-size: 0.95rem; max-width: 550px; margin: 0 auto;">
                Prepare for your dream role. Answer the questions clearly and confidently. Your session will be recorded and analyzed.
            </p>
        </div>

        <div class="interview-studio" style="background: var(--card-bg); padding: 2.5rem; border-radius: 20px; box-shadow: var(--shadow-md); border: 1px solid var(--card-border); backdrop-filter: blur(8px); display: flex; justify-content: center; align-items: center; min-height: 380px;">
            
            <!-- Step 1: Pre-Interview Email Verification Gate -->
            <div id="emailVerificationStep" style="width: 100%; max-width: 500px; margin: 0 auto; padding: 1rem 0; text-align: center;">
                <div style="width: 65px; height: 65px; border-radius: 50%; background: rgba(0, 74, 237, 0.06); color: #004aad; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 1.8rem; box-shadow: 0 4px 12px rgba(0, 74, 237, 0.1);">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h3 style="font-size: 1.5rem; color: #0d2149; margin-bottom: 0.8rem; font-weight: 700;">Interview Access Gate</h3>
                <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 1.8rem; line-height: 1.6;">
                    Please enter the email address associated with your interview invitation to verify your access and time window.
                </p>
                
                <div class="form-group" style="text-align: left; margin-bottom: 1.5rem;">
                    <label style="font-weight: 600; font-size: 0.85rem; color: #0d2149; margin-bottom: 0.5rem; display: block;">Notification / Invitation Email</label>
                    <input type="email" id="verificationEmail" class="form-control" placeholder="your.name@example.com" style="border: 1.5px solid rgba(0, 74, 237, 0.2); padding: 0.8rem 1rem; border-radius: 10px; font-size: 0.95rem; width: 100%; box-sizing: border-box; transition: border-color 0.2s;" required>
                </div>
                
                <div id="verificationErrorMsg" style="display: none; padding: 0.8rem; margin-bottom: 1.5rem; background: rgba(239, 68, 68, 0.06); border: 1px solid rgba(239, 68, 68, 0.25); border-radius: 10px; color: #dc2626; font-size: 0.85rem; font-weight: 600; text-align: left; line-height: 1.4;">
                    <i class="fa-solid fa-triangle-exclamation" style="margin-right: 5px;"></i> <span id="errorText"></span>
                </div>

                <button id="verifyEmailBtn" class="btn btn-primary" style="width: 100%; padding: 0.9rem; font-size: 1rem; font-weight: 600; border-radius: 10px; background: linear-gradient(135deg, #38b6ff, #004aad); border: none; color: white; cursor: pointer; box-shadow: 0 4px 15px rgba(0, 74, 237, 0.2);">
                    Verify Access <i class="fa-solid fa-chevron-right" style="margin-left: 6px; font-size: 0.8rem;"></i>
                </button>
            </div>

            <!-- Step 2: Confirm Candidate Info -->
            <div id="emailConfirmationStep" style="display: none; width: 100%; max-width: 500px; margin: 0 auto; padding: 1rem 0; text-align: center;">
                <div style="width: 65px; height: 65px; border-radius: 50%; background: rgba(16, 185, 129, 0.06); color: #10b981; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 1.8rem; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.1);">
                    <i class="fa-solid fa-envelope-circle-check"></i>
                </div>
                <h3 style="font-size: 1.5rem; color: #0d2149; margin-bottom: 0.8rem; font-weight: 700;">Confirm Candidate Details</h3>
                <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 1.8rem; line-height: 1.6;">
                    Please confirm your full name below. We will use this information and your verified email address to deliver your interview results and confirmations.
                </p>
                
                <div class="form-group" style="text-align: left; margin-bottom: 1.2rem;">
                    <label style="font-weight: 600; font-size: 0.85rem; color: #0d2149; margin-bottom: 0.5rem; display: block;">Your Full Name</label>
                    <input type="text" id="confirmName" class="form-control" placeholder="Enter your full name" style="border: 1.5px solid rgba(0, 74, 237, 0.2); padding: 0.8rem 1rem; border-radius: 10px; font-size: 0.95rem; width: 100%; box-sizing: border-box;" required>
                </div>
                
                <div class="form-group" style="text-align: left; margin-bottom: 1.8rem;">
                    <label style="font-weight: 600; font-size: 0.85rem; color: #0d2149; margin-bottom: 0.5rem; display: block;">Your Notification Email Address</label>
                    <input type="email" id="confirmEmail" class="form-control" readonly style="border: 1.5px solid rgba(0, 74, 237, 0.1); background-color: #f8fafc; color: #64748b; padding: 0.8rem 1rem; border-radius: 10px; font-size: 0.95rem; width: 100%; box-sizing: border-box; cursor: not-allowed;">
                </div>
                
                <button id="confirmBtn" class="btn btn-primary" style="width: 100%; padding: 0.9rem; font-size: 1rem; font-weight: 600; border-radius: 10px; background: linear-gradient(135deg, #10b981, #059669); border: none; color: white; cursor: pointer; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);">
                    Confirm & Enter Interview Room <i class="fa-solid fa-chevron-right" style="margin-left: 6px; font-size: 0.8rem;"></i>
                </button>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const emailVerificationStep = document.getElementById('emailVerificationStep');
    const verifyEmailBtn = document.getElementById('verifyEmailBtn');
    const verificationEmail = document.getElementById('verificationEmail');
    const verificationErrorMsg = document.getElementById('verificationErrorMsg');
    const errorText = document.getElementById('errorText');
    
    const confirmBtn = document.getElementById('confirmBtn');
    const emailConfirmationStep = document.getElementById('emailConfirmationStep');
    
    const track = "<?php echo htmlspecialchars($track); ?>";

    // Step A Verification Gating Logic
    verifyEmailBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const email = verificationEmail.value.trim();
        if (!email) {
            showVerificationError("Please enter your email address.");
            return;
        }
        
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showVerificationError("Please enter a valid email address format.");
            return;
        }
        
        verifyEmailBtn.disabled = true;
        verifyEmailBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Verifying...';
        verificationErrorMsg.style.display = 'none';
        
        fetch('validate_interview_access.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: email })
        })
        .then(res => {
            return res.json().then(data => {
                if (!res.ok) {
                    throw new Error(data.message || 'Access denied.');
                }
                return data;
            });
        })
        .then(data => {
            if (data.status === 'success') {
                emailVerificationStep.style.display = 'none';
                emailConfirmationStep.style.display = 'block';
                
                // Pre-populate verified fields
                document.getElementById('confirmName').value = data.name || '';
                document.getElementById('confirmEmail').value = data.email || email;
            } else {
                throw new Error(data.message || 'Access verification failed.');
            }
        })
        .catch(err => {
            showVerificationError(err.message);
            verifyEmailBtn.disabled = false;
            verifyEmailBtn.innerHTML = 'Verify Access <i class="fa-solid fa-chevron-right" style="margin-left: 6px; font-size: 0.8rem;"></i>';
        });
    });

    function showVerificationError(msg) {
        errorText.innerText = msg;
        verificationErrorMsg.style.display = 'block';
        
        emailVerificationStep.style.transition = 'box-shadow 0.15s';
        emailVerificationStep.style.boxShadow = '0 0 0 3px rgba(220,38,38,0.25)';
        setTimeout(() => { emailVerificationStep.style.boxShadow = ''; }, 700);
    }

    confirmBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const confirmName = document.getElementById('confirmName').value.trim();
        const confirmEmail = document.getElementById('confirmEmail').value.trim();
        
        if (!confirmName || !confirmEmail) {
            alert("Please provide both your name and email address.");
            return;
        }
        
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Saving...';
        
        // Save back to applicant details session
        fetch('save_applicant_session.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ full_name: confirmName, email: confirmEmail })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Redirect to the actual interview room page
                window.location.href = 'interview_room.php?track=' + encodeURIComponent(track);
            } else {
                alert("Failed to save candidate details. Please try again.");
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = 'Confirm & Enter Interview Room <i class="fa-solid fa-chevron-right" style="margin-left: 6px; font-size: 0.8rem;"></i>';
            }
        })
        .catch(err => {
            console.error(err);
            alert("An error occurred. Please try again.");
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = 'Confirm & Enter Interview Room <i class="fa-solid fa-chevron-right" style="margin-left: 6px; font-size: 0.8rem;"></i>';
        });
    });
});
</script>

<?php include '../includes/footer.php'; ?>
