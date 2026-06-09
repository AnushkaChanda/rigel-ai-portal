<?php
session_start();

// Ensure candidate has verified their email via the session gate
if (empty($_SESSION['user_email'])) {
    header("Location: interview_session.php");
    exit;
}

include '../includes/header.php';

$track = $_GET['track'] ?? 'default';

$trackTitles = [
    'skillsphere' => 'SkillSphere AI Interview Room',
    'quickpro' => 'QuickPro AI Interview Room',
    'devsphere' => 'DevSphere AI Interview Room',
    'psyedge' => 'PsyEdge AI Interview Room',
    'default' => 'Rigel AI Interview Room'
];

$title = $trackTitles[$track] ?? $trackTitles['default'];

// Compulsory questions (always shown, marked with *)
// The first question must ALWAYS be: "Introduce yourself without using I, Me, or Myself."
$introQuestion = "Introduce yourself without using I, Me, or Myself.";

// The rest of the compulsory questions (3 questions, which will follow the intro)
$otherCompulsoryQuestions = [
    "If you could change one thing about your community overnight, what would it be?",
    "What are your hobbies? Share any achievements in that field (if any).",
    "What qualities do you think you possess which will help our organization grow?"
];

// Combine to get final 4 compulsory questions, ensuring Intro is index 0
$compulsoryQuestions = array_merge([$introQuestion], $otherCompulsoryQuestions);

// Optional questions pool — 3 will be randomly selected from these 7
$optionalQuestions = [
    "What is one social issue that you believe doesn't receive enough attention, and why?",
    "Tell us about a challenge you faced while helping others and what it taught you.",
    "What encouraged you to join us?",
    "What role would you like to play in helping Rigel Foundation expand its reach and influence in the future?",
    "If entrusted with a permanent leadership position within Rigel Foundation, what skills, ideas, and value would you bring to the organization?",
    "How can you define your life in one word?",
    "In which domain do you want to work? (Logistics, PR & Marketing, Event Management, Donation Collection)"
];

// Shuffle and pick 3 optional questions
shuffle($optionalQuestions);
$selectedOptional = array_slice($optionalQuestions, 0, 3);

// Final question list: compulsory first (index 0 is Intro), then 3 random optional
$questions = array_merge($compulsoryQuestions, $selectedOptional);

$questionsJson = json_encode($questions);
$compulsoryCount = count($compulsoryQuestions); // 4
?>

