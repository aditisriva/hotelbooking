<?php
session_start();
require_once 'db.php';
require_once 'hotel_functions.php';
require_once 'pricing.php';

// -- Handle AJAX booking save ----------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_booking') {
    header('Content-Type: application/json');
    $required = ['hotel_id','guest_name','guest_email','checkin_date','checkout_date','total_amount'];
    foreach ($required as $f) {
        if (empty($_POST[$f])) {
            echo json_encode(['success'=>false,'error'=>'Missing field: '.$f]);
            exit;
        }
    }

    // Generate booking ID
    $booking_id = 'BH-' . date('Y') . '-' . strtoupper(substr(uniqid(),5,6));

    // Sanitize inputs
    $hotel_id       = (int)$_POST['hotel_id'];
    $hotel          = bhGetHotelById($hotel_id);
    $hotel_name     = $hotel ? $hotel['hotel_name'] : sanitize($_POST['hotel_name'] ?? 'Unknown Hotel');
    $hotel_city     = $hotel ? $hotel['city'] : '';
    $guest_name     = sanitize($_POST['guest_name']);
    $guest_email    = sanitize($_POST['guest_email']);
    $guest_phone    = sanitize($_POST['guest_phone'] ?? '');
    $checkin        = sanitize($_POST['checkin_date']);
    $checkout       = sanitize($_POST['checkout_date']);
    $nights         = max(1, (int)($_POST['nights'] ?? 1));
    $guests         = max(1, (int)($_POST['guests'] ?? 2));
    $room_type      = sanitize($_POST['room_type'] ?? 'Deluxe Room');
    $base_amount    = (float)$_POST['base_amount'];
    $discount_amt   = (float)($_POST['discount_amount'] ?? 0);
    $tax_amount     = (float)($_POST['tax_amount'] ?? 0);
    $svc_charge     = (float)($_POST['service_charge'] ?? 200);
    $coupon_disc    = (float)($_POST['coupon_discount'] ?? 0);
    $total_amount   = (float)$_POST['total_amount'];
    $pay_method     = sanitize($_POST['payment_method'] ?? 'UPI');
    $special_req    = sanitize($_POST['special_requests'] ?? '');
    $arrival_time   = sanitize($_POST['arrival_time'] ?? '');
    $user_id        = isset($_SESSION['hm_id']) ? (int)$_SESSION['hm_id'] : null;

    $stmt = mysqli_prepare($conn,
        "INSERT INTO bookings (booking_id,user_id,hotel_id,hotel_name,hotel_city,room_type,
         guest_name,guest_email,guest_phone,checkin_date,checkout_date,nights,guests,
         base_amount,discount_amount,tax_amount,service_charge,coupon_discount,
         total_amount,payment_method,payment_status,booking_status,special_requests,arrival_time)
         VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'paid','confirmed',?,?)"
    );

    mysqli_stmt_bind_param($stmt,'siissssssssiiddddddsss',
        $booking_id,$user_id,$hotel_id,$hotel_name,$hotel_city,$room_type,
        $guest_name,$guest_email,$guest_phone,$checkin,$checkout,$nights,$guests,
        $base_amount,$discount_amt,$tax_amount,$svc_charge,$coupon_disc,
        $total_amount,$pay_method,$special_req,$arrival_time
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        echo json_encode(['success'=>true,'booking_id'=>$booking_id]);
    } else {
        $err = mysqli_error($conn);
        mysqli_stmt_close($stmt);
        echo json_encode(['success'=>false,'error'=>$err]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%231a56db'/%3E%3Ctext x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-size='18' font-family='system-ui' fill='%23f59e0b'%3E&#x1F3E8;%3C/text%3E%3C/svg%3E"/>
  <title>Payment – bookHotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" crossorigin="anonymous"/>
  <link rel="stylesheet" href="style.css"/>
  <link rel="stylesheet" href="hotels.css"/>
  <link rel="stylesheet" href="booking.css"/>
  <link rel="stylesheet" href="payment.css"/>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand fw-800 fs-4" href="index.php">
      <i class="bi bi-building-fill text-warning me-1"></i>bookHotel
    </a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="hotels.php">Hotels</a></li>
        <li class="nav-item"><a class="nav-link" href="my-bookings.php">My Bookings</a></li>
        <li class="nav-item ms-lg-3">
          <a class="btn btn-outline-warning btn-sm px-3" href="login.php">Login / Sign Up</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- PROGRESS BAR -->
<div class="booking-progress-bar">
  <div class="container">
    <div class="d-flex align-items-center gap-2 mb-3">
      <a href="javascript:history.back()" class="text-warning text-decoration-none small fw-600">
        <i class="bi bi-arrow-left me-1"></i>Back to Guest Details
      </a>
    </div>
    <div class="progress-steps">
      <div class="progress-step completed"><div class="step-circle"><i class="bi bi-check-lg"></i></div><span>Select Room</span></div>
      <div class="progress-line completed"></div>
      <div class="progress-step completed"><div class="step-circle"><i class="bi bi-check-lg"></i></div><span>Review Booking</span></div>
      <div class="progress-line completed"></div>
      <div class="progress-step completed"><div class="step-circle"><i class="bi bi-check-lg"></i></div><span>Guest Details</span></div>
      <div class="progress-line completed"></div>
      <div class="progress-step active"><div class="step-circle">4</div><span>Payment</span></div>
    </div>
  </div>
</div>

<!-- MAIN -->
<section class="py-4 bg-light">
  <div class="container">
    <div class="row g-4">

      <!-- LEFT -->
      <div class="col-12 col-lg-8">

        <!-- Security badge -->
        <div class="secure-banner mb-4">
          <i class="bi bi-lock-fill text-success me-2"></i>
          <span class="fw-600 small">Secure Payment · 256-bit SSL Encryption · PCI DSS Compliant</span>
        </div>

        <!-- Payment Method Tabs -->
        <div class="review-section-card mb-4">
          <div class="review-section-header">
            <h6 class="fw-700 mb-0"><i class="bi bi-credit-card-fill me-2 text-warning"></i>Choose Payment Method</h6>
          </div>
          <div class="review-section-body p-0">
            <div class="payment-tabs">
              <button class="pay-tab active" onclick="switchTab('upi',this)"><i class="bi bi-phone-fill me-2"></i>UPI</button>
              <button class="pay-tab" onclick="switchTab('card',this)"><i class="bi bi-credit-card me-2"></i>Credit / Debit Card</button>
              <button class="pay-tab" onclick="switchTab('netbank',this)"><i class="bi bi-bank me-2"></i>Net Banking</button>
              <button class="pay-tab" onclick="switchTab('wallet',this)"><i class="bi bi-wallet2 me-2"></i>Wallets</button>
              <button class="pay-tab" onclick="switchTab('emi',this)"><i class="bi bi-calendar-range me-2"></i>EMI</button>
            </div>

            <!-- UPI -->
            <div class="pay-panel active p-4" id="panel-upi">
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label small fw-600 text-muted">ENTER UPI ID</label>
                  <div class="input-group">
                    <input type="text" class="form-control" id="upiId" placeholder="yourname@upi / 9876543210@paytm"/>
                    <button class="btn btn-outline-primary fw-600" onclick="verifyUPI()">Verify</button>
                  </div>
                  <div id="upiStatus" class="mt-1 small"></div>
                </div>
              </div>
              <div class="upi-apps mt-4">
                <p class="small fw-600 text-muted mb-3">OR PAY WITH</p>
                <div class="d-flex flex-wrap gap-3">
                  <div class="upi-app-btn" onclick="selectUPI(this,'GooglePay')">
                    <i class="bi bi-google fs-4 text-primary"></i><span>Google Pay</span>
                  </div>
                  <div class="upi-app-btn" onclick="selectUPI(this,'PhonePe')">
                    <i class="bi bi-phone fs-4 text-success"></i><span>PhonePe</span>
                  </div>
                  <div class="upi-app-btn" onclick="selectUPI(this,'Paytm')">
                    <i class="bi bi-wallet2 fs-4 text-primary"></i><span>Paytm</span>
                  </div>
                  <div class="upi-app-btn" onclick="selectUPI(this,'BHIM')">
                    <i class="bi bi-bank fs-4 text-warning"></i><span>BHIM UPI</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Credit/Debit Card -->
            <div class="pay-panel p-4" id="panel-card" style="display:none">
              <div class="row g-3">
                <div class="col-12">
                  <label class="form-label small fw-600 text-muted">CARD NUMBER</label>
                  <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-credit-card text-muted"></i></span>
                    <input type="text" class="form-control" id="cardNum" placeholder="1234  5678  9012  3456"
                           maxlength="19" oninput="formatCard(this)"/>
                    <span class="input-group-text bg-white" id="cardBrand"></span>
                  </div>
                </div>
                <div class="col-12">
                  <label class="form-label small fw-600 text-muted">CARDHOLDER NAME</label>
                  <input type="text" class="form-control" placeholder="Name as on card"/>
                </div>
                <div class="col-6">
                  <label class="form-label small fw-600 text-muted">EXPIRY DATE</label>
                  <input type="text" class="form-control" placeholder="MM / YY" maxlength="7" oninput="formatExpiry(this)"/>
                </div>
                <div class="col-6">
                  <label class="form-label small fw-600 text-muted">CVV</label>
                  <div class="input-group">
                    <input type="password" class="form-control" id="cvvInput" placeholder="•••" maxlength="4"/>
                    <span class="input-group-text bg-white cursor-pointer" onclick="toggleCVV()">
                      <i class="bi bi-eye" id="cvvEye"></i>
                    </span>
                  </div>
                </div>
                <div class="col-12">
                  <label class="save-card-label">
                    <input type="checkbox" checked style="accent-color:#e52d5e"/>
                    <span class="ms-2 small fw-600">Save this card securely for future bookings</span>
                  </label>
                </div>
              </div>
              <div class="card-logos mt-3 d-flex gap-2 flex-wrap">
                <img src="https://img.shields.io/badge/Visa-1A1F71?style=flat&logo=visa&logoColor=white" height="22" alt="Visa"/>
                <img src="https://img.shields.io/badge/Mastercard-EB001B?style=flat&logo=mastercard&logoColor=white" height="22" alt="MC"/>
                <img src="https://img.shields.io/badge/Rupay-FF6600?style=flat&logoColor=white" height="22" alt="RuPay"/>
                <img src="https://img.shields.io/badge/Amex-007BC1?style=flat&logoColor=white" height="22" alt="Amex"/>
              </div>
            </div>

            <!-- Net Banking -->
            <div class="pay-panel p-4" id="panel-netbank" style="display:none">
              <p class="small fw-600 text-muted mb-3">SELECT YOUR BANK</p>
              <div class="row g-2 mb-3">
                <div class="col-6 col-md-3"><label class="bank-tile"><input type="radio" name="bank"/><span>SBI</span></label></div>
                <div class="col-6 col-md-3"><label class="bank-tile"><input type="radio" name="bank"/><span>HDFC Bank</span></label></div>
                <div class="col-6 col-md-3"><label class="bank-tile"><input type="radio" name="bank"/><span>ICICI Bank</span></label></div>
                <div class="col-6 col-md-3"><label class="bank-tile"><input type="radio" name="bank"/><span>Axis Bank</span></label></div>
                <div class="col-6 col-md-3"><label class="bank-tile"><input type="radio" name="bank"/><span>Kotak Bank</span></label></div>
                <div class="col-6 col-md-3"><label class="bank-tile"><input type="radio" name="bank"/><span>Yes Bank</span></label></div>
                <div class="col-6 col-md-3"><label class="bank-tile"><input type="radio" name="bank"/><span>PNB</span></label></div>
                <div class="col-6 col-md-3"><label class="bank-tile"><input type="radio" name="bank"/><span>BOB</span></label></div>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label small fw-600 text-muted">OR SELECT OTHER BANK</label>
                <select class="form-select"><option>Select Bank...</option><option>Union Bank</option><option>Canara Bank</option><option>Indian Bank</option></select>
              </div>
            </div>

            <!-- Wallets -->
            <div class="pay-panel p-4" id="panel-wallet" style="display:none">
              <p class="small fw-600 text-muted mb-3">SELECT WALLET</p>
              <div class="d-flex flex-wrap gap-3">
                <div class="upi-app-btn" onclick="selectUPI(this,'Paytm Wallet')"><i class="bi bi-wallet2 fs-4 text-primary"></i><span>Paytm</span></div>
                <div class="upi-app-btn" onclick="selectUPI(this,'Mobikwik')"><i class="bi bi-phone fs-4 text-danger"></i><span>MobiKwik</span></div>
                <div class="upi-app-btn" onclick="selectUPI(this,'Amazon Pay')"><i class="bi bi-bag fs-4 text-warning"></i><span>Amazon Pay</span></div>
                <div class="upi-app-btn" onclick="selectUPI(this,'Freecharge')"><i class="bi bi-lightning fs-4 text-success"></i><span>Freecharge</span></div>
              </div>
            </div>

            <!-- EMI -->
            <div class="pay-panel p-4" id="panel-emi" style="display:none">
              <p class="small text-muted mb-3">EMI available on credit cards with 0% interest for 3–12 months.</p>
              <div class="row g-2">
                <div class="col-12 col-md-6"><label class="emi-tile"><input type="radio" name="emi" checked/><div><span class="fw-700">3 Months</span><div class="text-muted small" id="emi3">?2,309 / month</div></div></label></div>
                <div class="col-12 col-md-6"><label class="emi-tile"><input type="radio" name="emi"/><div><span class="fw-700">6 Months</span><div class="text-muted small" id="emi6">?1,154 / month</div></div></label></div>
                <div class="col-12 col-md-6"><label class="emi-tile"><input type="radio" name="emi"/><div><span class="fw-700">9 Months</span><div class="text-muted small" id="emi9">?769 / month</div></div></label></div>
                <div class="col-12 col-md-6"><label class="emi-tile"><input type="radio" name="emi"/><div><span class="fw-700">12 Months</span><div class="text-muted small" id="emi12">?577 / month</div></div></label></div>
              </div>
              <p class="text-muted small mt-3 mb-0"><i class="bi bi-info-circle me-1"></i>Available on Visa, Mastercard & RuPay credit cards.</p>
            </div>

          </div>
        </div>

        <!-- Coupon -->
        <div class="review-section-card mb-4">
          <div class="review-section-header">
            <h6 class="fw-700 mb-0"><i class="bi bi-ticket-perforated me-2 text-warning"></i>Coupons & Offers</h6>
          </div>
          <div class="review-section-body">
            <div class="input-group mb-3">
              <input type="text" class="form-control" id="couponCode" placeholder="Enter coupon code (try WEEKEND30)"/>
              <button class="btn btn-outline-danger fw-700" onclick="applyCoupon()">Apply</button>
            </div>
            <div id="couponResult"></div>
            <div class="offer-chips d-flex flex-wrap gap-2 mt-2">
              <span class="offer-chip" onclick="useCoupon('WEEKEND30')">WEEKEND30</span>
              <span class="offer-chip" onclick="useCoupon('MONSOON50')">MONSOON50</span>
              <span class="offer-chip" onclick="useCoupon('FIRST1000')">FIRST1000</span>
            </div>
          </div>
        </div>

      </div><!-- end left -->

      <!-- RIGHT: Order Summary -->
      <div class="col-12 col-lg-4">
        <div class="price-summary-card sticky-booking">
          <div class="price-summary-header">
            <h6 class="fw-700 text-white mb-0"><i class="bi bi-receipt me-2"></i>Order Summary</h6>
          </div>
          <div class="price-summary-body">
            <div class="d-flex gap-3 align-items-center mb-3">
              <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=80&q=80"
                   class="rounded-2" width="56" height="48" style="object-fit:cover" alt="Hotel"/>
              <div>
                <div class="fw-700 small">Heritage Haveli, Jaipur</div>
                <div class="text-warning small">?????</div>
                <div class="small text-muted" id="ordRoomName">Deluxe Heritage Room</div>
              </div>
            </div>
            <hr class="my-2"/>
            <div class="d-flex justify-content-between mb-1"><span class="small text-muted">Check-in</span><span class="small fw-600" id="ordCheckin">—</span></div>
            <div class="d-flex justify-content-between mb-1"><span class="small text-muted">Check-out</span><span class="small fw-600" id="ordCheckout">—</span></div>
            <div class="d-flex justify-content-between mb-3"><span class="small text-muted">Duration</span><span class="small fw-600" id="ordNights">2 Nights</span></div>
            <hr class="my-2"/>
            <div class="d-flex justify-content-between mb-1"><span class="small text-muted" id="ordBaseLabel">Base</span><span class="small fw-600" id="ordBase">—</span></div>
            <div class="d-flex justify-content-between mb-1"><span class="small text-muted" id="ordTaxLabel">GST (12%)</span><span class="small fw-600" id="ordTax">—</span></div>
            <div class="d-flex justify-content-between mb-1"><span class="small text-muted">Service Charge</span><span class="small fw-600" id="ordService">?200</span></div>
            <div class="d-flex justify-content-between mb-1"><span class="small text-success fw-600">Discount</span><span class="small fw-600 text-success" id="ordDiscount">—</span></div>
            <div class="d-flex justify-content-between mb-3" id="extraDiscRow" style="display:none!important">
              <span class="small text-success fw-600">Coupon</span>
              <span class="small fw-600 text-success" id="ordCoupon">—</span>
            </div>
            <hr class="my-2"/>
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="fw-700">Total Payable</span>
              <span class="fw-800 text-primary fs-4" id="ordTotal">—</span>
            </div>
            <p class="text-muted small mb-4">Inclusive of all taxes & fees</p>
            <button class="btn book-now-btn w-100 fw-700 py-3 mb-3" onclick="payNow()">
              <i class="bi bi-lock-fill me-2"></i>Pay Now
            </button>
            <p class="text-center text-muted small">
              <i class="bi bi-shield-check-fill text-success me-1"></i>100% Secure · SSL Encrypted
            </p>
            <div class="d-flex justify-content-center gap-2 mt-3 flex-wrap">
              <img src="https://img.shields.io/badge/Visa-1A1F71?style=flat&logo=visa&logoColor=white" height="20" alt="Visa"/>
              <img src="https://img.shields.io/badge/Mastercard-EB001B?style=flat&logo=mastercard&logoColor=white" height="20" alt="MC"/>
              <img src="https://img.shields.io/badge/UPI-1a73e8?style=flat&logo=google-pay&logoColor=white" height="20" alt="UPI"/>
              <img src="https://img.shields.io/badge/PayPal-003087?style=flat&logo=paypal&logoColor=white" height="20" alt="PP"/>
            </div>
          </div>
        </div>
        <div class="trust-badges mt-3">
          <div class="trust-item"><i class="bi bi-shield-lock-fill text-success"></i><span>Secure Pay</span></div>
          <div class="trust-item"><i class="bi bi-arrow-counterclockwise text-primary"></i><span>Free Cancel</span></div>
          <div class="trust-item"><i class="bi bi-headset text-warning"></i><span>24/7 Help</span></div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="bg-dark text-white pt-5 pb-3">
  <div class="container">
    <div class="row g-4 mb-4">
      <div class="col-12 col-md-4">
        <h5 class="fw-800 mb-3"><i class="bi bi-building-fill text-warning me-1"></i>bookHotel</h5>
        <p class="text-white-50 small">Your trusted travel partner since 2015.</p>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="fw-700 mb-3">Support</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="#">Help Center</a></li>
          <li><a href="#">Contact Us</a></li>
        </ul>
      </div>
    </div>
    <hr class="border-secondary"/>
    <p class="text-white-50 small text-center mb-0">© 2026 bookHotel Technologies Pvt. Ltd. All rights reserved.</p>
  </div>
</footer>

<!-- Booking Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 rounded-4 overflow-hidden">
      <div class="modal-body text-center p-5">
        <div class="confirm-icon mb-4"><i class="bi bi-check-lg"></i></div>
        <h4 class="fw-800 mb-2">Booking Confirmed!</h4>
        <p class="text-muted mb-1">Your booking at <strong id="confirmHotelName">Heritage Haveli</strong> is confirmed.</p>
        <p class="text-muted mb-4">Confirmation sent to <strong id="confirmEmail">—</strong></p>
        <div class="booking-ref mb-4">
          Booking ID: <strong id="bookingId">BH-2026-XXXXX</strong>
        </div>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
          <a href="index.php" class="btn btn-primary px-4 fw-700">Back to Home</a>
          <a href="hotels.php" class="btn btn-outline-primary px-4 fw-700">Browse Hotels</a>
        </div>
      </div>
    </div>
  </div>
</div>

<button id="backToTop" class="btn btn-warning btn-sm rounded-circle shadow" aria-label="Back to top" onclick="window.scrollTo({top:0,behavior:'smooth'})">
  <i class="bi bi-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
  window.addEventListener('scroll', () => {
    document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 50);
    document.getElementById('backToTop').classList.toggle('show', window.scrollY > 300);
  });

  // Room data
  const rooms = {
    'deluxe':   { name:'Deluxe Heritage Room',       price:4680,  discount:0.35 },
    'royal':    { name:'Royal Suite',                 price:9800,  discount:0.30 },
    'maharaja': { name:'Maharaja Presidential Suite', price:19500, discount:0.30 }
  };

  const params   = new URLSearchParams(window.location.search);
  const roomKey  = params.get('room')    || 'deluxe';
  const guests   = (typeof bhSearch !== 'undefined' ? bhSearch.guests() : parseInt(params.get('guests')||'2'));
  const checkin  = (typeof bhSearch !== 'undefined' ? bhSearch.checkin()  : params.get('checkin'))  || (() => { let d=new Date(); d.setDate(d.getDate()+1); return d.toISOString().split('T')[0]; })();
  const checkout = (typeof bhSearch !== 'undefined' ? bhSearch.checkout() : params.get('checkout')) || (() => { let d=new Date(); d.setDate(d.getDate()+2); return d.toISOString().split('T')[0]; })();
  const room     = rooms[roomKey] || rooms['deluxe'];

  const ci = new Date(checkin), co = new Date(checkout);
  const nights = Math.max(1, Math.round((co-ci)/86400000));
  const base   = room.price * nights;
  const taxRate = room.price <= 2500 ? 0 : room.price <= 7500 ? 0.12 : 0.18;
  const taxPct  = Math.round(taxRate * 100);
  const svc     = 200;
  const tax     = Math.round(base * taxRate);
  const disc   = Math.round(base * room.discount);
  let   total  = base + tax - disc + svc;
  let   extraDisc = 0;

  const fmtDate = s => new Date(s).toLocaleDateString('en-IN',{weekday:'short',day:'2-digit',month:'short',year:'numeric'});

  document.getElementById('ordRoomName').textContent  = room.name;
  document.getElementById('ordCheckin').textContent   = fmtDate(checkin);
  document.getElementById('ordCheckout').textContent  = fmtDate(checkout);
  document.getElementById('ordNights').textContent    = nights + ' Night'+(nights>1?'s':'');
  document.getElementById('ordBaseLabel').textContent = '?'+room.price.toLocaleString()+' × '+nights+' night'+(nights>1?'s':'');
  document.getElementById('ordBase').textContent      = '?'+base.toLocaleString();
  document.getElementById('ordTax').textContent       = '?'+tax.toLocaleString();
  const tLbl = document.getElementById('ordTaxLabel');
  if(tLbl) tLbl.textContent = 'GST ('+taxPct+'%)';
  const sEl = document.getElementById('ordService');
  if(sEl) sEl.textContent = '?'+svc.toLocaleString();
  document.getElementById('ordDiscount').textContent  = '-?'+disc.toLocaleString();
  document.getElementById('ordTotal').textContent     = '?'+total.toLocaleString();

  // EMI calc
  const emiTotal = total;
  document.getElementById('emi3').textContent  = '?'+Math.ceil(emiTotal/3).toLocaleString()+' / month';
  document.getElementById('emi6').textContent  = '?'+Math.ceil(emiTotal/6).toLocaleString()+' / month';
  document.getElementById('emi9').textContent  = '?'+Math.ceil(emiTotal/9).toLocaleString()+' / month';
  document.getElementById('emi12').textContent = '?'+Math.ceil(emiTotal/12).toLocaleString()+' / month';

  // Tab switcher
  function switchTab(id, btn) {
    document.querySelectorAll('.pay-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.pay-panel').forEach(p => p.style.display='none');
    btn.classList.add('active');
    document.getElementById('panel-'+id).style.display='block';
  }

  // UPI verify
  function verifyUPI() {
    const val = document.getElementById('upiId').value.trim();
    const el  = document.getElementById('upiStatus');
    if (!val) { el.innerHTML='<span class="text-danger">Please enter a UPI ID.</span>'; return; }
    el.innerHTML='<span class="text-muted"><i class="bi bi-arrow-repeat spin me-1"></i>Verifying...</span>';
    setTimeout(() => {
      if (val.includes('@')) {
        el.innerHTML='<span class="text-success fw-600"><i class="bi bi-check-circle-fill me-1"></i>UPI ID verified successfully!</span>';
      } else {
        el.innerHTML='<span class="text-danger fw-600"><i class="bi bi-x-circle-fill me-1"></i>Invalid UPI ID. Use format: name@bank</span>';
      }
    }, 1200);
  }

  function selectUPI(el, name) {
    document.querySelectorAll('.upi-app-btn').forEach(b => b.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('upiStatus').innerHTML='<span class="text-success fw-600"><i class="bi bi-check-circle-fill me-1"></i>'+name+' selected.</span>';
  }

  // Card format
  function formatCard(el) {
    let v = el.value.replace(/\D/g,'').substring(0,16);
    el.value = v.replace(/(.{4})/g,'$1 ').trim();
    const brand = document.getElementById('cardBrand');
    if (v.startsWith('4'))      brand.innerHTML='<i class="bi bi-credit-card text-primary"></i>';
    else if (v.startsWith('5')) brand.innerHTML='<i class="bi bi-credit-card text-danger"></i>';
    else                         brand.innerHTML='';
  }
  function formatExpiry(el) {
    let v = el.value.replace(/\D/g,'');
    if (v.length >= 2) v = v.substring(0,2)+' / '+v.substring(2,4);
    el.value = v;
  }
  function toggleCVV() {
    const inp = document.getElementById('cvvInput');
    const eye = document.getElementById('cvvEye');
    inp.type = inp.type==='password' ? 'text' : 'password';
    eye.className = inp.type==='password' ? 'bi bi-eye' : 'bi bi-eye-slash';
  }

  // Coupons
  const coupons = { 'WEEKEND30':0.10, 'MONSOON50':0.15, 'FIRST1000':0.05 };
  function useCoupon(code) { document.getElementById('couponCode').value=code; applyCoupon(); }
  function applyCoupon() {
    const code = document.getElementById('couponCode').value.trim().toUpperCase();
    const res  = document.getElementById('couponResult');
    if (coupons[code]) {
      extraDisc = Math.round((base+tax-disc) * coupons[code]);
      total = base + tax - disc + svc - extraDisc;
      document.getElementById('ordTotal').textContent   = '?'+total.toLocaleString();
      document.getElementById('ordCoupon').textContent  = '-?'+extraDisc.toLocaleString();
      document.getElementById('extraDiscRow').style.display='flex';
      res.innerHTML='<span class="text-success fw-600 small"><i class="bi bi-check-circle-fill me-1"></i>Coupon applied! Extra '+(coupons[code]*100)+'% off.</span>';
    } else {
      res.innerHTML='<span class="text-danger fw-600 small"><i class="bi bi-x-circle-fill me-1"></i>Invalid coupon code.</span>';
    }
  }

  // Pay Now — saves booking to DB then shows confirmation
  function payNow() {
    const btn = document.querySelector('.book-now-btn');
    btn.innerHTML='<span class="spinner-border spinner-border-sm me-2"></span>Processing Payment...';
    btn.disabled = true;

    // Collect guest details passed from previous page
    const guestName  = params.get('name')    || sessionStorage.getItem('bh_guest_name')  || 'Guest';
    const guestEmail = params.get('email')   || sessionStorage.getItem('bh_guest_email') || '';
    const guestPhone = params.get('phone')   || sessionStorage.getItem('bh_guest_phone') || '';
    const hotelId    = params.get('id')      || sessionStorage.getItem('bh_hotel_id')    || '1';
    const hotelName  = params.get('hotel')   || sessionStorage.getItem('bh_hotel_name')  || 'Hotel';
    const specialReq = params.get('special') || sessionStorage.getItem('bh_special_req') || '';
    const arrivalTime= params.get('arrival') || sessionStorage.getItem('bh_arrival_time')|| '';

    // Determine active payment method
    const activeTab  = document.querySelector('.pay-tab.active');
    const payMethod  = activeTab ? activeTab.textContent.trim() : 'UPI';

    const formData = new FormData();
    formData.append('action',         'save_booking');
    formData.append('hotel_id',       hotelId);
    formData.append('hotel_name',     hotelName);
    formData.append('guest_name',     guestName);
    formData.append('guest_email',    guestEmail);
    formData.append('guest_phone',    guestPhone);
    formData.append('checkin_date',   checkin);
    formData.append('checkout_date',  checkout);
    formData.append('nights',         nights);
    formData.append('guests',         guests);
    formData.append('room_type',      room.name);
    formData.append('base_amount',    base);
    formData.append('discount_amount',disc);
    formData.append('tax_amount',     tax);
    formData.append('service_charge', svc);
    formData.append('coupon_discount',extraDisc);
    formData.append('total_amount',   total);
    formData.append('payment_method', payMethod);
    formData.append('special_requests', specialReq);
    formData.append('arrival_time',   arrivalTime);

    fetch('payment.php', { method:'POST', body: formData })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          // Store booking id for confirmation page
          sessionStorage.setItem('bh_last_booking_id', data.booking_id);
          document.getElementById('confirmEmail').textContent = guestEmail || 'your registered email';
          document.getElementById('bookingId').textContent    = data.booking_id;
          document.getElementById('confirmHotelName').textContent = hotelName;
          new bootstrap.Modal(document.getElementById('confirmModal')).show();
        } else {
          alert('Booking failed: ' + (data.error || 'Unknown error. Please try again.'));
        }
        btn.innerHTML='<i class="bi bi-lock-fill me-2"></i>Pay Now';
        btn.disabled=false;
      })
      .catch(() => {
        alert('Network error. Please check your connection and try again.');
        btn.innerHTML='<i class="bi bi-lock-fill me-2"></i>Pay Now';
        btn.disabled=false;
      });
  }
</script>
<script src="search-state.js"></script>
</body>
</html>


