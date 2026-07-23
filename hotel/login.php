<?php
/**
 * Hotel Manager Login — Uses Main Users Table
 */
session_start();
require_once 'db.php';

if (isset($_SESSION['hm_id'])) {
    header('Location: admin-dashboard.php');
    exit();
}

$error     = '';
$success   = '';
$email_val = '';

if (isset($_COOKIE['hm_remember_email'])) {
    $email_val = htmlspecialchars($_COOKIE['hm_remember_email']);
}

if (isset($_GET['assigned']) && $_GET['assigned'] == 1) {
    $success = 'Your account has been assigned as a Hotel Manager. Please sign in.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier  = trim($_POST['identifier'] ?? '');
    $password    = trim($_POST['password']   ?? '');
    $remember_me = isset($_POST['remember_me']);

    if (empty($identifier) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        $identifier_safe = hm_sanitize($identifier);
        $sql = "SELECT id, first_name, last_name, email, password, role, status
                FROM users
                WHERE email = '$identifier_safe' OR mobile = '$identifier_safe'
                LIMIT 1";

        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            if ($user['role'] !== 'hotel_manager') {
                $error = 'You are not authorized as a Hotel Manager.';
            } elseif ($user['status'] !== 'active') {
                $error = 'Your account has been suspended. Please contact support.';
            } elseif (password_verify($password, $user['password'])) {
                $_SESSION['hm_id']        = $user['id'];
                $_SESSION['hm_name']      = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['hm_email']     = $user['email'];
                $_SESSION['hm_firstname'] = $user['first_name'];

                $uid = (int)$user['id'];
                mysqli_query($conn, "UPDATE users SET last_login = NOW() WHERE id = $uid");

                if ($remember_me) {
                    setcookie('hm_remember_email', $user['email'], time() + (30 * 24 * 60 * 60), '/');
                } else {
                    setcookie('hm_remember_email', '', time() - 3600, '/');
                }

                header('Location: admin-dashboard.php');
                exit();
            } else {
                $error = 'Invalid email/mobile or password.';
            }
        } else {
            $error = 'Invalid email/mobile or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Hotel Manager Login — bookHotel</title>
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
      <p class="mb-4 text-white-50">Manage your properties, bookings, and guests from one powerful dashboard.</p>
      <div class="auth-features">
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="fa fa-shield-alt"></i></div>
          <div>
            <h6 class="mb-0 fw-bold">Secure Access</h6>
            <small class="text-white-50">Protected manager portal</small>
          </div>
        </div>
        <div class="auth-feature-item">
          <div class="auth-feature-icon"><i class="fa fa-tags"></i></div>
          <div>
            <h6 class="mb-0 fw-bold">Real-time Data</h6>
            <small class="text-white-50">Live bookings & analytics</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="auth-right">
    <div class="auth-form-box">
      <div class="text-center mb-4">
        <h2 class="fw-bold">Welcome Back</h2>
        <p class="text-muted">Hotel Manager Sign In</p>
      </div>

      <?php if ($error): ?>
      <div class="alert alert-danger" role="alert">
        <i class="fa fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <?php if ($success): ?>
      <div class="alert alert-success" role="alert">
        <i class="fa fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
      </div>
      <?php endif; ?>

      <form method="POST" action="login.php" id="loginForm" novalidate>
        <div class="mb-3">
          <label class="auth-label" for="identifier">EMAIL OR MOBILE NUMBER</label>
          <div class="input-group auth-input-group">
            <span class="input-group-text"><i class="fa fa-user"></i></span>
            <input type="text" id="identifier" name="identifier" class="form-control auth-input" placeholder="Enter email or mobile" value="<?= htmlspecialchars($email_val) ?>" required/>
          </div>
        </div>

        <div class="mb-3">
          <div class="d-flex justify-content-between align-items-center">
            <label class="auth-label mb-0" for="password">PASSWORD</label>
          </div>
          <div class="input-group auth-input-group mt-1">
            <span class="input-group-text"><i class="fa fa-lock"></i></span>
            <input type="password" id="password" name="password" class="form-control auth-input" placeholder="Enter your password" required/>
            <span class="input-group-text" id="togglePassword"><i class="fa fa-eye" id="toggleIcon"></i></span>
          </div>
        </div>

        <div class="mb-4 form-check">
          <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me" <?= !empty($email_val) ? 'checked' : '' ?>>
          <label class="form-check-label text-muted" style="font-size: 0.85rem;" for="remember_me">Remember me for 30 days</label>
        </div>

        <button type="submit" class="auth-submit-btn w-100 py-2 mb-4" id="loginBtn">
          Sign In <i class="fa fa-arrow-right ms-2"></i>
        </button>

        <p class="text-center text-muted mb-0" style="font-size: 0.9rem;">
          Not a manager? <a href="../login.php" class="text-danger fw-bold text-decoration-none">User Login</a>
        </p>

      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
