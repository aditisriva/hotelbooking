<?php require_once 'pricing.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%231a56db'/%3E%3Ctext x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-size='18' font-family='system-ui' fill='%23f59e0b'%3E&#x1F3E8;%3C/text%3E%3C/svg%3E"/>
  <title>Guest Details � bookHotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" crossorigin="anonymous"/>
  <link rel="stylesheet" href="style.css"/>
  <link rel="stylesheet" href="hotels.css"/>
  <link rel="stylesheet" href="booking.css"/>
</head>
<body>

<!-- ========== NAVBAR ========== -->
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand fw-800 fs-4" href="index.php">
      <i class="bi bi-building-fill text-warning me-1"></i>bookHotel
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="hotels.php">Hotels</a></li>
        <li class="nav-item"><a class="nav-link" href="destinations.php">Destinations</a></li>
        <li class="nav-item"><a class="nav-link" href="my-bookings.php">My Bookings</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item ms-lg-3">
          <a class="btn btn-outline-warning btn-sm px-3" href="login.php">Login / Sign Up</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ========== PROGRESS BAR ========== -->
<div class="booking-progress-bar">
  <div class="container">
    <div class="d-flex align-items-center gap-2 mb-3">
      <a href="javascript:history.back()" class="text-warning text-decoration-none small fw-600">
        <i class="bi bi-arrow-left me-1"></i>Back to Review
      </a>
    </div>
    <div class="progress-steps">
      <div class="progress-step completed">
        <div class="step-circle"><i class="bi bi-check-lg"></i></div>
        <span>Select Room</span>
      </div>
      <div class="progress-line completed"></div>
      <div class="progress-step completed">
        <div class="step-circle"><i class="bi bi-check-lg"></i></div>
        <span>Review Booking</span>
      </div>
      <div class="progress-line completed"></div>
      <div class="progress-step active">
        <div class="step-circle">3</div>
        <span>Guest Details</span>
      </div>
      <div class="progress-line"></div>
      <div class="progress-step">
        <div class="step-circle">4</div>
        <span>Payment</span>
      </div>
    </div>
  </div>
</div>

