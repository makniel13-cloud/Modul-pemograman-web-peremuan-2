<?php
require_once(__DIR__ . "/../config.php");
$current_theme = get_active_theme();
$page_title = $page_title ?? 'SIM RS - Data Alat Elektromedis';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;600;700&family=Inter:wght@400;500;600;700&family=Nunito+Sans:wght@400;500;600;700&family=Poppins:wght@400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    
    <!-- Template Style -->
    <link rel="stylesheet" href="assets/style.css">
    
    <!-- SIMRS Unified Theme Stylesheet -->
    <link rel="stylesheet" href="assets/app-theme.css">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/hand-heart.png">
</head>
<body class="<?php echo htmlspecialchars($current_theme); ?>">
