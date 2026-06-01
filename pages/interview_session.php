<?php
session_start();
// Login requirement temporarily disabled for testing/applicants
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit;
// }
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

$trackQuestions = [
    'skillsphere' => [
        "Introduce yourself.",
        "Which domain do you want to work for?",
        "What works do you have in that domain?",
        "What do you think will be the outcome of this internship?"
    ],
    'quickpro' => [
        "Introduce yourself.",
        "Which domain do you want to work for?",
        "What works do you have in that domain?",
        "What do you think will be the outcome of this internship?"
    ],
    'devsphere' => [
        "Introduce yourself.",
        "Which domain do you want to work for?",
        "What works do you have in that domain?",
        "What do you think will be the outcome of this internship?"
    ],
    'psyedge' => [
        "Introduce yourself.",
        "Which domain do you want to work for?",
        "What works do you have in that domain?",
        "What do you think will be the outcome of this internship?"
    ],
    'default' => [
        "Introduce yourself.",
        "Which domain do you want to work for?",
        "What works do you have in that domain?",
        "What do you think will be the outcome of this internship?"
    ]
];

$questions = $trackQuestions[$track] ?? $trackQuestions['default'];
$questionsJson = json_encode($questions);
?>

<main class="page-content" style="padding: 8rem 2rem 4rem; min-height: 80vh; background-color: var(--bg-main);">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        
        <div style="text-align: center; margin-bottom: 2rem;" class="fade-in-up">
            <h2 style="font-size: 2.5rem; margin-bottom: 0.5rem;"><i class="fa-solid fa-microphone-lines" style="color: #10b981;"></i> <?php echo htmlspecialchars($title); ?></h2>
            <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                Prepare for your dream role. Answer the questions clearly and confidently. Your session will be recorded and analyzed.
            </p>
        </div>

        <div class="interview-studio fade-in-up" style="background: var(--bg-card); padding: 2rem; border-radius: 24px; box-shadow: var(--shadow-lg); border: 1px solid var(--glass-border); backdrop-filter: blur(12px);">
            
            <div class="split-layout" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: stretch;">
                
                <!-- Left Side: Question Area & Controls -->
                <div class="left-panel" style="display: flex; flex-direction: column; justify-content: space-between; gap: 2rem;">
                    
                    <!-- Question Display Area -->
                    <div id="questionArea" style="flex-grow: 1; background: rgba(16, 185, 129, 0.05); border-radius: 16px; padding: 2.5rem; border: 1px solid rgba(16, 185, 129, 0.2); text-align: center; display: flex; flex-direction: column; justify-content: center;">
                        <span id="questionCounter" style="font-size: 1rem; font-weight: 700; color: #10b981; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 1rem;">Click 'Start Interview' to begin</span>
                        <h3 id="currentQuestion" style="font-size: 1.8rem; color: var(--text-dark); margin: 0; line-height: 1.4;">Ready when you are!</h3>
                        <div id="timerContainer" style="display: none; width: 100%; max-width: 300px; margin: 2rem auto 0;">
                            <div id="timerDisplay" style="font-size: 3.5rem; font-weight: bold; color: #10b981; font-family: monospace; letter-spacing: 2px; margin-bottom: 0.5rem;">02:00</div>
                            <div style="width: 100%; height: 8px; background: rgba(16, 185, 129, 0.2); border-radius: 50px; overflow: hidden;">
                                <div id="timerProgressBar" style="width: 100%; height: 100%; background: #10b981; transition: width 1s linear;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Controls -->
                    <div class="controls" style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                        <button id="startBtn" class="btn" style="display: flex; align-items: center; gap: 0.5rem; width: 100%; justify-content: center; padding: 1rem; font-size: 1.2rem; background: #10b981; color: white; border: none; border-radius: 50px;">
                            <i class="fa-solid fa-play"></i> Start Interview
                        </button>
                        <button id="nextBtn" class="btn btn-outline btn-large" style="display: none; align-items: center; gap: 0.5rem; width: 100%; justify-content: center; padding: 1rem; font-size: 1.2rem;">
                            Next Question <i class="fa-solid fa-arrow-right"></i>
                        </button>
                        <button id="stopBtn" class="btn" style="display: none; align-items: center; gap: 0.5rem; background: #dc2626; color: white; border: none; padding: 1rem; border-radius: 50px; font-weight: 600; font-size: 1.2rem; cursor: pointer; box-shadow: 0 4px 15px rgba(220, 38, 38, 0.4); width: 100%; justify-content: center;">
                            <i class="fa-solid fa-stop"></i> Finish & Submit
                        </button>
                    </div>
                </div>

                <!-- Right Side: Video Recording Feed -->
                <div class="right-panel" style="display: flex; flex-direction: column;">
                    <div class="video-container" style="position: relative; border-radius: 16px; overflow: hidden; background: #0a0f1c; box-shadow: 0 10px 30px rgba(0,0,0,0.3); aspect-ratio: 4/3; width: 100%; height: 100%; border: 1px solid var(--border-light);">
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
                <i class="fa-solid fa-spinner fa-spin" style="font-size: 2rem; color: #10b981; margin-bottom: 1rem;"></i>
                <p style="color: var(--text-dark); font-weight: 600;">Processing your interview... Please wait.</p>
            </div>

        </div>
    </div>
