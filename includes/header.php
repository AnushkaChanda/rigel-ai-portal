<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_url = (basename(dirname($_SERVER['PHP_SELF'])) == 'pages') ? '../' : ''; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rigel Foundation | AI Career Portal</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS (with cache buster to ensure theme updates apply) -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/auth.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/dashboard.css?v=<?php echo time(); ?>">
    
    <!-- PWA Settings -->
    <link rel="manifest" href="<?php echo $base_url; ?>manifest.json">
    <meta name="theme-color" content="#0a0f1c">
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('<?php echo $base_url; ?>sw.js')
                    .then(registration => console.log('SW registered'))
                    .catch(err => console.log('SW registration failed', err));
            });
        }
    </script>
    <script type="text/javascript">
        // Initialize Google Translate
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,hi,es,fr,de,zh-CN,ar,bn,ru,pt,ja,ko,it,nl,ta,te,mr,gu,kn,ml,pa,or,sa', // Expanded languages
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }

        // Custom function to trigger translation
        function changeLanguage(langCode, langName) {
            const select = document.querySelector('.goog-te-combo');
            if (select) {
                select.value = langCode;
                select.dispatchEvent(new Event('change'));
            }
            // Update button text
            const btnText = document.getElementById('selectedLang');
            if (btnText) btnText.innerText = langName;
            
            // Close dropdown
            document.querySelector('.lang-content').style.display = 'none';
        }

        // Toggle custom dropdown
        function toggleLangDropdown() {
            const content = document.querySelector('.lang-content:not(.profile-content)');
            if (content) content.style.display = content.style.display === 'block' ? 'none' : 'block';
            const profileContent = document.querySelector('.profile-content');
            if (profileContent) profileContent.style.display = 'none';
        }

        function toggleProfileDropdown() {
            const content = document.querySelector('.profile-content');
            if (content) content.style.display = content.style.display === 'block' ? 'none' : 'block';
            const langContent = document.querySelector('.lang-content:not(.profile-content)');
            if (langContent) langContent.style.display = 'none';
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.lang-dropdown')) {
                const contents = document.querySelectorAll('.lang-content');
                contents.forEach(c => c.style.display = 'none');
            }
        });
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
</head>
<body>
    <button id="themeToggle" class="theme-toggle" title="Toggle Theme" style="position: fixed; top: 1.5rem; right: 1.5rem; z-index: 1000; background: var(--bg-card); border: 1px solid var(--border-light); color: var(--text-dark); width: 45px; height: 45px; border-radius: 50%; display: flex; justify-content: center; align-items: center; cursor: pointer; transition: all 0.3s; box-shadow: var(--shadow-sm);">
        <i id="moonIcon" class="fa-solid fa-moon" style="position: absolute; transition: opacity 0.3s;"></i>
        <i id="sunIcon" class="fa-solid fa-sun" style="position: absolute; opacity: 0; transition: opacity 0.3s;"></i>
    </button>
