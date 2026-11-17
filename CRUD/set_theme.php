<?php
/**
 * AJAX / Endpoint to set active theme
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$theme = $_GET['theme'] ?? 'theme-1';
if (in_array($theme, ['theme-1', 'theme-2', 'theme-dark'])) {
    $_SESSION['app_theme'] = $theme;
    setcookie('app_theme', $theme, time() + (86400 * 30), "/");
    echo json_encode(['status' => 'success', 'theme' => $theme]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid theme']);
}
?>