</main>

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
    const stopBtn = document.getElementById('stopBtn');
    const recordingIndicator = document.getElementById('recordingIndicator');
    const currentQuestionEl = document.getElementById('currentQuestion');
    const questionCounterEl = document.getElementById('questionCounter');
    const uploadStatus = document.getElementById('uploadStatus');
    const timerContainer = document.getElementById('timerContainer');
    const timerDisplay = document.getElementById('timerDisplay');
    const timerProgressBar = document.getElementById('timerProgressBar');
    
    let mediaRecorder;
    let recordedChunks = [];
    let currentQuestionIndex = 0;
    let timerInterval;
    let timeLeft = 120; // 2 minutes in seconds
    
    // Preset Questions injected from PHP depending on track
    const questions = <?php echo $questionsJson; ?>;

    // Initialize Camera feed
    navigator.mediaDevices.getUserMedia({ video: true, audio: true })
        .then(stream => {
            videoElement.srcObject = stream;
            
            // Setup Media Recorder
            mediaRecorder = new MediaRecorder(stream, { mimeType: 'video/webm;codecs=vp8,opus' });
            
            mediaRecorder.ondataavailable = function(e) {
                if (e.data.size > 0) {
                    recordedChunks.push(e.data);
                }
            };
            
            mediaRecorder.onstop = function() {
                const blob = new Blob(recordedChunks, { type: 'video/webm' });
                // We will handle the upload in Phase 3
                uploadVideo(blob);
            };
        })
        .catch(err => {
            console.error("Camera access denied:", err);
            currentQuestionEl.innerText = "Error: Camera/Microphone access is required for the interview.";
            currentQuestionEl.style.color = "#dc2626";
            startBtn.disabled = true;
        });

    startBtn.addEventListener('click', () => {
        // Start recording
        recordedChunks = [];
        mediaRecorder.start(1000); // chunk every 1 second
        
        // UI Updates
        startBtn.style.display = 'none';
        nextBtn.style.display = 'flex';
        recordingIndicator.style.display = 'flex';
        
        // Show first question
        currentQuestionIndex = 0;
        updateQuestionUI();
        startTimer();
    });
    
    nextBtn.addEventListener('click', () => {
        currentQuestionIndex++;
        if (currentQuestionIndex < questions.length - 1) {
            updateQuestionUI();
            startTimer();
        } else if (currentQuestionIndex === questions.length - 1) {
            updateQuestionUI();
            startTimer();
            nextBtn.style.display = 'none';
            stopBtn.style.display = 'flex';
        }
    });
    
    stopBtn.addEventListener('click', () => {
        // Stop recording
        mediaRecorder.stop();
        clearInterval(timerInterval);
        
        // UI Updates
        stopBtn.style.display = 'none';
        recordingIndicator.style.display = 'none';
        timerContainer.style.display = 'none';
        currentQuestionEl.innerText = "Interview Complete!";
        questionCounterEl.innerText = "Finishing up...";
        uploadStatus.style.display = 'block';
    });
    
    function updateQuestionUI() {
        questionCounterEl.innerText = `Question ${currentQuestionIndex + 1} of ${questions.length}`;
        currentQuestionEl.innerText = questions[currentQuestionIndex];
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
                // Time is up, move to next
                if (currentQuestionIndex < questions.length - 1) {
                    nextBtn.click();
                } else {
                    stopBtn.click();
                }
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
        uploadStatus.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size: 2.5rem; color: #10b981; margin-bottom: 1rem;"></i><p style="color: var(--text-dark); font-weight: 600;">Saving recording...</p>';
        
        const formData = new FormData();
        formData.append('video', blob, 'interview_recording.webm');
        
        let uploadedFileName = '';

        // 1. Upload Video
        fetch('upload_video.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if(data.status !== 'success') throw new Error(data.message);
            uploadedFileName = data.fileName;
            
            uploadStatus.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size: 2.5rem; color: #10b981; margin-bottom: 1rem;"></i><p style="color: var(--text-dark); font-weight: 600;">Analyzing interview using HuggingFace AI...</p>';
            
            // 2. Process with Groq API
            return fetch('process_interview.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ fileName: uploadedFileName })
            });
        })
        .then(response => response.json())
        .then(data => {
            if(data.status !== 'success') throw new Error(data.message);
            
            uploadStatus.innerHTML = '<i class="fa-solid fa-spinner fa-spin" style="font-size: 2.5rem; color: #10b981; margin-bottom: 1rem;"></i><p style="color: var(--text-dark); font-weight: 600;">Emailing summary to HR...</p>';
            
            // 3. Send Email
            return fetch('send_email.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ summary: data.summary })
            });
        })
        .then(response => response.json())
        .then(data => {
            if(data.status !== 'success') throw new Error(data.message);
            
            // Done
            uploadStatus.innerHTML = '<i class="fa-solid fa-circle-check" style="font-size: 2.5rem; color: #22c55e; margin-bottom: 1rem;"></i><p style="color: #22c55e; font-weight: 700; font-size: 1.2rem;">Interview successfully completed! We have emailed you a confirmation.</p>';
        })
        .catch(err => {
            console.error("Pipeline error:", err);
            uploadStatus.innerHTML = '<i class="fa-solid fa-circle-xmark" style="font-size: 2.5rem; color: #dc2626; margin-bottom: 1rem;"></i><p style="color: #dc2626; font-weight: 700; font-size: 1.2rem;">Error: ' + err.message + '</p>';
        });
    }
});
</script>

<?php include '../includes/footer.php'; ?>
