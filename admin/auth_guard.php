<?php
/**
 * Auth Guard for Admin Panel
 * Completely independent from User and Hotel Manager panels
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}
