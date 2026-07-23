<?php
/**
 * Hotel Manager Signup has been removed.
 * Please use the main website signup and request admin to assign Hotel Manager role.
 */
session_start();
require_once 'db.php';

if (isset($_SESSION['hm_id'])) {
    header('Location: admin-dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Hotel Manager Access — bookHotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="../login.css"/>
</head>
<body class="auth-body">
<div class="auth-wrapper">
  <div class="auth-left d-none d-lg-flex">
    <div class="auth-left-overlay"></div>
    <div class="auth-left-content text-white">
      <h1 class="fw-bold mb-4"><i class="fa fa-hotel me-2 text-warning"></i>bookHotel</h1>
      <h2 class="fw-bold mb-3">Hotel Operations Panel</h2>
      <p class="mb-4 text-white-50">This portal is for assigned Hotel Managers only.</p>
    </div>
  </div>
  <div class="auth-right">
    <div class="auth-form-box text-center">
      <div class="mb-4">
        <i class="fa fa-user-shield text-primary" style="font-size:3rem"></i>
      </div>
      <h2 class="fw-bold mb-3">Hotel Manager Access</h2>
      <p class="text-muted mb-4">To access the Hotel Operations Portal:</p>
      <div class="text-start p-3 rounded mb-4" style="background:var(--srf);border:1px solid var(--bdr)">
        <ol class="mb-0 ps-3" style="font-size:.9rem;line-height:1.8">
          <li>Create an account on the main website.</li>
          <li>Contact the Platform Admin to request Hotel Manager access.</li>
          <li>Admin will assign you the <strong>Hotel Manager</strong> role.</li>
          <li>Sign in here with the same email and password.</li>
        </ol>
      </div>
      <a href="../signup.php" class="auth-submit-btn w-100 py-2 mb-3 d-block text-decoration-none">
        Create Account <i class="fa fa-arrow-right ms-2"></i>
      </a>
      <p class="text-muted mb-0" style="font-size:.9rem">
        Already have an account? <a href="login.php" class="text-danger fw-bold text-decoration-none">Sign In</a>
      </p>
    </div>
  </div>
</div>
</body>
</html>
