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
  <title>Cookie Policy — bookHotel</title>
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
        <li class="breadcrumb-item active">Cookie Policy</li>
      </ol>
    </nav>
    <div class="lg-hero__badge"><i class="bi bi-brightness-alt-high-fill me-2"></i>Cookie Information</div>
    <h1 class="lg-hero__title">Cookie Policy</h1>
    <p class="lg-hero__sub">Learn how bookHotel uses cookies and similar tracking technologies to improve your experience and personalise our services.</p>
    <div class="lg-hero__meta">
      <div class="lg-hero__meta-item"><i class="bi bi-calendar3"></i> Last Updated: July 2026</div>
      <div class="lg-hero__meta-item"><i class="bi bi-toggles"></i> Manage Cookie Preferences</div>
      <div class="lg-hero__meta-item"><i class="bi bi-shield-check"></i> GDPR Compliant</div>
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
            <li><a href="#ck-what" class="active"><i class="bi bi-question-circle-fill"></i> What Are Cookies</a></li>
            <li><a href="#ck-types"><i class="bi bi-grid-fill"></i> Types of Cookies</a></li>
            <li><a href="#ck-essential"><i class="bi bi-shield-fill-check"></i> Essential Cookies</a></li>
            <li><a href="#ck-analytics"><i class="bi bi-bar-chart-fill"></i> Analytics Cookies</a></li>
            <li><a href="#ck-marketing"><i class="bi bi-megaphone-fill"></i> Marketing Cookies</a></li>
            <li><a href="#ck-managing"><i class="bi bi-toggles"></i> Managing Cookies</a></li>
            <li><a href="#ck-browser"><i class="bi bi-browser-chrome"></i> Browser Settings</a></li>
            <li><a href="#ck-contact"><i class="bi bi-envelope-fill"></i> Contact Information</a></li>
          </ul>
        </div>
      </aside>

      <!-- MAIN CONTENT -->
      <main>
        <div class="lg-updated-card">
          <i class="bi bi-clock-fill"></i>
          <div>
            <div class="lg-updated-card__text">Last Updated: July 2026</div>
            <div class="lg-updated-card__sub">This policy covers all cookies used across bookHotel's web and mobile platforms.</div>
          </div>
        </div>

        <!-- 1 -->
        <div class="lg-section" id="ck-what">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--gold"><i class="bi bi-question-circle-fill"></i></div>
            <div><h2 class="lg-section__title">What Are Cookies?</h2><div class="lg-section__num">Section 01</div></div>
          </div>
          <div class="lg-body">
            <p>Cookies are small text files stored on your device (computer, smartphone, or tablet) when you visit a website. They are widely used to make websites work more efficiently and to provide information to website owners.</p>
            <div class="lg-info-box">
              <i class="bi bi-lightbulb-fill"></i>
              <div class="lg-info-box__text">Think of cookies as a website's memory. They help bookHotel remember your preferences — like your saved search destinations, login status, or currency settings — so you don't have to re-enter them every visit.</div>
            </div>
            <p>Cookies can be "session cookies" (deleted when you close your browser) or "persistent cookies" (remaining on your device for a set period). They can also be set by the website you're visiting ("first-party cookies") or by third-party services we use.</p>
          </div>
        </div>

        <!-- 2 -->
        <div class="lg-section" id="ck-types">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--blue"><i class="bi bi-grid-fill"></i></div>
            <div><h2 class="lg-section__title">Types of Cookies We Use</h2><div class="lg-section__num">Section 02</div></div>
          </div>
          <div class="lg-body">
            <p>bookHotel uses the following categories of cookies:</p>
            <div class="lg-table-wrap">
              <table class="lg-table">
                <thead><tr><th>Cookie Type</th><th>Purpose</th><th>Duration</th><th>Required</th></tr></thead>
                <tbody>
                  <tr><td><strong>Essential</strong></td><td>Core site functionality, security, login sessions</td><td>Session / 1 year</td><td>Yes</td></tr>
                  <tr><td><strong>Functional</strong></td><td>Remember preferences, language, currency</td><td>1 year</td><td>No</td></tr>
                  <tr><td><strong>Analytics</strong></td><td>Track usage patterns and improve UX</td><td>2 years</td><td>No</td></tr>
                  <tr><td><strong>Marketing</strong></td><td>Personalised ads and retargeting</td><td>90 days</td><td>No</td></tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- 3 -->
        <div class="lg-section" id="ck-essential">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--green"><i class="bi bi-shield-fill-check"></i></div>
            <div><h2 class="lg-section__title">Essential Cookies</h2><div class="lg-section__num">Section 03</div></div>
          </div>
          <div class="lg-body">
            <p>Essential cookies are strictly necessary for the bookHotel platform to function correctly. These cannot be disabled without affecting core functionality.</p>
            <ul class="lg-list">
              <li><i class="bi bi-lock-fill"></i> <strong>Authentication cookies:</strong> Keep you logged in during your session</li>
              <li><i class="bi bi-cart-fill"></i> <strong>Session cookies:</strong> Remember your booking progress across pages</li>
              <li><i class="bi bi-shield-fill-check"></i> <strong>Security cookies:</strong> Protect against CSRF attacks and fraud</li>
              <li><i class="bi bi-toggles"></i> <strong>Preference cookies:</strong> Remember your cookie consent choices</li>
            </ul>
            <div class="lg-info-box">
              <i class="bi bi-info-circle-fill"></i>
              <div class="lg-info-box__text">Essential cookies do not require your consent. They are set automatically when you use our platform and are deleted when you close your browser or within the session period.</div>
            </div>
          </div>
        </div>

        <!-- 4 -->
        <div class="lg-section" id="ck-analytics">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--indigo"><i class="bi bi-bar-chart-fill"></i></div>
            <div><h2 class="lg-section__title">Analytics Cookies</h2><div class="lg-section__num">Section 04</div></div>
          </div>
          <div class="lg-body">
            <p>Analytics cookies help us understand how visitors interact with bookHotel, which allows us to improve our platform and services.</p>
            <ul class="lg-list">
              <li><i class="bi bi-graph-up"></i> <strong>Google Analytics:</strong> Tracks page views, session duration, and user journeys (anonymised)</li>
              <li><i class="bi bi-heatgrid"></i> <strong>Hotjar:</strong> Heatmaps and session recordings to understand UX issues</li>
              <li><i class="bi bi-funnel-fill"></i> <strong>Conversion tracking:</strong> Measures booking completion rates and drop-off points</li>
            </ul>
            <p>All analytics data is aggregated and anonymised. We do not use analytics cookies to identify individual users. You can opt out of analytics cookies via your cookie preferences or by installing the <a href="https://tools.google.com/dlpage/gaoptout" target="_blank" rel="noopener">Google Analytics Opt-out Browser Add-on</a>.</p>
          </div>
        </div>

        <!-- 5 -->
        <div class="lg-section" id="ck-marketing">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--pink"><i class="bi bi-megaphone-fill"></i></div>
            <div><h2 class="lg-section__title">Marketing Cookies</h2><div class="lg-section__num">Section 05</div></div>
          </div>
          <div class="lg-body">
            <p>Marketing cookies are used to deliver relevant advertisements and measure the effectiveness of our campaigns. These are set only with your explicit consent.</p>
            <ul class="lg-list">
              <li><i class="bi bi-facebook"></i> <strong>Meta Pixel:</strong> Retargeting on Facebook and Instagram based on hotels you viewed</li>
              <li><i class="bi bi-google"></i> <strong>Google Ads:</strong> Remarketing to users who previously visited bookHotel</li>
              <li><i class="bi bi-star-fill"></i> <strong>Affiliate tracking:</strong> Attributes bookings made through partner links</li>
            </ul>
            <div class="lg-warn-box">
              <i class="bi bi-exclamation-triangle-fill"></i>
              <div class="lg-warn-box__text">Marketing cookies may track your activity across other websites. You can withdraw consent for marketing cookies at any time through your cookie preferences panel without affecting your ability to use bookHotel.</div>
            </div>
          </div>
        </div>

        <!-- 6 -->
        <div class="lg-section" id="ck-managing">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--teal"><i class="bi bi-toggles"></i></div>
            <div><h2 class="lg-section__title">Managing Cookie Preferences</h2><div class="lg-section__num">Section 06</div></div>
          </div>
          <div class="lg-body">
            <p>You have full control over non-essential cookies. You can manage your preferences at any time:</p>
            <ul class="lg-list">
              <li><i class="bi bi-toggle-on"></i> Click <strong>"Cookie Preferences"</strong> in the website footer to open the consent panel</li>
              <li><i class="bi bi-toggle-off"></i> Toggle individual cookie categories on or off</li>
              <li><i class="bi bi-arrow-counterclockwise"></i> Your choices are saved and applied immediately</li>
              <li><i class="bi bi-clock-history"></i> Preferences are stored for 12 months, after which you will be asked again</li>
            </ul>
            <p>Please note that disabling certain cookies may affect the functionality of bookHotel. For example, disabling functional cookies may prevent us from remembering your login status or currency preferences.</p>
          </div>
        </div>

        <!-- 7 -->
        <div class="lg-section" id="ck-browser">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--blue"><i class="bi bi-browser-chrome"></i></div>
            <div><h2 class="lg-section__title">Browser Settings</h2><div class="lg-section__num">Section 07</div></div>
          </div>
          <div class="lg-body">
            <p>Most browsers allow you to control cookies through their settings. Here's how to manage cookies in popular browsers:</p>
            <div class="lg-table-wrap">
              <table class="lg-table">
                <thead><tr><th>Browser</th><th>Cookie Settings Path</th></tr></thead>
                <tbody>
                  <tr><td><i class="bi bi-browser-chrome me-1"></i> Google Chrome</td><td>Settings → Privacy and security → Cookies and other site data</td></tr>
                  <tr><td><i class="bi bi-browser-firefox me-1"></i> Mozilla Firefox</td><td>Options → Privacy & Security → Cookies and Site Data</td></tr>
                  <tr><td><i class="bi bi-browser-safari me-1"></i> Safari</td><td>Preferences → Privacy → Manage Website Data</td></tr>
                  <tr><td><i class="bi bi-browser-edge me-1"></i> Microsoft Edge</td><td>Settings → Cookies and site permissions → Cookies and site data</td></tr>
                </tbody>
              </table>
            </div>
            <div class="lg-warn-box">
              <i class="bi bi-exclamation-triangle-fill"></i>
              <div class="lg-warn-box__text">Blocking all cookies through your browser will prevent bookHotel from functioning correctly, including the ability to log in, complete bookings, or maintain your session.</div>
            </div>
          </div>
        </div>

        <!-- 8 -->
        <div class="lg-section" id="ck-contact">
          <div class="lg-section__head">
            <div class="lg-section__icon lg-section__icon--green"><i class="bi bi-envelope-fill"></i></div>
            <div><h2 class="lg-section__title">Contact Information</h2><div class="lg-section__num">Section 08</div></div>
          </div>
          <div class="lg-body">
            <p>If you have questions about our use of cookies or wish to exercise your data rights, please contact us:</p>
            <ul class="lg-list">
              <li><i class="bi bi-envelope-fill"></i> Email: <a href="mailto:privacy@bookhotel.com">privacy@bookhotel.com</a></li>
              <li><i class="bi bi-telephone-fill"></i> Phone: +91 9876543210</li>
              <li><i class="bi bi-geo-alt-fill"></i> bookHotel Technologies Pvt. Ltd., Lucknow, Uttar Pradesh — 226001</li>
            </ul>
            <p>You also have the right to lodge a complaint with the relevant supervisory authority in your jurisdiction if you believe your data rights have been violated.</p>
          </div>
        </div>

        <!-- CTA -->
        <div class="lg-cta">
          <h3 class="lg-cta__title">Questions About Our Cookie Usage?</h3>
          <p class="lg-cta__sub">Our privacy team is available to help with any cookie or data related queries.</p>
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
