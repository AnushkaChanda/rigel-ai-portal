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
    <title>Rigel Foundation | Career Portal</title>
    <meta name="description" content="Rigel Foundation Career Portal — Apply for flagship internship programs with AI-powered interview sessions.">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/dashboard.css?v=<?php echo time(); ?>">
    <!-- PWA -->
    <link rel="manifest" href="<?php echo $base_url; ?>manifest.json">
    <meta name="theme-color" content="#0d2149">
</head>
<body>

<div class="app-layout">

    <!-- ========== MAIN CONTENT ========== -->
    <main class="main-content">