<!-- ========== MAIN CONTENT ========== -->
<section class="py-4 bg-light">
  <div class="container">
    <div class="row g-4">

      <!-- LEFT COLUMN -->
      <div class="col-12 col-lg-8">

        <!-- Login prompt -->
        <div class="login-prompt-card mb-4">
          <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
              <div class="login-prompt-icon"><i class="bi bi-person-circle"></i></div>
              <div>
                <div class="fw-700">Sign in for faster checkout</div>
                <div class="text-muted small">Access saved addresses, exclusive member deals & booking history</div>
              </div>
            </div>
            <a href="#" class="btn btn-outline-primary btn-sm px-4 fw-600">Login / Sign Up</a>
          </div>
        </div>

        <!-- Primary Guest -->
        <div class="review-section-card mb-4">
          <div class="review-section-header">
            <h6 class="fw-700 mb-0"><i class="bi bi-person-fill me-2 text-warning"></i>Primary Guest Details</h6>
          </div>
          <div class="review-section-body">
            <div class="row g-3">
              <div class="col-12 col-md-2">
                <label class="form-label small fw-600 text-muted">TITLE</label>
                <select class="form-select" id="title">
                  <option>Mr.</option>
                  <option>Mrs.</option>
                  <option>Ms.</option>
                  <option>Dr.</option>
                </select>
              </div>
              <div class="col-12 col-md-5">
                <label class="form-label small fw-600 text-muted">FIRST NAME *</label>
                <input type="text" class="form-control" id="firstName" placeholder="As per ID proof"/>
              </div>
              <div class="col-12 col-md-5">
                <label class="form-label small fw-600 text-muted">LAST NAME *</label>
                <input type="text" class="form-control" id="lastName" placeholder="As per ID proof"/>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label small fw-600 text-muted">EMAIL ADDRESS *</label>
                <input type="email" class="form-control" id="email" placeholder="Booking confirmation will be sent here"/>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label small fw-600 text-muted">MOBILE NUMBER *</label>
                <div class="input-group">
                  <span class="input-group-text bg-white">???? +91</span>
                  <input type="tel" class="form-control" id="phone" placeholder="10-digit number" maxlength="10"/>
                </div>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label small fw-600 text-muted">DATE OF BIRTH</label>
                <input type="date" class="form-control" id="dob"/>
              </div>
              <div class="col-12 col-md-6">
                <label class="form-label small fw-600 text-muted">NATIONALITY</label>
                <select class="form-select">
                  <option selected>Indian</option>
                  <option>American</option>
                  <option>British</option>
                  <option>Other</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- ID Proof -->
        <div class="review-section-card mb-4">
          <div class="review-section-header">
            <h6 class="fw-700 mb-0"><i class="bi bi-card-text me-2 text-warning"></i>ID Proof Details</h6>
          </div>
          <div class="review-section-body">
            <p class="text-muted small mb-3">Valid government-issued ID required at check-in. Please provide the same ID as below.</p>
            <div class="row g-3">
              <div class="col-12 col-md-4">
                <label class="form-label small fw-600 text-muted">ID TYPE *</label>
                <select class="form-select" id="idType">
                  <option value="">Select ID type</option>
                  <option>Aadhaar Card</option>
                  <option>Passport</option>
                  <option>Driving License</option>
                  <option>Voter ID</option>
                  <option>PAN Card</option>
                </select>
              </div>
              <div class="col-12 col-md-8">
                <label class="form-label small fw-600 text-muted">ID NUMBER *</label>
                <input type="text" class="form-control" id="idNumber" placeholder="Enter ID number"/>
              </div>
            </div>
          </div>
        </div>

        <!-- Additional Guests -->
        <div class="review-section-card mb-4">
          <div class="review-section-header d-flex justify-content-between align-items-center">
            <h6 class="fw-700 mb-0"><i class="bi bi-people-fill me-2 text-warning"></i>Additional Guests</h6>
            <button class="btn btn-outline-primary btn-sm fw-600" onclick="addGuest()">
              <i class="bi bi-plus-circle me-1"></i>Add Guest
            </button>
          </div>
          <div class="review-section-body">
            <p class="text-muted small mb-3">Room for 2 adults. You can add up to 1 more adult.</p>
            <div id="guestsList">
              <!-- dynamically added -->
            </div>
            <div id="noGuestsMsg" class="text-center py-3 text-muted small">
              <i class="bi bi-person-plus fs-2 opacity-25 d-block mb-2"></i>
              No additional guests added yet
            </div>
          </div>
        </div>

        <!-- Special Requests -->
        <div class="review-section-card mb-4">
          <div class="review-section-header">
            <h6 class="fw-700 mb-0"><i class="bi bi-chat-square-text me-2 text-warning"></i>Special Requests</h6>
          </div>
          <div class="review-section-body">
            <p class="text-muted small mb-3">While we can't guarantee requests, we'll do our best to accommodate your preferences.</p>
            <div class="row g-3 mb-3">
              <div class="col-12">
                <label class="form-label small fw-600 text-muted">BED PREFERENCE</label>
                <div class="d-flex flex-wrap gap-2">
                  <label class="pref-chip"><input type="radio" name="bed" checked/><span>King Bed</span></label>
                  <label class="pref-chip"><input type="radio" name="bed"/><span>Twin Beds</span></label>
                  <label class="pref-chip"><input type="radio" name="bed"/><span>No Preference</span></label>
                </div>
              </div>
              <div class="col-12">
                <label class="form-label small fw-600 text-muted">FLOOR PREFERENCE</label>
                <div class="d-flex flex-wrap gap-2">
                  <label class="pref-chip"><input type="radio" name="floor" checked/><span>No Preference</span></label>
                  <label class="pref-chip"><input type="radio" name="floor"/><span>High Floor</span></label>
                  <label class="pref-chip"><input type="radio" name="floor"/><span>Low Floor</span></label>
                </div>
              </div>
              <div class="col-12">
                <label class="form-label small fw-600 text-muted">OTHER REQUESTS</label>
                <div class="d-flex flex-wrap gap-2 mb-3">
                  <label class="pref-check"><input type="checkbox"/><span>Early Check-in</span></label>
                  <label class="pref-check"><input type="checkbox"/><span>Late Check-out</span></label>
                  <label class="pref-check"><input type="checkbox"/><span>Airport Transfer</span></label>
                  <label class="pref-check"><input type="checkbox"/><span>Anniversary Setup</span></label>
                  <label class="pref-check"><input type="checkbox"/><span>Birthday Decoration</span></label>
                  <label class="pref-check"><input type="checkbox"/><span>Extra Towels</span></label>
                </div>
                <textarea class="form-control" rows="3" placeholder="Any other special requests or requirements..."></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Arrival Time -->
        <div class="review-section-card mb-4">
          <div class="review-section-header">
            <h6 class="fw-700 mb-0"><i class="bi bi-clock me-2 text-warning"></i>Estimated Arrival Time</h6>
          </div>
          <div class="review-section-body">
            <p class="text-muted small mb-3">Let the hotel know when to expect you. Check-in from 2:00 PM.</p>
            <div class="row g-3">
              <div class="col-12 col-md-4">
                <select class="form-select">
                  <option>I don't know yet</option>
                  <option>Before 12:00 PM</option>
                  <option>12:00 PM � 2:00 PM</option>
                  <option>2:00 PM � 4:00 PM</option>
                  <option>4:00 PM � 6:00 PM</option>
                  <option>6:00 PM � 8:00 PM</option>
                  <option>After 8:00 PM</option>
                </select>
              </div>
            </div>
          </div>
        </div>

      </div><!-- end left col -->

      <!-- RIGHT: Booking Summary -->
      <div class="col-12 col-lg-4">
        <div class="price-summary-card sticky-booking">
          <div class="price-summary-header">
            <h6 class="fw-700 text-white mb-0"><i class="bi bi-receipt me-2"></i>Booking Summary</h6>
          </div>
          <div class="price-summary-body">

            <!-- Hotel -->
            <div class="d-flex gap-2 align-items-center mb-3">
              <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=80&q=80"
                   class="rounded-2" width="56" height="48" style="object-fit:cover" alt="Hotel"/>
              <div>
                <div class="fw-700 small">Heritage Haveli</div>
                <div class="text-muted" style="font-size:0.72rem"><i class="bi bi-geo-alt-fill text-danger"></i> Jaipur, Rajasthan</div>
                <span class="text-warning" style="font-size:0.7rem">?????</span>
              </div>
            </div>
            <hr class="my-2"/>

            <!-- Stay info -->
            <div class="d-flex justify-content-between mb-1">
              <span class="small text-muted">Room</span>
              <span class="small fw-600" id="sumRoom">Deluxe Heritage Room</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span class="small text-muted">Check-in</span>
              <span class="small fw-600" id="sumCheckin">�</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span class="small text-muted">Check-out</span>
              <span class="small fw-600" id="sumCheckout">�</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
              <span class="small text-muted">Duration</span>
              <span class="small fw-600" id="sumNights">2 Nights</span>
            </div>
            <hr class="my-2"/>

            <!-- Pricing -->
            <div class="d-flex justify-content-between mb-1">
              <span class="small text-muted" id="sumBaseLabel">?4,680 � 2 nights</span>
              <span class="small fw-600" id="sumBase">?9,360</span>
            </div>
            <div class="d-flex justify-content-between mb-1">
              <span class="small text-muted" id="sumTaxLabel">Taxes (GST)</span>
              <span class="small fw-600" id="sumTax">?842</span>
            </div>
                        <div class="d-flex justify-content-between mb-1">
              <span class="small text-muted">Service Charge</span>
              <span class="small fw-600">?200</span>
            </div>
            <div class="d-flex justify-content-between mb-3">
              <span class="small text-success fw-600">Discount</span>
              <span class="small fw-600 text-success" id="sumDiscount">-?3,276</span>
            </div>
            <hr class="my-2"/>
            <div class="d-flex justify-content-between align-items-center mb-1">
              <span class="fw-700">Total</span>
              <span class="fw-800 text-primary fs-5" id="sumTotal">?6,926</span>
            </div>
            <p class="text-muted small mb-4">Inclusive of all taxes</p>

            <div class="savings-badge mb-4">
              <i class="bi bi-piggy-bank-fill me-2 text-success"></i>
              <span class="small fw-700 text-success">Saving <span id="savingsAmt">?3,276</span> on this booking!</span>
            </div>

            <button class="btn book-now-btn w-100 fw-700 py-3 mb-3" onclick="goToPayment()">
              <i class="bi bi-lock-fill me-2"></i>Proceed to Payment
            </button>
            <p class="text-center text-muted small mb-0">
              <i class="bi bi-shield-check-fill text-success me-1"></i>100% Secure � SSL Encrypted
            </p>
          </div>
        </div>

        <!-- Inclusions -->
        <div class="review-section-card mt-3">
          <div class="review-section-header">
            <h6 class="fw-700 mb-0 small"><i class="bi bi-check2-all me-2 text-success"></i>What's Included</h6>
          </div>
          <div class="review-section-body py-3 px-3">
            <ul class="info-list">
              <li><i class="bi bi-check-circle-fill text-success"></i>Breakfast for 2 adults</li>
              <li><i class="bi bi-check-circle-fill text-success"></i>Free high-speed WiFi</li>
              <li><i class="bi bi-check-circle-fill text-success"></i>Free valet parking</li>
              <li><i class="bi bi-check-circle-fill text-success"></i>Access to pool & gym</li>
              <li><i class="bi bi-check-circle-fill text-success"></i>24/7 concierge service</li>
            </ul>
          </div>
        </div>

        <div class="trust-badges mt-3">
          <div class="trust-item"><i class="bi bi-shield-lock-fill text-success"></i><span>Secure</span></div>
          <div class="trust-item"><i class="bi bi-arrow-counterclockwise text-primary"></i><span>Free Cancel</span></div>
          <div class="trust-item"><i class="bi bi-headset text-warning"></i><span>24/7 Help</span></div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ========== FOOTER ========== -->
