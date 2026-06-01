<?php include 'includes/header.php'; ?>

<main class="hero-section">
    <div class="hero-content fade-in-up">
        <div class="badge"><svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg> Secure • Unbiased • Intelligent</div>
        <h1 style="color: var(--text-light-shade);">Automated AI Interviews</h1>
        <p>Streamlining our onboarding process with unbiased, intelligent, and scalable interview solutions.</p>
        <div class="hero-buttons">
            <a href="pages/login.php" class="btn btn-primary btn-large">Login</a>
        </div>
    </div>
    <div class="hero-visual">
        <div style="animation: heroFloat 4s ease-in-out infinite; display: flex; justify-content: center; align-items: center;">
             <img src="images/logo.png" alt="Rigel Animated Logo" style="width: 100%; max-width: 350px; height: auto; border-radius: 50%;">
        </div>
    </div>
    <style>
        @keyframes heroFloat {
            0% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-20px) scale(1.02); }
            100% { transform: translateY(0px) scale(1); }
        }
    </style>
    </div>
</main>



<?php include 'includes/footer.php'; ?>
