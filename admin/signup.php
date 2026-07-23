<?php
/**
 * Admin Signup — Independent Panel
 */
session_start();
require_once 'db.php';

if (isset($_SESSION['admin_id'])) {
    header('Location: admin-dashboard.php');
    exit();
}

$error   = '';
$success = '';
$form    = ['full_name' => '', 'email' => '', 'mobile' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name       = trim($_POST['full_name']       ?? '');
    $email           = trim($_POST['email']            ?? '');
    $mobile          = trim($_POST['mobile']           ?? '');
    $password        = $_POST['password']              ?? '';
    $confirm_password = $_POST['confirm_password']    ?? '';

    $form = ['full_name' => $full_name, 'email' => $email, 'mobile' => $mobile];

    if (empty($full_name) || empty($email) || empty($mobile) || empty($password) || empty($confirm_password)) {
        $error = 'All fields are required.';
    } elseif (strlen($full_name) < 3) {
        $error = 'Full name must be at least 3 characters.';
    } elseif (!admin_validateEmail($email)) {
        $error = 'Please enter a valid email address.';
    } elseif (!admin_validateMobile($mobile)) {
        $error = 'Please enter a valid 10-digit mobile number.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = 'Password must contain at least one uppercase letter.';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = 'Password must contain at least one number.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (admin_emailExists($email)) {
        $error = 'This email is already registered. <a href="login.php">Sign in instead</a>.';
    } elseif (admin_mobileExists($mobile)) {
        $error = 'This mobile number is already registered.';
    } else {
        $name_parts = explode(' ', $full_name, 2);
        $first_name = admin_sanitize($name_parts[0]);
        $last_name  = admin_sanitize($name_parts[1] ?? '');
        $email_s    = admin_sanitize($email);
        $mobile_s   = admin_sanitize($mobile);
        $hash       = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $sql = "INSERT INTO admins (first_name, last_name, email, mobile, password, role)
                VALUES ('$first_name', '$last_name', '$email_s', '$mobile_s', '$hash', 'admin')";

        if (mysqli_query($conn, $sql)) {
            header('Location: login.php?registered=1');
            exit();
        } else {
            $error = 'Registration failed. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Signup — bookHotel</title>
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
      <h2 class="fw-bold mb-3">Platform Administration</h2>
      <p class="mb-4 text-white-50">Create an admin account to manage the entire bookHotel platform.</p>
      <div class="auth-features">
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="fa fa-shield-alt"></i></div>
          <div>
            <h6 class="mb-0 fw-bold">Full Control</h6>
            <small class="text-white-50">Manage users, hotels, and settings</small>
          </div>
        </div>
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="fa fa-chart-bar"></i></div>
          <div>
            <h6 class="mb-0 fw-bold">Analytics</h6>
            <small class="text-white-50">Platform-wide insights and reports</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="auth-right">
    <div class="auth-form-box">
      <div class="text-center mb-4">
        <h2 class="fw-bold">Admin Registration</h2>
        <p class="text-muted">Create your admin account</p>
      </div>

      <?php if ($error): ?>
      <div class="alert alert-danger" role="alert">
        <i class="fa fa-exclamation-circle me-2"></i><?= $error ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="signup.php" id="signupForm" novalidate>
        <div class="mb-3">
          <label class="auth-label" for="full_name">FULL NAME</label>
          <div class="input-group auth-input-group">
            <span class="input-group-text"><i class="fa fa-user"></i></span>
            <input type="text" id="full_name" name="full_name" class="form-control auth-input" placeholder="e.g. Admin User" value="<?= htmlspecialchars($form['full_name']) ?>" required/>
          </div>
        </div>

        <div class="row g-2 mb-3">
          <div class="col-md-6">
            <label class="auth-label" for="email">EMAIL ADDRESS</label>
            <div class="input-group auth-input-group">
              <span class="input-group-text"><i class="fa fa-envelope"></i></span>
              <input type="email" id="email" name="email" class="form-control auth-input" placeholder="name@example.com" value="<?= htmlspecialchars($form['email']) ?>" required/>
            </div>
          </div>
          <div class="col-md-6">
            <label class="auth-label" for="mobile">MOBILE NUMBER</label>
            <div class="input-group auth-input-group">
              <span class="input-group-text"><i class="fa fa-phone"></i></span>
              <input type="tel" id="mobile" name="mobile" class="form-control auth-input" placeholder="10-digit number" value="<?= htmlspecialchars($form['mobile']) ?>" required/>
            </div>
          </div>
        </div>

        <div class="row g-2 mb-3">
          <div class="col-md-6">
            <label class="auth-label" for="password">PASSWORD</label>
            <div class="input-group auth-input-group">
              <span class="input-group-text"><i class="fa fa-lock"></i></span>
              <input type="password" id="password" name="password" class="form-control auth-input" placeholder="Min 8 chars" required/>
            </div>
          </div>
          <div class="col-md-6">
            <label class="auth-label" for="confirm_password">CONFIRM PASSWORD</label>
            <div class="input-group auth-input-group">
              <span class="input-group-text"><i class="fa fa-check-circle"></i></span>
              <input type="password" id="confirm_password" name="confirm_password" class="form-control auth-input" placeholder="Repeat password" required/>
            </div>
          </div>
        </div>

        <div class="mb-4 form-check">
          <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
          <label class="form-check-label text-muted" style="font-size: 0.85rem;" for="terms">I agree to the <a href="#" class="text-danger">Terms & Conditions</a></label>
        </div>

        <button type="submit" class="auth-submit-btn w-100 py-2 mb-4" id="signupBtn">
          Create Admin Account <i class="fa fa-arrow-right ms-2"></i>
        </button>

        <p class="text-center text-muted mb-0" style="font-size: 0.9rem;">
          Already have an account? <a href="login.php" class="text-danger fw-bold text-decoration-none">Sign In</a>
        </p>

      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('signupForm').addEventListener('submit', function(e) {
    const pwd = document.getElementById('password').value;
    const cpwd = document.getElementById('confirm_password').value;
    if(pwd !== cpwd && pwd !== '') {
      e.preventDefault();
      alert('Passwords do not match!');
    }
  });
</script>
</body>
</html>
