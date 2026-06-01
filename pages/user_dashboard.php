<?php
// user_dashboard.php
// This file is included from dashboard.php, so session is already started and db connected.
?>

<div class="dashboard-container fade-in-up">
    <div class="dashboard-header-modern">
        <div class="header-content">
            <div class="badge-status">
                <div class="pulse-dot"></div>
                Session Active
            </div>
            <h1>Welcome, <span class="highlight"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>!</h1>
            <p>Your account type: <strong><?php echo htmlspecialchars(ucfirst($_SESSION['account_type'])); ?></strong>. Track your progress and jump right back into practice.</p>
        </div>
        <div class="header-illustration">
            <i class="fa-solid fa-user-graduate header-icon"></i>
        </div>
    </div>

    <div class="stats-grid" style="display: none;">
        <!-- Stats hidden as requested -->
    </div>


    
    <div class="section-title" style="margin-top: 4rem;">
        <h2>Rigel Foundation Internship Tracks</h2>
        <div class="title-underline"></div>
        <p style="color: var(--text-muted); margin-top: 1rem;">Choose from our flagship internship programs designed to provide real-world exposure, mentorship, and certified professional experience.</p>
    </div>

    <div class="features-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
        
        <!-- SkillSphere -->
        <div class="feature-card" style="padding: 2rem; border-radius: 16px; background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); box-shadow: var(--shadow-md); transition: transform 0.3s; display: flex; flex-direction: column;">
            <div class="icon-wrapper" style="width: 50px; height: 50px; background: linear-gradient(135deg, #ff4d6d, #ff758c); border-radius: 12px; color: white; display: flex; justify-content: center; align-items: center; font-size: 1.2rem; margin-bottom: 1.2rem;">
                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="6"></circle><circle cx="12" cy="12" r="2"></circle></svg>
            </div>
            <h3 style="font-size: 1.3rem; margin-bottom: 0.8rem; color: var(--text-dark);">SkillSphere Internship</h3>
            <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 1.2rem; flex-grow: 1;">
                A complete hands-on on-ground internship where interns work in teams under mentors to experience NGO execution, PR activities, and real-time troubleshooting.
            </p>
            <ul style="list-style: none; padding: 0; margin-bottom: 1.2rem; font-size: 0.85rem; color: var(--text-dark);">
                <li style="margin-bottom: 0.4rem;"><strong>Duration:</strong> 45 Working Days</li>
                <li style="margin-bottom: 0.4rem;"><strong>Credits:</strong> 45–60 Hours</li>
                <li style="margin-bottom: 0.4rem;"><strong>Focus:</strong> Fund Management, PR</li>
            </ul>
            <a href="internship_details.php?track=skillsphere" class="btn btn-outline" style="width: 100%; border-radius: 8px; font-size: 0.9rem; padding: 0.6rem;">View Full Details</a>
        </div>

        <!-- QuickPro -->
        <div class="feature-card" style="padding: 2rem; border-radius: 16px; background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); box-shadow: var(--shadow-md); transition: transform 0.3s; display: flex; flex-direction: column;">
            <div class="icon-wrapper" style="width: 50px; height: 50px; background: linear-gradient(135deg, #38b6ff, #004aad); border-radius: 12px; color: white; display: flex; justify-content: center; align-items: center; font-size: 1.2rem; margin-bottom: 1.2rem;">
                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
            </div>
            <h3 style="font-size: 1.3rem; margin-bottom: 0.8rem; color: var(--text-dark);">QuickPro Internship</h3>
            <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 1.2rem; flex-grow: 1;">
                A fast-paced theoretical internship focused on building strong foundational knowledge of NGO operations, legal compliance, and career readiness.
            </p>
            <ul style="list-style: none; padding: 0; margin-bottom: 1.2rem; font-size: 0.85rem; color: var(--text-dark);">
                <li style="margin-bottom: 0.4rem;"><strong>Duration:</strong> 21 Working Days</li>
                <li style="margin-bottom: 0.4rem;"><strong>Credits:</strong> 30–45 Hours</li>
                <li style="margin-bottom: 0.4rem;"><strong>Mode:</strong> Hybrid Learning</li>
            </ul>
            <a href="internship_details.php?track=quickpro" class="btn btn-outline" style="width: 100%; border-radius: 8px; font-size: 0.9rem; padding: 0.6rem;">View Full Details</a>
        </div>

        <!-- DevSphere -->
        <div class="feature-card" style="padding: 2rem; border-radius: 16px; background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); box-shadow: var(--shadow-md); transition: transform 0.3s; display: flex; flex-direction: column;">
            <div class="icon-wrapper" style="width: 50px; height: 50px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 12px; color: white; display: flex; justify-content: center; align-items: center; font-size: 1.2rem; margin-bottom: 1.2rem;">
                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="2" y1="20" x2="22" y2="20"></line></svg>
            </div>
            <h3 style="font-size: 1.3rem; margin-bottom: 0.8rem; color: var(--text-dark);">DevSphere Internship</h3>
            <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 1.2rem; flex-grow: 1;">
                A technical arena for developers and designers to work directly on Rigel’s official platforms, building web solutions, fixing bugs, and improving UI/UX.
            </p>
            <ul style="list-style: none; padding: 0; margin-bottom: 1.2rem; font-size: 0.85rem; color: var(--text-dark);">
                <li style="margin-bottom: 0.4rem;"><strong>Duration:</strong> 60 Days</li>
                <li style="margin-bottom: 0.4rem;"><strong>Credits:</strong> 120–150 Hours</li>
                <li style="margin-bottom: 0.4rem;"><strong>Skills:</strong> Frontend, Backend, SEO</li>
            </ul>
            <a href="internship_details.php?track=devsphere" class="btn btn-outline" style="width: 100%; border-radius: 8px; font-size: 0.9rem; padding: 0.6rem;">View Full Details</a>
        </div>

        <!-- PsyEdge -->
        <div class="feature-card" style="padding: 2rem; border-radius: 16px; background: var(--glass-bg); backdrop-filter: blur(10px); border: 1px solid var(--border-light); box-shadow: var(--shadow-md); transition: transform 0.3s; display: flex; flex-direction: column;">
            <div class="icon-wrapper" style="width: 50px; height: 50px; background: linear-gradient(135deg, #8b5cf6, #6d28d9); border-radius: 12px; color: white; display: flex; justify-content: center; align-items: center; font-size: 1.2rem; margin-bottom: 1.2rem;">
                <svg width="1.5em" height="1.5em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24 2.5 2.5 0 0 1 1.98-3A2.5 2.5 0 0 1 9.5 2Z"></path><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24 2.5 2.5 0 0 0-1.98-3A2.5 2.5 0 0 0 14.5 2Z"></path></svg>
            </div>
            <h3 style="font-size: 1.3rem; margin-bottom: 0.8rem; color: var(--text-dark);">PsyEdge Internship</h3>
            <p style="font-size: 0.9rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 1.2rem; flex-grow: 1;">
                A research-oriented internship for Psychology and Sociology students involving survey design, handling datasets, interpreting results, and preparing reports.
            </p>
            <ul style="list-style: none; padding: 0; margin-bottom: 1.2rem; font-size: 0.85rem; color: var(--text-dark);">
                <li style="margin-bottom: 0.4rem;"><strong>Duration:</strong> 60 Working Days</li>
                <li style="margin-bottom: 0.4rem;"><strong>Credits:</strong> 120–150 Hours</li>
                <li style="margin-bottom: 0.4rem;"><strong>Work:</strong> Field + Online Surveys</li>
            </ul>
            <a href="internship_details.php?track=psyedge" class="btn btn-outline" style="width: 100%; border-radius: 8px; font-size: 0.9rem; padding: 0.6rem;">View Full Details</a>
        </div>
    </div>
</div>
