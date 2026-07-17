<?php
/**
 * Auth Guard for Hotel Operations Panel
 * Only allows hotel_manager and admin roles.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$_role = $_SESSION['role'] ?? '';
if (!isset($_SESSION['user_id']) || !in_array($_role, ['hotel_manager', 'admin'])) {
    // Destroy session cleanly
    session_unset();
    session_destroy();
    header('Location: login.php?denied=1');
    exit();
}
?>
