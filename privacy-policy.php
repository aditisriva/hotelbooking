<?php
session_start();
$is_logged_in   = isset($_SESSION['user_id']);
$user_firstname = $is_logged_in ? htmlspecialchars($_SESSION['user_firstname'] ?? $_SESSION['user_name'] ?? 'User') : '';
$user_initial   = $is_logged_in ? strtoupper(substr($_SESSION['user_firstname'] ?? $_SESSION['user_name'] ?? 'U', 0, 1)) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Privacy Policy — bookHotel</title>
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%231a56db'/%3E%3Ctext x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-size='18' font-family='system-ui' fill='%23f59e0b'%3E&#x1F3E8;%3C/text%3E%3C/svg%3E"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" crossorigin="anonymous"/>
  <link rel="stylesheet" href="style.css"/>
  <link rel="stylesheet" href="legal.css"/>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand fw-800 fs-4" href="index.php"><i class="bi bi-building-fill text-warning me-1"></i>bookHotel</a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="hotels.php">Hotels</a></li>
        <li class="nav-item"><a class="nav-link" href="destinations.php">Destinations</a></li>
        <li class="nav-item"><a class="nav-link" href="my-bookings.php">My Bookings</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item ms-lg-3" id="navAuthSlot">
          <?php if ($is_logged_in): ?>
          <div class="dropdown">
            <a class="btn btn-warning btn-sm px-3 dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
              <span class="rounded-circle bg-white text-dark d-inline-flex align-items-center justify-content-center flex-shrink-0" style="width:26px;height:26px;font-size:0.7rem;font-weight:700;"><?= $user_initial ?></span>
              <span><?= $user_firstname ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person me-2"></i>My Profile</a></li>
              <li><a class="dropdown-item" href="my-bookings.php"><i class="bi bi-calendar-check me-2"></i>My Bookings</a></li>
              <li><a class="dropdown-item" href="wishlist.php"><i class="bi bi-heart me-2"></i>Wishlist</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
            </ul>
          </div>
          <?php else: ?>
          <a class="btn btn-outline-warning btn-sm px-3" href="login.php">Login / Sign Up</a>
          <?php endif; ?>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="lg-hero">
  <div class="lg-hero__overlay"></div>
  <div class="container position-relative" style="z-index:2">
    <nav aria-label="breadcrumb" class="mb-3">
      <ol class="breadcrumb lg-breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Privacy Policy</li>
      </ol>
    </nav>
    <div class="lg-hero__badge"><i class="bi bi-shield-lock-fill me-2"></i>Your Privacy Matters</div>
    <h1 class="lg-hero__title">Privacy Policy</h1>
    <p class="lg-hero__sub">We are committed to protecting your personal data. This policy explains how we collect, use, and safeguard your information when you use bookHotel.</p>
    <div class="lg-hero__meta">
      <div class="lg-hero__meta-item"><i class="bi bi-calendar3"></i> Last Updated: July 2026</div>
      <div class="lg-hero__meta-item"><i class="bi bi-shield-fill-check"></i> GDPR & IT Act Compliant</div>
      <div class="lg-hero__meta-item"><i class="bi bi-lock-fill"></i> Data Encrypted & Secure</div>
    </div>
  </div>
</section>

