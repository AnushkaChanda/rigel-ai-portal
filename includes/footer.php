    </main><!-- end .main-content -->
</div><!-- end .app-layout -->

<!-- ========== BOTTOM NAVIGATION FOOTER ========== -->
<footer class="bottom-nav-footer">
    <div class="bottom-nav-inner">

        <!-- Brand Column -->
        <div class="bottom-nav-brand">
            <div class="bottom-nav-brand-row">
                <img src="<?php echo $base_url; ?>images/rigel_logo.png" alt="Rigel Foundation Logo" class="bottom-nav-logo">
                <div>
                    <span class="bottom-nav-brand-title">Rigel Career Portal</span>
                    <span class="bottom-nav-brand-sub">Rigel Foundation</span>
                </div>
            </div>
            <p class="bottom-nav-tagline">A Government of India Registered<br>Non-Profit Organisation<br>Section 8 &bull; Licence No. 151249</p>
            <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="<?php echo $base_url; ?>pages/login.php" class="bottom-nav-admin-link"><i class="fa-solid fa-lock"></i> Admin Login</a>
            <?php endif; ?>
        </div>

        <!-- Navigation Column -->
        <div class="bottom-nav-col">
            <h4 class="bottom-nav-heading">Navigation</h4>
            <ul>
                <li><a href="<?php echo $base_url; ?>index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"><i class="fa-solid fa-house"></i> Home</a></li>
                <li><a href="<?php echo $base_url; ?>pages/team.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'team.php' ? 'active' : ''; ?>"><i class="fa-solid fa-users"></i> Our Team</a></li>
                <?php if (isset($_SESSION['user_id']) && in_array($_SESSION['account_type'] ?? '', ['admin', 'superadmin'])): ?>
                <li><a href="<?php echo $base_url; ?>pages/dashboard.php"><i class="fa-solid fa-gauge-high"></i> Dashboard</a></li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Quick Links Column -->
        <div class="bottom-nav-col">
            <h4 class="bottom-nav-heading">Quick Links</h4>
            <ul>
                <li><a href="https://www.rigelfoundation.org.in" target="_blank"><i class="fa-solid fa-globe"></i> Main Website</a></li>
                <li><a href="mailto:info@rigelfoundation.org.in"><i class="fa-solid fa-envelope"></i> Contact Us</a></li>
            </ul>
        </div>

        <!-- Social Column -->
        <div class="bottom-nav-col">
            <h4 class="bottom-nav-heading">Follow Us</h4>
            <ul>
                <li><a href="https://www.facebook.com/rigelfoundation/" target="_blank"><i class="fa-brands fa-facebook"></i> Facebook</a></li>
                <li><a href="https://www.instagram.com/rigelfoundation/" target="_blank"><i class="fa-brands fa-instagram"></i> Instagram</a></li>
                <li><a href="https://www.linkedin.com/company/rigelfoundation/" target="_blank"><i class="fa-brands fa-linkedin"></i> LinkedIn</a></li>
            </ul>
        </div>

    </div>

    <!-- Bottom Bar -->
    <div class="bottom-nav-bar">
        <p>&copy; <?php echo date("Y"); ?> Rigel Foundation. All rights reserved. &mdash; <a href="https://www.rigelfoundation.org.in" target="_blank">www.rigelfoundation.org.in</a></p>
    </div>
</footer>

</body>
</html>
