<?php
/**
 * Signup Page — bookHotel Authentication
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
$form    = ['full_name' => '', 'email' => '', 'mobile' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name       = trim($_POST['full_name']       ?? '');
    $email           = trim($_POST['email']            ?? '');
    $mobile          = trim($_POST['mobile']           ?? '');
    $password        = $_POST['password']              ?? '';
    $confirm_password = $_POST['confirm_password']    ?? '';

    $form = ['full_name' => $full_name, 'email' => $email, 'mobile' => $mobile];

    // ---- Validation ----
    if (empty($full_name) || empty($email) || empty($mobile) || empty($password) || empty($confirm_password)) {
        $error = 'All fields are required.';
    } elseif (strlen($full_name) < 3) {
        $error = 'Full name must be at least 3 characters.';
    } elseif (!validateEmail($email)) {
        $error = 'Please enter a valid email address.';
    } elseif (!validateMobile($mobile)) {
        $error = 'Please enter a valid 10-digit mobile number.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = 'Password must contain at least one uppercase letter.';
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = 'Password must contain at least one number.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } elseif (emailExists($email)) {
        $error = 'This email is already registered. <a href="login.php">Sign in instead</a>.';
    } elseif (mobileExists($mobile)) {
        $error = 'This mobile number is already registered.';
    } else {
        // Split full name into first + last
        $name_parts = explode(' ', $full_name, 2);
        $first_name = sanitize($name_parts[0]);
        $last_name  = sanitize($name_parts[1] ?? '');
        $email_s    = sanitize($email);
        $mobile_s   = sanitize($mobile);
        $hash       = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

        $sql = "INSERT INTO users (first_name, last_name, email, mobile, password)
                VALUES ('$first_name', '$last_name', '$email_s', '$mobile_s', '$hash')";

        if (mysqli_query($conn, $sql)) {
            header('Location: login.php?registered=1');
            exit();
        } else {
            $error = 'Registration failed. Please try again later.';
        }
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Create Account — bookHotel</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <!-- Original Login CSS -->
  <link rel="stylesheet" href="login.css"/>
</head>
<body class="auth-body">

<div class="auth-wrapper">
  <!-- ===== LEFT PANEL ===== -->
  <div class="auth-left d-none d-lg-flex">
    <div class="auth-left-overlay"></div>
    <div class="auth-left-content text-white">
      <h1 class="fw-bold mb-4"><i class="fa fa-hotel me-2 text-warning"></i>bookHotel</h1>
      <h2 class="fw-bold mb-3">Start Your Journey Today</h2>
      <p class="mb-4 text-white-50">Create an account and unlock exclusive deals, personalized recommendations, and much more.</p>
      
      <div class="auth-features">
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="fa fa-gift"></i></div>
          <div>
            <h6 class="mb-0 fw-bold">Member Only Deals</h6>
            <small class="text-white-50">Get up to 50% off on premium stays</small>
          </div>
        </div>
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="fa fa-headset"></i></div>
          <div>
            <h6 class="mb-0 fw-bold">24/7 Support</h6>
            <small class="text-white-50">We're always here to help you</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== RIGHT PANEL ===== -->
  <div class="auth-right">
    <div class="auth-form-box">
      
      <div class="text-center mb-4">
        <h2 class="fw-bold">Create Account</h2>
        <p class="text-muted">Sign up to get started</p>
      </div>

      <?php if ($error): ?>
      <div class="alert alert-danger" role="alert">
        <i class="fa fa-exclamation-circle me-2"></i><?= $error ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="signup.php" id="signupForm" novalidate>
        
        <!-- Name -->
        <div class="mb-3">
          <label class="auth-label" for="full_name">FULL NAME</label>
          <div class="input-group auth-input-group">
            <span class="input-group-text"><i class="fa fa-user"></i></span>
            <input type="text" id="full_name" name="full_name" class="form-control auth-input" placeholder="e.g. John Doe" value="<?= htmlspecialchars($form['full_name']) ?>" required/>
          </div>
        </div>

        <div class="row g-2 mb-3">
          <!-- Email -->
          <div class="col-md-6">
            <label class="auth-label" for="email">EMAIL ADDRESS</label>
            <div class="input-group auth-input-group">
              <span class="input-group-text"><i class="fa fa-envelope"></i></span>
              <input type="email" id="email" name="email" class="form-control auth-input" placeholder="name@example.com" value="<?= htmlspecialchars($form['email']) ?>" required/>
            </div>
          </div>
          <!-- Mobile -->
          <div class="col-md-6">
            <label class="auth-label" for="mobile">MOBILE NUMBER</label>
            <div class="input-group auth-input-group">
              <span class="input-group-text"><i class="fa fa-phone"></i></span>
              <input type="tel" id="mobile" name="mobile" class="form-control auth-input" placeholder="10-digit number" value="<?= htmlspecialchars($form['mobile']) ?>" required/>
            </div>
          </div>
        </div>

        <div class="row g-2 mb-3">
          <!-- Password -->
          <div class="col-md-6">
            <label class="auth-label" for="password">PASSWORD</label>
            <div class="input-group auth-input-group">
              <span class="input-group-text"><i class="fa fa-lock"></i></span>
              <input type="password" id="password" name="password" class="form-control auth-input" placeholder="Min 8 chars" required/>
            </div>
          </div>
          <!-- Confirm Password -->
          <div class="col-md-6">
            <label class="auth-label" for="confirm_password">CONFIRM PASSWORD</label>
            <div class="input-group auth-input-group">
              <span class="input-group-text"><i class="fa fa-check-circle"></i></span>
              <input type="password" id="confirm_password" name="confirm_password" class="form-control auth-input" placeholder="Repeat password" required/>
            </div>
          </div>
        </div>

        <!-- Terms & Conditions -->
        <div class="mb-4 form-check">
          <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
          <label class="form-check-label text-muted" style="font-size: 0.85rem;" for="terms">I agree to the <a href="#" class="text-danger">Terms & Conditions</a></label>
        </div>

        <!-- Create Account Button -->
        <button type="submit" class="auth-submit-btn w-100 py-2 mb-4" id="signupBtn">
          Create Account <i class="fa fa-arrow-right ms-2"></i>
        </button>

        <p class="text-center text-muted mb-0" style="font-size: 0.9rem;">
          Already have an account? <a href="login.php" class="text-danger fw-bold text-decoration-none">Sign In</a>
        </p>

      </form>
    </div>
  </div>
</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Simple validation check before submitting
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
