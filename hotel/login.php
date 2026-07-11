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
    } elseif (!checkLoginAttempts($identifier)) {
        $error = 'Too many failed attempts. Please try again after 15 minutes.';
    } else {
        // Support login by email OR mobile
        $identifier_safe = sanitize($identifier);
        $sql = "SELECT id, first_name, last_name, email, password, status
                FROM users
                WHERE email = '$identifier_safe' OR mobile = '$identifier_safe'
                LIMIT 1";

        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            if ($user['status'] !== 'active') {
                $error = 'Your account has been suspended. Please contact support.';
                logLoginAttempt($identifier, false);
            } elseif (password_verify($password, $user['password'])) {
                // Success
                logLoginAttempt($identifier, true);

                $_SESSION['user_id']        = $user['id'];
                $_SESSION['user_name']      = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email']     = $user['email'];
                $_SESSION['user_firstname'] = $user['first_name'];

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
                logLoginAttempt($identifier, false);
            }
        } else {
            $error = 'Invalid email/mobile or password.';
            logLoginAttempt($identifier, false);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login — bookHotel</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
  <!-- Original Login CSS -->
  <link rel="stylesheet" href="../login.css"/>
</head>
<body class="auth-body">

<div class="auth-wrapper">
  <!-- ===== LEFT PANEL ===== -->
  <div class="auth-left d-none d-lg-flex">
    <div class="auth-left-overlay"></div>
    <div class="auth-left-content text-white">
      <h1 class="fw-bold mb-4"><i class="fa fa-hotel me-2 text-warning"></i>bookHotel</h1>
      <h2 class="fw-bold mb-3">Your Next Adventure Awaits</h2>
      <p class="mb-4 text-white-50">Join millions of travelers who trust bookHotel for seamless hotel bookings at the best prices.</p>
      
      <div class="auth-features">
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="fa fa-shield-alt"></i></div>
          <div>
            <h6 class="mb-0 fw-bold">Secure Booking</h6>
            <small class="text-white-50">256-bit encrypted security</small>
          </div>
        </div>
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="fa fa-tags"></i></div>
          <div>
            <h6 class="mb-0 fw-bold">Best Prices</h6>
            <small class="text-white-50">Guaranteed price match</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ===== RIGHT PANEL ===== -->
  <div class="auth-right">
    <div class="auth-form-box">
      
      <div class="text-center mb-4">
        <h2 class="fw-bold">Welcome Back</h2>
        <p class="text-muted">Sign in to continue booking amazing stays</p>
      </div>

      <?php if ($error): ?>
      <div class="alert alert-danger" role="alert">
        <i class="fa fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <?php if (isset($_GET['registered']) && $_GET['registered'] == 1): ?>
      <div class="alert alert-success" role="alert">
        <i class="fa fa-check-circle me-2"></i>Account created successfully! Please sign in.
      </div>
      <?php endif; ?>

      <form method="POST" action="login.php" id="loginForm" novalidate>
        
        <!-- Email/Mobile -->
        <div class="mb-3">
          <label class="auth-label" for="identifier">EMAIL OR MOBILE NUMBER</label>
          <div class="input-group auth-input-group">
            <span class="input-group-text"><i class="fa fa-user"></i></span>
            <input type="text" id="identifier" name="identifier" class="form-control auth-input" placeholder="Enter email or mobile" value="<?= htmlspecialchars($email_val) ?>" required/>
          </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center">
            <label class="auth-label mb-0" for="password">PASSWORD</label>
            <a href="forgot-password.php" class="forgot-link">Forgot password?</a>
          </div>
          <div class="input-group auth-input-group mt-1">
            <span class="input-group-text"><i class="fa fa-lock"></i></span>
            <input type="password" id="password" name="password" class="form-control auth-input" placeholder="Enter your password" required/>
            <span class="input-group-text" id="togglePassword"><i class="fa fa-eye" id="toggleIcon"></i></span>
          </div>
        </div>

        <!-- Remember Me -->
        <div class="mb-4 form-check">
          <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me" <?= !empty($email_val) ? 'checked' : '' ?>>
          <label class="form-check-label text-muted" style="font-size: 0.85rem;" for="remember_me">Remember me for 30 days</label>
        </div>

        <!-- Sign In Button -->
        <button type="submit" class="auth-submit-btn w-100 py-2 mb-4" id="loginBtn">
          Sign In <i class="fa fa-arrow-right ms-2"></i>
        </button>

        <div class="auth-divider">
          <span>or sign in with</span>
        </div>

        <!-- Social Buttons -->
        <div class="row g-2 mb-4">
          <div class="col-6">
            <button type="button" class="social-btn w-100">
              <i class="fab fa-google text-danger me-2"></i> Google
            </button>
          </div>
          <div class="col-6">
            <button type="button" class="social-btn w-100">
              <i class="fab fa-facebook text-primary me-2"></i> Facebook
            </button>
          </div>
        </div>

        <p class="text-center text-muted mb-0" style="font-size: 0.9rem;">
          Don't have an account? <a href="signup.php" class="text-danger fw-bold text-decoration-none">Create Account</a>
        </p>

      </form>
    </div>
  </div>
</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Simple Password Toggle
  const toggleBtn = document.getElementById('togglePassword');
  const pwdInput = document.getElementById('password');
  const toggleIcon = document.getElementById('toggleIcon');
  if(toggleBtn && pwdInput) {
    toggleBtn.addEventListener('click', () => {
      const type = pwdInput.getAttribute('type') === 'password' ? 'text' : 'password';
      pwdInput.setAttribute('type', type);
      toggleIcon.className = type === 'password' ? 'fa fa-eye' : 'fa fa-eye-slash';
    });
  }
</script>
</body>
</html>