<footer class="bg-dark text-white pt-5 pb-3">
  <div class="container">
    <div class="row g-4 mb-4">
      <div class="col-12 col-md-4">
        <h5 class="fw-800 mb-3"><i class="bi bi-building-fill text-warning me-1"></i>bookHotel</h5>
        <p class="text-white-50 small">Your trusted travel partner since 2015.</p>
        <div class="d-flex gap-3 mt-3">
          <a href="#" class="text-white-50 social-icon"><i class="bi bi-facebook fs-5"></i></a>
          <a href="#" class="text-white-50 social-icon"><i class="bi bi-instagram fs-5"></i></a>
          <a href="#" class="text-white-50 social-icon"><i class="bi bi-twitter-x fs-5"></i></a>
          <a href="#" class="text-white-50 social-icon"><i class="bi bi-linkedin fs-5"></i></a>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="fw-700 mb-3">Company</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="#">About Us</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="#">Blog</a></li>
        </ul>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="fw-700 mb-3">Support</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="#">Help Center</a></li>
          <li><a href="contact.php">Contact Us</a></li>
          <li><a href="#">Cancellation</a></li>
        </ul>
      </div>
    </div>
    <hr class="border-secondary"/>
    <p class="text-white-50 small text-center mb-0">� 2026 bookHotel Technologies Pvt. Ltd. All rights reserved.</p>
  </div>
