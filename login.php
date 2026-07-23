<?php
/**
 * Login Page — bookHotel Authentication
 */
session_start();
require_once 'db.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$error   = '';
$success = '';
$email_val = '';

// Handle Remember Me cookie
if (isset($_COOKIE['remember_email'])) {
    $email_val = htmlspecialchars($_COOKIE['remember_email']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier  = trim($_POST['identifier'] ?? '');
    $password    = trim($_POST['password']   ?? '');
    $remember_me = isset($_POST['remember_me']);

    // Basic validation
    if (empty($identifier) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        // Support login by email OR mobile
        $identifier_safe = sanitize($identifier);
        $sql = "SELECT id, first_name, last_name, email, password, status, role
                FROM users
                WHERE email = '$identifier_safe' OR mobile = '$identifier_safe'
                LIMIT 1";

        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            if ($user['status'] !== 'active') {
                $error = 'Your account has been suspended. Please contact support.';
            } elseif (password_verify($password, $user['password'])) {
                // Success
                $_SESSION['user_id']        = $user['id'];
                $_SESSION['user_name']      = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email']     = $user['email'];
                $_SESSION['user_firstname'] = $user['first_name'];
                $_SESSION['role']           = $user['role'];

                // Update last login
                $uid = (int)$user['id'];
                mysqli_query($conn, "UPDATE users SET last_login = NOW() WHERE id = $uid");

                // Remember Me cookie (30 days)
                if ($remember_me) {
                    setcookie('remember_email', $user['email'], time() + (30 * 24 * 60 * 60), '/');
                } else {
                    setcookie('remember_email', '', time() - 3600, '/');
                }

                header('Location: index.php');
                exit();
            } else {
                $error = 'Invalid email/mobile or password.';
            }
        } else {
            $error = 'Invalid email/mobile or password.';
        }
    }
}
