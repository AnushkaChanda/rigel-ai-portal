document.addEventListener('DOMContentLoaded', () => {
    // Theme toggle functionality
    const themeToggle = document.getElementById('themeToggle');
    const moonIcon = document.getElementById('moonIcon');
    const sunIcon = document.getElementById('sunIcon');
    
    // Check local storage for theme
    if (localStorage.getItem('theme') === 'light') {
        document.body.classList.add('light-theme');
        if (moonIcon && sunIcon) {
            moonIcon.style.opacity = '0';
            sunIcon.style.opacity = '1';
        }
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('light-theme');
            let theme = 'dark';
            if (document.body.classList.contains('light-theme')) {
                theme = 'light';
                if (moonIcon && sunIcon) {
                    moonIcon.style.opacity = '0';
                    sunIcon.style.opacity = '1';
                }
            } else {
                if (moonIcon && sunIcon) {
                    moonIcon.style.opacity = '1';
                    sunIcon.style.opacity = '0';
                }
            }
            localStorage.setItem('theme', theme);
        });
    }

    // Scroll effect for navbar glassmorphism
    window.addEventListener('scroll', () => {
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }
    });

    // Mobile menu toggle functionality
    const mobileToggle = document.querySelector('.mobile-toggle');
    const navLinks = document.querySelector('.nav-links');
    const navActions = document.querySelector('.nav-actions');

    if (mobileToggle) {
        mobileToggle.addEventListener('click', () => {
            // Setup simple toggle behavior for mobile
            const expanded = navLinks.style.display === 'flex';
            if (expanded) {
                navLinks.style.display = 'none';
                navActions.style.display = 'none';
            } else {
                navLinks.style.display = 'flex';
                navLinks.style.flexDirection = 'column';
                navLinks.style.position = 'absolute';
                navLinks.style.top = '70px';
                navLinks.style.right = '20px';
                navLinks.style.background = 'white';
                navLinks.style.padding = '1rem';
                navLinks.style.borderRadius = '10px';
                navLinks.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1)';
                
                navActions.style.display = 'flex';
                navActions.style.flexDirection = 'column';
                navActions.style.position = 'absolute';
                navActions.style.top = '250px';
                navActions.style.right = '20px';
                navActions.style.background = 'white';
                navActions.style.padding = '1rem';
                navActions.style.borderRadius = '10px';
                navActions.style.boxShadow = '0 10px 15px -3px rgba(0, 0, 0, 0.1)';
            }
        });
    }
});
