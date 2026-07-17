<?php
/**
 * Auth Guard for Main Admin Panel
 * Ensures the user is logged in AND is an admin.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}
?>