<div class="page-content fade-in-up">
    <div style="max-width: 1100px; margin: 0 auto;">
        
        <div style="text-align: center; margin-bottom: 2rem;">
            <h2 style="font-size: 2rem; margin-bottom: 0.4rem; color: #0d2149;"><i class="fa-solid fa-microphone-lines" style="color: #004aad;"></i> <?php echo htmlspecialchars($title); ?></h2>
            <p style="color: var(--text-secondary); font-size: 0.95rem; max-width: 550px; margin: 0 auto;">
                Welcome, <strong style="color: #004aad;"><?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Candidate'); ?></strong>. Answer the questions clearly and confidently.
            </p>
        </div>

        <div class="interview-studio" style="background: var(--card-bg); padding: 2rem; border-radius: 20px; box-shadow: var(--shadow-md); border: 1px solid var(--card-border); backdrop-filter: blur(8px);">
            
            <!-- Main Interview Layout -->
            <div id="interviewMainLayout" class="split-layout" style="display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 2rem; align-items: stretch;">
                
                <!-- Left Side: Question Card (Fixed Height to match Camera aspect-ratio) -->
                <div class="left-panel" style="display: flex; flex-direction: column; justify-content: space-between; border-radius: 20px; background: #ffffff; border: 1px solid rgba(0, 74, 237, 0.15); padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.02); min-height: 420px; box-sizing: border-box;">
                    
                    <!-- Top section: Question Counter and Badges -->
                    <div style="text-align: center; margin-bottom: 1rem;">
                        <div id="questionCounter" style="font-size: 0.95rem; font-weight: 700; color: #004aad; text-transform: uppercase; letter-spacing: 1px;">
                            Click "Start Interview" below to begin
                        </div>
                    </div>
                    
                    <!-- Middle section: Question Text & Timer -->
                    <div id="questionDisplayArea" style="flex-grow: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; margin: 1.5rem 0;">
                        <h3 id="currentQuestion" style="font-size: 1.4rem; color: #0d2149; font-weight: 600; line-height: 1.5; margin: 0; max-width: 90%;">
                            Ready when you are! Camera and microphone access will initialize.
                        </h3>
                        
                        <!-- Timer container inside the card -->
                        <div id="timerContainer" style="display: none; width: 100%; max-width: 250px; margin-top: 1.5rem;">
                            <div id="timerDisplay" style="font-size: 2.8rem; font-weight: bold; color: #004aad; font-family: monospace; letter-spacing: 2px; text-align: center; margin-bottom: 0.3rem;">02:00</div>
                            <div style="width: 100%; height: 6px; background: rgba(0, 74, 237, 0.1); border-radius: 50px; overflow: hidden;">
                                <div id="timerProgressBar" style="width: 100%; height: 100%; background: #004aad; transition: width 1s linear;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom section: Action controls LOCKED inside the left card -->
                    <div class="controls-container" style="display: flex; flex-direction: column; gap: 0.75rem; width: 100%; margin-top: auto;">
                        <button id="startBtn" class="btn" style="display: flex; align-items: center; gap: 0.5rem; width: 100%; justify-content: center; padding: 0.95rem; font-size: 1.15rem; background: linear-gradient(135deg, #38b6ff, #004aad); border: none; border-radius: 50px; color: white; cursor: pointer; font-weight: 600; box-shadow: 0 4px 15px rgba(0, 74, 237, 0.2);">
                            <i class="fa-solid fa-play"></i> Start Interview
                        </button>
                        
                        <!-- Next & Skip row (Visible at all times once interview starts) -->
                        <div id="navBtnsRow" style="display: none; width: 100%; gap: 0.75rem;">
                            <button id="nextBtn" class="btn" style="display: flex; align-items: center; gap: 0.5rem; flex: 2; justify-content: center; padding: 0.95rem; font-size: 1.1rem; border-radius: 50px; background: linear-gradient(135deg, #38b6ff, #004aad); border: none; color: white; cursor: pointer; font-weight: 600; box-shadow: 0 4px 12px rgba(0, 74, 237, 0.15); transition: all 0.2s;">
                                Next Question <i class="fa-solid fa-arrow-right"></i>
                            </button>
                            <button id="skipBtn" class="btn" style="display: flex; align-items: center; gap: 0.5rem; flex: 1; justify-content: center; padding: 0.95rem; font-size: 1.1rem; background: rgba(100, 116, 139, 0.1); color: #64748b; border: 1.5px dashed #94a3b8; border-radius: 50px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                Skip <i class="fa-solid fa-forward-step"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Side: Video Recording Feed -->
                <div class="right-panel" style="display: flex; flex-direction: column;">
                    <div class="video-container" style="position: relative; border-radius: 20px; overflow: hidden; background: #0a0f1c; box-shadow: 0 10px 30px rgba(0,0,0,0.15); aspect-ratio: 4/3; width: 100%; border: 1px solid rgba(0, 74, 237, 0.15);">
                        <video id="cameraFeed" autoplay muted playsinline style="width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1);"></video>
                        
                        <!-- Recording Indicator -->
                        <div id="recordingIndicator" style="display: none; position: absolute; top: 1.5rem; left: 1.5rem; background: rgba(220, 38, 38, 0.9); color: white; padding: 0.5rem 1.2rem; border-radius: 50px; font-weight: 700; font-size: 1rem; align-items: center; gap: 0.6rem; box-shadow: 0 0 15px rgba(220, 38, 38, 0.6);">
                            <div style="width: 12px; height: 12px; background: white; border-radius: 50%; animation: blink 1s infinite;"></div> REC
                        </div>
                    </div>
                </div>
                
            </div>
            
            <!-- Uploading State (Hidden initially) -->
            <div id="uploadStatus" style="display: none; text-align: center; margin-top: 2rem;">
                <i class="fa-solid fa-spinner fa-spin" style="font-size: 2.5rem; color: #004aad; margin-bottom: 1rem;"></i>
                <p id="uploadStatusText" style="color: var(--text-dark); font-weight: 600; font-size: 1.1rem;">Saving recording... Please do not close this window.</p>
            </div>

        </div>
    </div>
