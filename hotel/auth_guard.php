<?php
/**
 * Auth Guard for Hotel Manager Panel
 * Validates role='hotel_manager' from users table
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['hm_id'])) {
    session_unset();
    session_destroy();
    header('Location: login.php?denied=1');
    exit();
}

// Optional: Verify role is still hotel_manager on protected pages
if (isset($conn)) {
    $uid = (int)$_SESSION['hm_id'];
    $role_res = mysqli_query($conn, "SELECT role FROM users WHERE id = $uid LIMIT 1");
    if ($role_res && mysqli_num_rows($role_res) > 0) {
        $role = mysqli_fetch_assoc($role_res)['role'];
        if ($role !== 'hotel_manager') {
            session_unset();
            session_destroy();
            header('Location: login.php?unauthorized=1');
            exit();
        }
    } else {
        session_unset();
        session_destroy();
        header('Location: login.php?denied=1');
        exit();
    }
}