</footer>

<button id="backToTop" class="btn btn-warning btn-sm rounded-circle shadow" aria-label="Back to top" onclick="window.scrollTo({top:0,behavior:'smooth'})">
  <i class="bi bi-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script>
  // Navbar scroll
  window.addEventListener('scroll', () => {
    document.getElementById('mainNav').classList.toggle('scrolled', window.scrollY > 50);
    document.getElementById('backToTop').classList.toggle('show', window.scrollY > 300);
  });

  // Room data
  const rooms = {
    'deluxe':   { name: 'Deluxe Heritage Room',        price: 4680,  discount: 0.35 },
    'royal':    { name: 'Royal Suite',                  price: 9800,  discount: 0.30 },
    'maharaja': { name: 'Maharaja Presidential Suite',  price: 19500, discount: 0.30 }
  };

  const params   = new URLSearchParams(window.location.search);
  const roomKey  = params.get('room')     || 'deluxe';
  const checkin  = params.get('checkin')  || '';
  const checkout = params.get('checkout') || '';
  const room     = rooms[roomKey] || rooms['deluxe'];

  // Pre-fill from previous page if available
  if (params.get('first')) document.getElementById('firstName').value = params.get('first');
  if (params.get('email')) document.getElementById('email').value     = params.get('email');
  if (params.get('phone')) document.getElementById('phone').value     = params.get('phone');

  // Calc
  const ci = new Date(checkin), co = new Date(checkout);
  const nights = Math.max(1, Math.round((co - ci) / 86400000));
  const base   = room.price * nights;
    // Dynamic GST: 0% =2500, 12% =7500, 18% >7500
  const taxRate = room.price <= 2500 ? 0 : room.price <= 7500 ? 0.12 : 0.18;
  const taxPct  = Math.round(taxRate * 100);
  const svc     = 200; // service charge
  const tax     = Math.round(base * taxRate);
  const disc   = Math.round(base * room.discount);
  const total  = base + tax - disc + svc;

  const fmtDate = s => new Date(s).toLocaleDateString('en-IN',{weekday:'short',day:'2-digit',month:'short',year:'numeric'});

  document.getElementById('sumRoom').textContent      = room.name;
  document.getElementById('sumCheckin').textContent   = fmtDate(checkin);
  document.getElementById('sumCheckout').textContent  = fmtDate(checkout);
  document.getElementById('sumNights').textContent    = nights + ' Night' + (nights>1?'s':'');
  document.getElementById('sumBaseLabel').textContent = '?' + room.price.toLocaleString() + ' � ' + nights + ' night' + (nights>1?'s':'');
  document.getElementById('sumBase').textContent      = '?' + base.toLocaleString();
    document.getElementById('sumTax').textContent        = '?' + tax.toLocaleString();
  const taxLabelEl = document.getElementById('sumTaxLabel');
  if (taxLabelEl) taxLabelEl.textContent = 'GST (' + taxPct + '%)';
  document.getElementById('sumDiscount').textContent  = '-?' + disc.toLocaleString();
  document.getElementById('sumTotal').textContent     = '?' + total.toLocaleString();
  document.getElementById('savingsAmt').textContent   = '?' + disc.toLocaleString();

  // Add guest
  let guestCount = 0;
  function addGuest() {
    if (guestCount >= 1) { alert('Maximum 1 additional guest allowed for this room.'); return; }
    guestCount++;
    document.getElementById('noGuestsMsg').style.display = 'none';
    const div = document.createElement('div');
    div.className = 'additional-guest-row';
    div.id = 'guest_' + guestCount;
    div.innerHTML = `
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="fw-600 small">Guest ${guestCount}</span>
        <button class="btn btn-sm btn-outline-danger" onclick="removeGuest(${guestCount})">
          <i class="bi bi-trash3"></i> Remove
        </button>
      </div>
      <div class="row g-2">
        <div class="col-6"><input type="text" class="form-control form-control-sm" placeholder="First Name"/></div>
        <div class="col-6"><input type="text" class="form-control form-control-sm" placeholder="Last Name"/></div>
      </div>`;
    document.getElementById('guestsList').appendChild(div);
  }
  function removeGuest(id) {
    document.getElementById('guest_' + id).remove();
    guestCount--;
    if (guestCount === 0) document.getElementById('noGuestsMsg').style.display = 'block';
  }

  // Proceed to payment
  function goToPayment() {
    const first = document.getElementById('firstName').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const idType = document.getElementById('idType').value;
    const idNum  = document.getElementById('idNumber').value.trim();
    if (!first || !email || !phone) {
      alert('Please fill in all required guest details (Name, Email, Mobile).');
      return;
    }
    if (!idType || !idNum) {
      alert('Please provide your ID proof type and number.');
      return;
    }
    const p = new URLSearchParams(params.toString());
    p.set('first', first); p.set('email', email); p.set('phone', phone);
    window.location.href = 'payment.php?' + p.toString();
  }
</script>
<script src="search-state.js"></script>
</body>
</html>