</div>

<style>
@keyframes blink {
    0% { opacity: 1; }
    50% { opacity: 0.3; }
    100% { opacity: 1; }
}

@media (max-width: 768px) {
    .split-layout {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const videoElement = document.getElementById('cameraFeed');
    const startBtn = document.getElementById('startBtn');
    const nextBtn = document.getElementById('nextBtn');
    const skipBtn = document.getElementById('skipBtn');
    const navBtnsRow = document.getElementById('navBtnsRow');
    const recordingIndicator = document.getElementById('recordingIndicator');
    const currentQuestionEl = document.getElementById('currentQuestion');
    const questionCounterEl = document.getElementById('questionCounter');
    const uploadStatus = document.getElementById('uploadStatus');
    const uploadStatusText = document.getElementById('uploadStatusText');
    const timerContainer = document.getElementById('timerContainer');
    const timerDisplay = document.getElementById('timerDisplay');
    const timerProgressBar = document.getElementById('timerProgressBar');
    const leftPanel = document.querySelector('.left-panel');
    
    let mediaRecorder;
    let recordedChunks = [];
    let currentQuestionIndex = 0;
    let timerInterval;
    let timeLeft = 120; // 2 minutes in seconds

    // Number of compulsory questions (always first in list)
    const compulsoryCount = <?php echo $compulsoryCount; ?>;
    
    // Questions injected from PHP
    const questions = <?php echo $questionsJson; ?>;

    // Hover actions for skip button
    skipBtn.addEventListener('mouseenter', () => {
        if (currentQuestionIndex >= compulsoryCount) {
            skipBtn.style.background = 'rgba(100,116,139,0.2)';
            skipBtn.style.borderColor = '#64748b';
        }
    });
    skipBtn.addEventListener('mouseleave', () => {
        if (currentQuestionIndex >= compulsoryCount) {
            skipBtn.style.background = 'rgba(100,116,139,0.1)';
            skipBtn.style.borderColor = '#94a3b8';
        }
    });

    // Automatically initialize camera on load
    initCamera();

    function initCamera() {
        navigator.mediaDevices.getUserMedia({ video: true, audio: true })
            .then(stream => {
                videoElement.srcObject = stream;
                mediaRecorder = new MediaRecorder(stream, { mimeType: 'video/webm;codecs=vp8,opus' });
                mediaRecorder.ondataavailable = function(e) {
                    if (e.data.size > 0) recordedChunks.push(e.data);
                };
                mediaRecorder.onstop = function() {
                    const blob = new Blob(recordedChunks, { type: 'video/webm' });
                    uploadVideo(blob);
                };
            })
            .catch(err => {
                console.warn("Camera/microphone access denied. Entering mock preview mode:", err);
                
                // Set up mock media recorder to simulate recording so test runs can finish
                mediaRecorder = {
                    state: "inactive",
                    start: function() { this.state = "recording"; },
                    stop: function() {
                        this.state = "inactive";
                        if (this.onstop) {
                            // Create a small mock WebM blob so the upload process compiles
                            const mockBlob = new Blob(["MOCK_RECORDING_DATA_FOR_PREVIEW"], { type: 'video/webm' });
                            this.onstop({ data: mockBlob });
                        }
                    }
                };
                
                // Show a helpful info banner on the camera element
                const mockBanner = document.createElement('div');
                mockBanner.innerHTML = '⚠️ Running in Camera Preview Mode (No actual recording)';
                mockBanner.style.cssText = 'position:absolute;bottom:1rem;left:50%;transform:translateX(-50%);background:rgba(220,38,38,0.85);color:white;padding:0.4rem 1rem;border-radius:50px;font-size:0.8rem;font-weight:700;z-index:10;width:max-content;box-shadow:0 2px 10px rgba(0,0,0,0.3);';
                document.querySelector('.video-container').appendChild(mockBanner);
                
                currentQuestionEl.innerText = "Ready when you are! (Running in Mock Camera mode)";
                startBtn.disabled = false;
            });
    }

    startBtn.addEventListener('click', () => {
        recordedChunks = [];
        mediaRecorder.start(1000);
        startBtn.style.display = 'none';
        navBtnsRow.style.display = 'flex';
        recordingIndicator.style.display = 'flex';
        currentQuestionIndex = 0;
        updateQuestionUI();
        startTimer();
    });

    // Shared advance function used by both Next and Skip
    function advanceQuestion() {
        currentQuestionIndex++;
        if (currentQuestionIndex < questions.length) {
            updateQuestionUI();
            startTimer();
        } else {
            // Reached past the last question - finish and submit
            finishAndSubmit();
        }
    }
    
    nextBtn.addEventListener('click', advanceQuestion);
    
    skipBtn.addEventListener('click', () => {
        // Compulsory questions (index < compulsoryCount) cannot be skipped
        if (currentQuestionIndex < compulsoryCount) {
            // Pulse the card red
            leftPanel.style.transition = 'box-shadow 0.15s';
            leftPanel.style.boxShadow = '0 0 0 4px rgba(220,38,38,0.35)';
            setTimeout(() => { leftPanel.style.boxShadow = ''; }, 700);
            
            // Toast warning message
            const toast = document.createElement('div');
            toast.innerText = '⚠️ This question is compulsory and cannot be skipped.';
            toast.style.cssText = 'position:fixed;bottom:2rem;left:50%;transform:translateX(-50%);background:#1e293b;color:#f8fafc;padding:0.75rem 1.5rem;border-radius:50px;font-size:0.9rem;font-weight:600;z-index:9999;box-shadow:0 4px 20px rgba(0,0,0,0.3);pointer-events:none;';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2500);
            return;
        }
        advanceQuestion();
    });
    
    function finishAndSubmit() {
        if (mediaRecorder && mediaRecorder.state !== "inactive") {
            mediaRecorder.stop();
        }
        clearInterval(timerInterval);
        navBtnsRow.style.display = 'none';
        recordingIndicator.style.display = 'none';
        timerContainer.style.display = 'none';
        currentQuestionEl.innerText = "Interview Complete!";
        questionCounterEl.innerText = "Finishing up...";
        uploadStatus.style.display = 'block';
    }
    
    function updateQuestionUI() {
        const isCompulsory = currentQuestionIndex < compulsoryCount;
        const isLast = currentQuestionIndex === questions.length - 1;
        
        // Badge: Compulsory vs Optional
        const badge = isCompulsory
            ? '<span style="display:inline-block;background:rgba(220,38,38,0.08);color:#dc2626;font-size:0.75rem;font-weight:700;letter-spacing:1px;padding:3px 12px;border-radius:50px;border:1px solid rgba(220,38,38,0.25);text-transform:uppercase;margin-bottom:0.6rem;">★ Compulsory</span>'
            : '<span style="display:inline-block;background:rgba(100,116,139,0.08);color:#64748b;font-size:0.75rem;font-weight:700;letter-spacing:1px;padding:3px 12px;border-radius:50px;border:1px solid rgba(100,116,139,0.2);text-transform:uppercase;margin-bottom:0.6rem;">Optional</span>';
        
        questionCounterEl.innerHTML = badge + `<br><div style='margin-top:0.4rem;color:#64748b;'>Question ${currentQuestionIndex + 1} of ${questions.length}</div>`;
        currentQuestionEl.innerText = questions[currentQuestionIndex];

        // Style Next button differently on the last question to show Submit
        if (isLast) {
            nextBtn.innerHTML = 'Finish & Submit <i class="fa-solid fa-square-check" style="margin-left:5px;"></i>';
            nextBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
            nextBtn.style.boxShadow = '0 4px 15px rgba(16, 185, 129, 0.25)';
        } else {
            nextBtn.innerHTML = 'Next Question <i class="fa-solid fa-arrow-right"></i>';
            nextBtn.style.background = 'linear-gradient(135deg, #38b6ff, #004aad)';
            nextBtn.style.boxShadow = '0 4px 15px rgba(0, 74, 237, 0.15)';
        }

        // Visually disable skip btn for compulsory questions
        if (isCompulsory) {
            skipBtn.style.opacity = '0.4';
            skipBtn.style.cursor = 'not-allowed';
            skipBtn.style.background = 'rgba(100,116,139,0.05)';
        } else {
            skipBtn.style.opacity = '1';
            skipBtn.style.cursor = 'pointer';
            skipBtn.style.background = 'rgba(100,116,139,0.1)';
        }
    }
    
    function startTimer() {
        clearInterval(timerInterval);
        timeLeft = 120; // 2 minutes
        updateTimerDisplay();
        timerContainer.style.display = 'block';
        
        timerInterval = setInterval(() => {
            timeLeft--;
            updateTimerDisplay();
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                advanceQuestion();
            }
        }, 1000);
    }
    
    function updateTimerDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerDisplay.innerText = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        // Update progress bar width
        const percentage = (timeLeft / 120) * 100;
        if(timerProgressBar) timerProgressBar.style.width = `${percentage}%`;
    }
    
    function uploadVideo(blob) {
        console.log("Video Blob created. Size:", blob.size);
        uploadStatus.style.display = 'block';
        uploadStatusText.innerHTML = 'Saving recording... Please do not close this window.';
        
        const formData = new FormData();
        formData.append('video', blob, 'interview_recording.webm');
        
        let uploadedFileName = '';

        // 1. Upload Video
        fetch('upload_video.php', { method: 'POST', body: formData })
        .then(response => {
            return response.json().then(data => {
                if (!response.ok) {
                    throw new Error(data.message || 'Video upload failed.');
                }
                return data;
            });
        })
        .then(data => {
            if(data.status !== 'success') throw new Error(data.message);
            uploadedFileName = data.fileName;
            
            uploadStatusText.innerHTML = 'Analyzing interview using AI model... Please wait.';
            
            // 2. Process with Groq API
            return fetch('process_interview.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ fileName: uploadedFileName })
            });
        })
        .then(response => {
            return response.json().then(data => {
                if (!response.ok) {
                    throw new Error(data.message || 'AI processing failed.');
                }
                return data;
            });
        })
        .then(data => {
            if(data.status !== 'success') throw new Error(data.message);
            
            uploadStatusText.innerHTML = 'Emailing summary to HR and sending confirmation...';
            
            // 3. Send Email
            return fetch('send_email.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ summary: data.summary })
            });
        })
        .then(response => {
            return response.json().then(data => {
                if (!response.ok) {
                    throw new Error(data.message || 'Email sending failed.');
                }
                return data;
            });
        })
        .then(data => {
            if(data.status !== 'success') throw new Error(data.message);
            
            // Completed successfully
            uploadStatus.innerHTML = '<i class="fa-solid fa-circle-check" style="font-size: 2.5rem; color: #22c55e; margin-bottom: 1rem;"></i><p style="color: #22c55e; font-weight: 700; font-size: 1.2rem;">Interview successfully completed! We have emailed you a confirmation. Redirecting to home...</p>';
            
            setTimeout(() => {
                window.location.href = '../index.php';
            }, 3000);
        })
        .catch(err => {
            console.error("Pipeline error:", err);
            uploadStatus.innerHTML = '<i class="fa-solid fa-circle-xmark" style="font-size: 2.5rem; color: #dc2626; margin-bottom: 1rem;"></i><p style="color: #dc2626; font-weight: 700; font-size: 1.2rem;">Error: ' + err.message + '</p><p style="color: #64748b; font-size: 0.9rem; margin-top: 0.5rem;">Note: Large recordings can occasionally hit server upload or execution limits in PHP. You can configure larger upload limits in php.ini.</p>';
        });
    }
});
</script>

<?php include '../includes/footer.php'; ?>