<!-- LAYOUT -->
<div class="lg-layout">
  <div class="container">
    <div class="lg-layout__inner">

      <!-- SIDEBAR -->
      <aside>
        <div class="lg-sidebar">
          <div class="lg-sidebar__head"><i class="bi bi-list-ul"></i> Contents</div>
          <ul class="lg-nav">
            <li><a href="#pp-collect" class="active"><i class="bi bi-database-fill"></i> Information We Collect</a></li>
            <li><a href="#pp-personal"><i class="bi bi-person-fill"></i> Personal Data</a></li>
            <li><a href="#pp-booking"><i class="bi bi-calendar2-check"></i> Booking Information</a></li>
            <li><a href="#pp-payment"><i class="bi bi-credit-card"></i> Payment Information</a></li>
            <li><a href="#pp-usage"><i class="bi bi-bar-chart-fill"></i> How We Use Data</a></li>
            <li><a href="#pp-protection"><i class="bi bi-shield-fill-check"></i> Data Protection</a></li>
            <li><a href="#pp-third"><i class="bi bi-globe"></i> Third Party Services</a></li>
            <li><a href="#pp-rights"><i class="bi bi-person-check"></i> Your Rights</a></li>
            <li><a href="#pp-contact"><i class="bi bi-envelope-fill"></i> Contact Us</a></li>
          </ul>
        </div>
      </aside>

      <!-- MAIN CONTENT -->
      <main>
        <div class="lg-updated-card">
          <i class="bi bi-clock-fill"></i>
          <div>
            <div class="lg-updated-card__text">Last Updated: July 2026</div>
            <div class="lg-updated-card__sub">This policy applies to all personal data processed through bookHotel platforms and services.</div>
          </div>
        </div>

        <!-- 1 -->
        <div class="lg-section" id="pp-collect">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--blue"><i class="bi bi-database-fill"></i></div>
            <div><h2 class="lg-section__title">Information We Collect</h2><div class="lg-section__num">Section 01</div></div>
          </div>
          <div class="lg-body">
            <p>bookHotel collects information to provide, improve, and personalise our hotel booking services. We collect information in the following ways:</p>
            <ul class="lg-list">
              <li><i class="bi bi-person-fill"></i> <strong>Account registration:</strong> Name, email address, phone number, and password</li>
              <li><i class="bi bi-laptop"></i> <strong>Device information:</strong> IP address, browser type, operating system, and device identifiers</li>
              <li><i class="bi bi-geo-alt-fill"></i> <strong>Location data:</strong> Approximate location based on IP address or GPS (with your consent)</li>
              <li><i class="bi bi-cursor-fill"></i> <strong>Usage data:</strong> Pages visited, search queries, clicked hotels, and session duration</li>
              <li><i class="bi bi-chat-fill"></i> <strong>Communications:</strong> Emails, chat messages, or support tickets you send us</li>
            </ul>
          </div>
        </div>

        <!-- 2 -->
        <div class="lg-section" id="pp-personal">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--green"><i class="bi bi-person-fill"></i></div>
            <div><h2 class="lg-section__title">Personal Data</h2><div class="lg-section__num">Section 02</div></div>
          </div>
          <div class="lg-body">
            <p>Personal data refers to any information that can identify you directly or indirectly. We process the following categories of personal data:</p>
            <div class="lg-table-wrap">
              <table class="lg-table">
                <thead><tr><th>Data Type</th><th>Purpose</th><th>Retention</th></tr></thead>
                <tbody>
                  <tr><td>Name & Email</td><td>Account management, communications</td><td>Duration of account</td></tr>
                  <tr><td>Phone Number</td><td>Booking confirmations, OTP verification</td><td>Duration of account</td></tr>
                  <tr><td>Date of Birth</td><td>Age verification, personalisation</td><td>Duration of account</td></tr>
                  <tr><td>Travel Preferences</td><td>Personalised recommendations</td><td>2 years</td></tr>
                </tbody>
              </table>
            </div>
            <p>We process personal data only where we have a lawful basis to do so, such as your consent, contractual necessity, or our legitimate business interests.</p>
          </div>
        </div>

        <!-- 3 -->
        <div class="lg-section" id="pp-booking">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--gold"><i class="bi bi-calendar2-check-fill"></i></div>
            <div><h2 class="lg-section__title">Booking Information</h2><div class="lg-section__num">Section 03</div></div>
          </div>
          <div class="lg-body">
            <p>When you make a hotel booking, we collect and store the following booking-related information:</p>
            <ul class="lg-list">
              <li><i class="bi bi-building-fill"></i> Hotel name, location, room type, and booking dates</li>
              <li><i class="bi bi-people-fill"></i> Number and names of guests (as provided during checkout)</li>
              <li><i class="bi bi-receipt-cutoff"></i> Booking reference number and transaction history</li>
              <li><i class="bi bi-chat-square-text-fill"></i> Special requests, dietary requirements, or accessibility needs</li>
            </ul>
            <p>Booking data is shared with the hotel property to fulfil your reservation and with our payment processor to complete the transaction. This data is retained for a minimum of 3 years for tax and legal compliance purposes.</p>
          </div>
        </div>

        <!-- 4 -->
        <div class="lg-section" id="pp-payment">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--purple"><i class="bi bi-credit-card-fill"></i></div>
            <div><h2 class="lg-section__title">Payment Information</h2><div class="lg-section__num">Section 04</div></div>
          </div>
          <div class="lg-body">
            <div class="lg-info-box">
              <i class="bi bi-shield-fill-check"></i>
              <div class="lg-info-box__text"><strong>bookHotel does not store your full card details.</strong> All payment information is processed by our PCI-DSS certified payment gateway partners (Razorpay / PayU). Only a masked version of your card number is retained for display purposes.</div>
            </div>
            <p>We store:</p>
            <ul class="lg-list">
              <li><i class="bi bi-check-circle-fill"></i> Transaction reference IDs for reconciliation</li>
              <li><i class="bi bi-check-circle-fill"></i> Partial card details (last 4 digits) for display only</li>
              <li><i class="bi bi-check-circle-fill"></i> Refund status and transaction timestamps</li>
            </ul>
          </div>
        </div>

        <!-- 5 -->
        <div class="lg-section" id="pp-usage">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--teal"><i class="bi bi-bar-chart-fill"></i></div>
            <div><h2 class="lg-section__title">How We Use Your Data</h2><div class="lg-section__num">Section 05</div></div>
          </div>
          <div class="lg-body">
            <p>We use the data we collect for the following purposes:</p>
            <ul class="lg-list">
              <li><i class="bi bi-check2-circle"></i> Processing and managing your hotel bookings</li>
              <li><i class="bi bi-check2-circle"></i> Sending booking confirmations, invoices, and reminders</li>
              <li><i class="bi bi-check2-circle"></i> Personalising your search results and recommendations</li>
              <li><i class="bi bi-check2-circle"></i> Improving platform features and user experience</li>
              <li><i class="bi bi-check2-circle"></i> Detecting and preventing fraud and abuse</li>
              <li><i class="bi bi-check2-circle"></i> Complying with legal and regulatory obligations</li>
              <li><i class="bi bi-check2-circle"></i> Sending promotional offers (only with your consent)</li>
            </ul>
          </div>
        </div>

        <!-- 6 -->
        <div class="lg-section" id="pp-protection">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--green"><i class="bi bi-shield-fill-check"></i></div>
            <div><h2 class="lg-section__title">Data Protection</h2><div class="lg-section__num">Section 06</div></div>
          </div>
          <div class="lg-body">
            <p>We implement industry-standard security measures to protect your personal data from unauthorised access, disclosure, alteration, or destruction:</p>
            <ul class="lg-list">
              <li><i class="bi bi-lock-fill"></i> All data is encrypted in transit using TLS 1.3</li>
              <li><i class="bi bi-server"></i> Data at rest is encrypted using AES-256</li>
              <li><i class="bi bi-person-badge-fill"></i> Access to personal data is restricted to authorised personnel only</li>
              <li><i class="bi bi-clock-history"></i> Regular security audits and vulnerability assessments</li>
              <li><i class="bi bi-cloud-fill"></i> Data stored on ISO 27001-certified cloud infrastructure</li>
            </ul>
            <div class="lg-warn-box">
              <i class="bi bi-exclamation-triangle-fill"></i>
              <div class="lg-warn-box__text">No method of transmission over the internet is 100% secure. While we strive to protect your data, we cannot guarantee absolute security. Please use a strong, unique password for your account.</div>
            </div>
          </div>
        </div>

        <!-- 7 -->
        <div class="lg-section" id="pp-third">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--indigo"><i class="bi bi-globe"></i></div>
            <div><h2 class="lg-section__title">Third Party Services</h2><div class="lg-section__num">Section 07</div></div>
          </div>
          <div class="lg-body">
            <p>bookHotel works with trusted third-party partners to deliver our services. We share your data with partners only as necessary:</p>
            <div class="lg-table-wrap">
              <table class="lg-table">
                <thead><tr><th>Partner Type</th><th>Data Shared</th><th>Purpose</th></tr></thead>
                <tbody>
                  <tr><td>Hotel Properties</td><td>Guest details, booking info</td><td>Fulfil reservation</td></tr>
                  <tr><td>Payment Gateways</td><td>Transaction data</td><td>Process payments</td></tr>
                  <tr><td>Analytics (Google)</td><td>Anonymised usage data</td><td>Platform improvement</td></tr>
                  <tr><td>Email Service</td><td>Email address</td><td>Send confirmations</td></tr>
                </tbody>
              </table>
            </div>
            <p>We do not sell, rent, or trade your personal data to third parties for marketing purposes.</p>
          </div>
        </div>

        <!-- 8 -->
        <div class="lg-section" id="pp-rights">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--pink"><i class="bi bi-person-check-fill"></i></div>
            <div><h2 class="lg-section__title">Your Rights</h2><div class="lg-section__num">Section 08</div></div>
          </div>
          <div class="lg-body">
            <p>You have the following rights regarding your personal data:</p>
            <ul class="lg-list">
              <li><i class="bi bi-eye-fill"></i> <strong>Right to Access:</strong> Request a copy of the personal data we hold about you</li>
              <li><i class="bi bi-pencil-fill"></i> <strong>Right to Rectification:</strong> Correct inaccurate or incomplete data</li>
              <li><i class="bi bi-trash-fill"></i> <strong>Right to Erasure:</strong> Request deletion of your personal data (subject to legal obligations)</li>
              <li><i class="bi bi-slash-circle-fill"></i> <strong>Right to Restrict Processing:</strong> Limit how we use your data</li>
              <li><i class="bi bi-box-arrow-right"></i> <strong>Right to Data Portability:</strong> Receive your data in a structured, machine-readable format</li>
              <li><i class="bi bi-hand-index-fill"></i> <strong>Right to Object:</strong> Opt out of direct marketing or profiling</li>
            </ul>
            <p>To exercise any of these rights, please contact our Data Protection Officer at <a href="mailto:privacy@bookhotel.com">privacy@bookhotel.com</a>.</p>
          </div>
        </div>

        <!-- 9 -->
        <div class="lg-section" id="pp-contact">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--blue"><i class="bi bi-envelope-fill"></i></div>
            <div><h2 class="lg-section__title">Contact Us</h2><div class="lg-section__num">Section 09</div></div>
          </div>
          <div class="lg-body">
            <p>For privacy-related queries, data requests, or to report a concern, please contact our Data Protection Officer:</p>
            <ul class="lg-list">
              <li><i class="bi bi-envelope-fill"></i> Email: <a href="mailto:privacy@bookhotel.com">privacy@bookhotel.com</a></li>
              <li><i class="bi bi-telephone-fill"></i> Phone: +91 9876543210 (Mon–Sun, 9 AM – 6 PM IST)</li>
              <li><i class="bi bi-geo-alt-fill"></i> Address: bookHotel Technologies Pvt. Ltd., Lucknow, Uttar Pradesh, India — 226001</li>
            </ul>
            <p>We aim to respond to all data-related requests within <strong>30 days</strong>. Complex requests may take up to 90 days, in which case we will notify you of the delay.</p>
          </div>
        </div>

        <!-- CTA -->
        <div class="lg-cta">
          <h3 class="lg-cta__title">Questions About Your Privacy?</h3>
          <p class="lg-cta__sub">Our dedicated privacy team is here to help you understand your rights.</p>
          <div class="lg-cta__btns">
            <a href="contact.php" class="lg-btn lg-btn--primary"><i class="bi bi-headset"></i> Contact Us</a>
            <a href="hotels.php" class="lg-btn lg-btn--outline"><i class="bi bi-search"></i> Browse Hotels</a>
          </div>
        </div>
      </main>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="bg-dark text-white pt-5 pb-3">
  <div class="container">
    <div class="row g-4 mb-4">
      <div class="col-12 col-md-4"><h5 class="fw-800 mb-3"><i class="bi bi-building-fill text-warning me-1"></i>bookHotel</h5><p class="text-white-50 small">Your trusted travel partner since 2015.</p><div class="d-flex gap-3 mt-3"><a href="#" class="text-white-50 social-icon"><i class="bi bi-facebook fs-5"></i></a><a href="#" class="text-white-50 social-icon"><i class="bi bi-twitter-x fs-5"></i></a><a href="#" class="text-white-50 social-icon"><i class="bi bi-instagram fs-5"></i></a></div></div>
      <div class="col-6 col-md-2"><h6 class="fw-700 mb-3">Explore</h6><ul class="list-unstyled footer-links"><li><a href="hotels.php">Hotels</a></li><li><a href="my-bookings.php">My Bookings</a></li><li><a href="wishlist.php">Wishlist</a></li></ul></div>
      <div class="col-6 col-md-2"><h6 class="fw-700 mb-3">Support</h6><ul class="list-unstyled footer-links"><li><a href="contact.php">Contact Us</a></li><li><a href="#">Help Center</a></li></ul></div>
      <div class="col-6 col-md-2"><h6 class="fw-700 mb-3">Legal</h6><ul class="list-unstyled footer-links"><li><a href="privacy-policy.php">Privacy Policy</a></li><li><a href="terms-of-service.php">Terms of Service</a></li><li><a href="cookie-policy.php">Cookie Policy</a></li></ul></div>
    </div>
    <hr class="border-secondary"/>
    <p class="text-white-50 small mb-0">© 2026 bookHotel Technologies Pvt. Ltd. All rights reserved.</p>
  </div>
</footer>
<button id="backToTop" class="btn btn-warning btn-sm rounded-circle shadow" aria-label="Back to top" onclick="window.scrollTo({top:0,behavior:'smooth'})"><i class="bi bi-arrow-up"></i></button>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="navbar.js"></script>
<script>
'use strict';
window.addEventListener('scroll', () => {
  document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 50);
  document.getElementById('backToTop').classList.toggle('show', window.scrollY > 300);
  updateActiveNav();
});
document.querySelectorAll('.lg-nav a').forEach(a => {
  a.addEventListener('click', e => { e.preventDefault(); const t = document.querySelector(a.getAttribute('href')); if (t) t.scrollIntoView({ behavior: 'smooth' }); });
});
function updateActiveNav() {
  const sections = document.querySelectorAll('.lg-section');
  let current = '';
  sections.forEach(s => { if (window.scrollY >= s.offsetTop - 120) current = s.id; });
  document.querySelectorAll('.lg-nav a').forEach(a => a.classList.toggle('active', a.getAttribute('href') === '#' + current));
}
</script>
</body>
</html>
