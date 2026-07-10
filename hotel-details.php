<?php
// Read booking params passed from search
$city_hd      = isset($_GET['city'])     ? trim($_GET['city'])     : 'Jaipur';
$city_hd_lbl  = ucfirst(strtolower($city_hd));
$checkin_raw  = isset($_GET['checkin'])  ? trim($_GET['checkin'])  : '';
$checkout_raw = isset($_GET['checkout']) ? trim($_GET['checkout']) : '';
$guests_raw   = isset($_GET['guests'])   ? (int)$_GET['guests']   : 2;
$nights       = 1;
if ($checkin_raw && $checkout_raw) {
    $diff   = (strtotime($checkout_raw) - strtotime($checkin_raw)) / 86400;
    $nights = max(1, (int)$diff);
}
function hd_fmt(string $d): string { return $d ? date('d M Y', strtotime($d)) : ''; }
$checkin_fmt  = hd_fmt($checkin_raw);
$checkout_fmt = hd_fmt($checkout_raw);
$guests_label = $guests_raw >= 6 ? '3 Rooms, 6 Guests' : ($guests_raw >= 4 ? '2 Rooms, 4 Guests' : ($guests_raw == 1 ? '1 Room, 1 Guest' : '1 Room, 2 Guests'));
$qs_parts = [];
if ($city_hd)      $qs_parts_city[] = 'city='     . urlencode(strtolower($city_hd));
if ($checkin_raw)  $qs_parts[] = 'checkin='  . urlencode($checkin_raw);
if ($checkout_raw) $qs_parts[] = 'checkout=' . urlencode($checkout_raw);
if ($guests_raw)   $qs_parts[] = 'guests='   . $guests_raw;
$booking_qs = $qs_parts ? '&' . implode('&', $qs_parts) : '';
$full_qs    = array_merge($qs_parts_city ?? [], $qs_parts);
$full_qs_str = $full_qs ? '?' . implode('&', $full_qs) : '';
?><?php require_once 'pricing.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%231a56db'/%3E%3Ctext x='50%25' y='54%25' dominant-baseline='middle' text-anchor='middle' font-size='18' font-family='system-ui' fill='%23f59e0b'%3E&#x1F3E8;%3C/text%3E%3C/svg%3E"/>
  <title>Heritage Haveli – bookHotel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" crossorigin="anonymous"/>
  <link rel="stylesheet" href="style.css"/>
  <link rel="stylesheet" href="hotels.css"/>
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
        <li class="nav-item"><a class="nav-link active" href="hotels.php">Hotels</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#destinations">Destinations</a></li>
        <li class="nav-item"><a class="nav-link" href="my-bookings.php">My Bookings</a></li>
        <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
        <li class="nav-item ms-lg-3">
          <a class="btn btn-outline-warning btn-sm px-3" href="#">Login / Sign Up</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ========== STICKY SEARCH BAR ========== -->
<div class="hd-search-bar" id="hdSearchBar">
  <div class="container">
    <form class="hd-search-form" onsubmit="hdSearch(event)">
      <!-- Destination -->
      <div class="hd-search-field">
        <div class="hd-search-label"><i class="bi bi-geo-alt-fill"></i> Destination</div>
        <input type="text" class="hd-search-input" id="hdCity"
          value="<?php echo htmlspecialchars($city_hd_lbl . ', India'); ?>"
          placeholder="City or Hotel"/>
      </div>
      <div class="hd-search-sep"></div>
      <!-- Check-in -->
      <div class="hd-search-field">
        <div class="hd-search-label"><i class="bi bi-calendar-check"></i> Check-In</div>
        <input type="date" class="hd-search-input" id="hdCheckin"
          value="<?php echo htmlspecialchars($checkin_raw); ?>"/>
        <?php if ($checkin_fmt): ?>
        <div class="hd-search-sub"><?php echo htmlspecialchars($checkin_fmt); ?></div>
        <?php endif; ?>
      </div>
      <div class="hd-search-sep"></div>
      <!-- Check-out -->
      <div class="hd-search-field">
        <div class="hd-search-label"><i class="bi bi-calendar-x"></i> Check-Out</div>
        <input type="date" class="hd-search-input" id="hdCheckout"
          value="<?php echo htmlspecialchars($checkout_raw); ?>"/>
        <?php if ($checkout_fmt): ?>
        <div class="hd-search-sub"><?php echo htmlspecialchars($checkout_fmt); ?>
          <?php if ($nights > 1): ?> · <strong><?php echo $nights; ?> Nights</strong><?php endif; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="hd-search-sep"></div>
      <!-- Guests -->
      <div class="hd-search-field">
        <div class="hd-search-label"><i class="bi bi-people-fill"></i> Rooms & Guests</div>
        <select class="hd-search-input" id="hdGuests">
          <option value="1" <?php echo $guests_raw==1?'selected':''; ?>>1 Room, 1 Guest</option>
          <option value="2" <?php echo (!$guests_raw||$guests_raw==2)?'selected':''; ?>>1 Room, 2 Guests</option>
          <option value="3" <?php echo $guests_raw==3?'selected':''; ?>>1 Room, 3 Guests</option>
          <option value="4" <?php echo $guests_raw==4?'selected':''; ?>>2 Rooms, 4 Guests</option>
          <option value="6" <?php echo $guests_raw>=6?'selected':''; ?>>3 Rooms, 6 Guests</option>
        </select>
        <div class="hd-search-sub"><?php echo htmlspecialchars($guests_label); ?></div>
      </div>
      <!-- Search Button -->
      <button type="submit" class="hd-search-btn">
        <i class="bi bi-search me-1"></i>Search
      </button>
    </form>
  </div>
</div>

<!-- ========== BREADCRUMB ========== -->
<div class="bg-white border-bottom py-2">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0 small">
        <li class="breadcrumb-item"><a href="index.php" class="text-danger text-decoration-none">Home</a></li>
        <li class="breadcrumb-item"><a href="hotels.php" class="text-danger text-decoration-none">Hotels</a></li>
        <li class="breadcrumb-item active text-muted">Heritage Haveli, Jaipur</li>
      </ol>
    </nav>
  </div>
</div>

<!-- ========== IMAGE GALLERY ========== -->
<section class="gallery-section bg-dark">
  <div class="container-fluid px-0">
    <div class="gallery-grid">
      <div class="gallery-main">
        <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1000&q=85" alt="Heritage Haveli Main"/>
        <button class="btn btn-light btn-sm gallery-all-btn" data-bs-toggle="modal" data-bs-target="#galleryModal">
          <i class="bi bi-images me-1"></i>View All 24 Photos
        </button>
      </div>
      <div class="gallery-thumbs">
        <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=400&q=80" alt="Deluxe Room"/>
        <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=400&q=80" alt="Suite"/>
        <img src="https://images.unsplash.com/photo-1540541338537-1220059ddcdd?w=400&q=80" alt="Restaurant"/>
        <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=400&q=80" alt="Pool"/>
      </div>
    </div>
  </div>
</section>

<!-- ========== MAIN CONTENT ========== -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="row g-4">

      <!-- LEFT: Hotel Info -->
      <div class="col-12 col-lg-8">

        <!-- Hotel Header -->
        <div class="detail-card mb-4">
          <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
            <div>
              <div class="d-flex gap-2 align-items-center mb-2">
                <span class="text-warning">★★★★★</span>
                <span class="badge bg-warning text-dark">Luxury</span>
                <span class="badge bg-success">Free Cancellation</span>
              </div>
              <h1 class="fw-800 fs-3 mb-1">Heritage Haveli</h1>
              <p class="text-muted mb-0"><i class="bi bi-geo-alt-fill text-danger me-1"></i>M.I. Road, Pink City, Jaipur, Rajasthan 302001
                <a href="#" class="text-primary ms-2 small">View on Map</a>
              </p>
            </div>
            <div class="text-end">
              <div class="d-flex align-items-center gap-2 justify-content-end mb-1">
                <div class="rating-big">4.9</div>
                <div>
                  <div class="fw-700 small">Exceptional</div>
                  <div class="text-muted" style="font-size:0.75rem">1,284 reviews</div>
                </div>
              </div>
              <button class="btn-wishlist-lg"><i class="bi bi-heart me-1"></i>Save</button>
            </div>
          </div>
          <!-- Quick amenity icons -->
          <div class="d-flex flex-wrap gap-3">
            <span class="quick-amenity"><i class="bi bi-wifi text-primary"></i> Free WiFi</span>
            <span class="quick-amenity"><i class="bi bi-droplet-fill text-info"></i> Pool</span>
            <span class="quick-amenity"><i class="bi bi-cup-hot text-warning"></i> Breakfast</span>
            <span class="quick-amenity"><i class="bi bi-car-front text-success"></i> Free Parking</span>
            <span class="quick-amenity"><i class="bi bi-flower1 text-danger"></i> Spa</span>
            <span class="quick-amenity"><i class="bi bi-dumbbell text-secondary"></i> Gym</span>
          </div>

          <!-- Availability Summary Strip -->
          <?php if ($checkin_raw || $guests_raw): ?>
          <div class="mt-3 p-3 rounded-3 d-flex flex-wrap gap-3 align-items-center" style="background:linear-gradient(135deg,#e8f0fe,#dbeafe);border:1.5px solid #bfdbfe">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-calendar-check-fill text-primary"></i>
              <div>
                <div style="font-size:.72rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.06em">Check-in</div>
                <div style="font-weight:700;color:#1a1a2e;font-size:.875rem"><?php echo $checkin_raw ? hd_fmt($checkin_raw) : 'Select date'; ?></div>
              </div>
            </div>
            <i class="bi bi-arrow-right text-muted"></i>
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-calendar-x-fill text-danger"></i>
              <div>
                <div style="font-size:.72rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.06em">Check-out</div>
                <div style="font-weight:700;color:#1a1a2e;font-size:.875rem"><?php echo $checkout_raw ? hd_fmt($checkout_raw) : 'Select date'; ?></div>
              </div>
            </div>
            <?php if ($nights > 1): ?>
            <span class="badge bg-primary" style="font-size:.78rem"><?php echo $nights; ?> Nights</span>
            <?php endif; ?>
            <?php if ($guests_raw > 0): ?>
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-people-fill text-success"></i>
              <div style="font-weight:700;color:#1a1a2e;font-size:.875rem"><?php echo htmlspecialchars($guests_raw); ?> Guests</div>
            </div>
            <?php endif; ?>
            <a href="hotels.php?<?php echo $city_param ? 'city='.urlencode($city_param).'&' : ''; ?>checkin=<?php echo urlencode($checkin_raw); ?>&checkout=<?php echo urlencode($checkout_raw); ?>&guests=<?php echo $guests_raw; ?>" class="ms-auto btn btn-outline-primary btn-sm">
              <i class="bi bi-pencil-fill me-1"></i>Change Dates
            </a>
          </div>
          <?php endif; ?>
        </div>

        <!-- Description -->
        <div class="detail-card mb-4">
          <h5 class="fw-700 mb-3"><i class="bi bi-info-circle text-primary me-2"></i>About This Property</h5>
          <p class="text-muted">Heritage Haveli is a magnificent royal property nestled in the heart of Jaipur's Pink City. Built in the 18th century, this stunning haveli has been lovingly restored to its former glory while offering every modern luxury.</p>
          <p class="text-muted">Each room is uniquely decorated with original frescoes, antique furniture, and hand-crafted Rajasthani textiles. Guests can enjoy the rooftop terrace with panoramic views of the Amber Fort, dine at our award-winning restaurant serving authentic Rajasthani cuisine, or unwind at the Ananda Spa.</p>
          <p class="text-muted mb-0">Located just 500 meters from the famous Hawa Mahal and a short walk from the bustling bazaars, Heritage Haveli is the perfect base for exploring Jaipur's rich cultural heritage.</p>
        </div>

        <!-- Amenities -->
        <div class="detail-card mb-4">
          <h5 class="fw-700 mb-4"><i class="bi bi-grid-3x3-gap text-primary me-2"></i>Amenities</h5>
          <div class="row g-3">
            <div class="col-6 col-md-4">
              <div class="amenity-item"><i class="bi bi-wifi"></i><span>Free High-Speed WiFi</span></div>
              <div class="amenity-item"><i class="bi bi-droplet-fill"></i><span>Outdoor Pool</span></div>
              <div class="amenity-item"><i class="bi bi-flower1"></i><span>Luxury Spa</span></div>
            </div>
            <div class="col-6 col-md-4">
              <div class="amenity-item"><i class="bi bi-cup-hot"></i><span>Breakfast Included</span></div>
              <div class="amenity-item"><i class="bi bi-car-front"></i><span>Free Valet Parking</span></div>
            </div>
            <div class="col-6 col-md-4">
              <div class="amenity-item"><i class="bi bi-wind"></i><span>Air Conditioning</span></div>
              <div class="amenity-item"><i class="bi bi-reception-4"></i><span>24/7 Concierge</span></div>
            </div>
            <div class="col-6 col-md-4">
              <div class="amenity-item"><i class="bi bi-camera"></i><span>Heritage Tours</span></div>
              <div class="amenity-item"><i class="bi bi-music-note-beamed"></i><span>Cultural Performances</span></div>
              <div class="amenity-item"><i class="bi bi-luggage"></i><span>Luggage Storage</span></div>
            </div>
            <div class="col-6 col-md-4">
              <div class="amenity-item"><i class="bi bi-shield-check"></i><span>24/7 Security</span></div>
              <div class="amenity-item"><i class="bi bi-telephone"></i><span>Room Service</span></div>
              <div class="amenity-item"><i class="bi bi-person-check"></i><span>Butler Service</span></div>
            </div>
            <div class="col-6 col-md-4">
              <div class="amenity-item"><i class="bi bi-sun"></i><span>Rooftop Terrace</span></div>
              <div class="amenity-item"><i class="bi bi-people"></i><span>Event Spaces</span></div>
              <div class="amenity-item"><i class="bi bi-arrow-repeat"></i><span>Free Cancellation</span></div>
            </div>
          </div>
        </div>

        <!-- Room Types -->
        <div class="detail-card mb-4">
          <h5 class="fw-700 mb-4"><i class="bi bi-door-open text-primary me-2"></i>Room Types</h5>
          <div class="d-flex flex-column gap-3">

            <!-- Room 1: Deluxe -->
            <div class="room-card">
              <div class="row g-0">
                <div class="col-4 col-md-3">
                  <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=300&q=80" class="room-img" alt="Deluxe Room"/>
                </div>
                <div class="col-8 col-md-9">
                  <div class="p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                      <h6 class="fw-700 mb-1">Deluxe Heritage Room</h6>
                      <p class="text-muted small mb-2">30 m² · King Bed · City View · Non-smoking</p>
                      <div class="d-flex flex-wrap gap-1 mb-2">
                        <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                        <span class="amenity-tag"><i class="bi bi-cup-hot"></i> Breakfast</span>
                        <span class="amenity-tag"><i class="bi bi-arrow-repeat"></i> Free Cancel</span>
                      </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                      <div>
                        <?php bhPriceBlock(4680, 7200); ?>
                      </div>
                      <a href="review-booking.php?room=deluxe<?php echo $booking_qs; ?>" class="btn btn-primary btn-sm px-4">Select</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Room 2: Royal Suite -->
            <div class="room-card">
              <div class="row g-0">
                <div class="col-4 col-md-3">
                  <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=300&q=80" class="room-img" alt="Royal Suite"/>
                </div>
                <div class="col-8 col-md-9">
                  <div class="p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                      <h6 class="fw-700 mb-1">Royal Suite</h6>
                      <p class="text-muted small mb-2">65 m² · King Bed · Fort View · Balcony</p>
                      <div class="d-flex flex-wrap gap-1 mb-2">
                        <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                        <span class="amenity-tag"><i class="bi bi-cup-hot"></i> Breakfast</span>
                        <span class="amenity-tag"><i class="bi bi-flower1"></i> Spa Access</span>
                      </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                      <div>
                        <?php bhPriceBlock(9800, 14000); ?>
                      </div>
                      <a href="review-booking.php?room=royal<?php echo $booking_qs; ?>" class="btn btn-primary btn-sm px-4">Select</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Room 3: Maharaja Suite -->
            <div class="room-card">
              <div class="row g-0">
                <div class="col-4 col-md-3">
                  <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=300&q=80" class="room-img" alt="Maharaja Suite"/>
                </div>
                <div class="col-8 col-md-9">
                  <div class="p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                      <h6 class="fw-700 mb-1">Maharaja Presidential Suite</h6>
                      <p class="text-muted small mb-2">120 m² · Private Terrace · Panoramic View</p>
                      <div class="d-flex flex-wrap gap-1 mb-2">
                        <span class="amenity-tag"><i class="bi bi-wifi"></i> WiFi</span>
                        <span class="amenity-tag"><i class="bi bi-cup-hot"></i> All Meals</span>
                        <span class="amenity-tag"><i class="bi bi-person-check"></i> Butler</span>
                      </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                      <div>
                        <?php bhPriceBlock(19500, 28000); ?>
                      </div>
                      <a href="review-booking.php?room=maharaja<?php echo $booking_qs; ?>" class="btn btn-primary btn-sm px-4">Select</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Guest Reviews -->
        <div class="detail-card mb-4">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-700 mb-0"><i class="bi bi-chat-quote text-primary me-2"></i>Guest Reviews</h5>
            <div class="d-flex align-items-center gap-2">
              <div class="rating-big">4.9</div>
              <div>
                <div class="fw-700 small">Exceptional</div>
                <div class="text-muted small">1,284 reviews</div>
              </div>
            </div>
          </div>

          <!-- Rating Bars -->
          <div class="row g-2 mb-4">
            <div class="col-6">
              <div class="d-flex align-items-center gap-2 mb-2">
                <span class="text-muted small w-auto" style="min-width:90px">Cleanliness</span>
                <div class="progress flex-grow-1" style="height:6px"><div class="progress-bar bg-success" style="width:98%"></div></div>
                <span class="small fw-600">4.9</span>
              </div>
              <div class="d-flex align-items-center gap-2 mb-2">
                <span class="text-muted small w-auto" style="min-width:90px">Service</span>
                <div class="progress flex-grow-1" style="height:6px"><div class="progress-bar bg-success" style="width:96%"></div></div>
                <span class="small fw-600">4.8</span>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="text-muted small w-auto" style="min-width:90px">Location</span>
                <div class="progress flex-grow-1" style="height:6px"><div class="progress-bar bg-success" style="width:94%"></div></div>
                <span class="small fw-600">4.7</span>
              </div>
            </div>
            <div class="col-6">
              <div class="d-flex align-items-center gap-2 mb-2">
                <span class="text-muted small w-auto" style="min-width:90px">Value</span>
                <div class="progress flex-grow-1" style="height:6px"><div class="progress-bar bg-success" style="width:92%"></div></div>
                <span class="small fw-600">4.6</span>
              </div>
              <div class="d-flex align-items-center gap-2 mb-2">
                <span class="text-muted small w-auto" style="min-width:90px">Amenities</span>
                <div class="progress flex-grow-1" style="height:6px"><div class="progress-bar bg-success" style="width:96%"></div></div>
                <span class="small fw-600">4.8</span>
              </div>
              <div class="d-flex align-items-center gap-2">
                <span class="text-muted small w-auto" style="min-width:90px">Food</span>
                <div class="progress flex-grow-1" style="height:6px"><div class="progress-bar bg-success" style="width:98%"></div></div>
                <span class="small fw-600">4.9</span>
              </div>
            </div>
          </div>

          <!-- Review Cards -->
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <div class="review-card p-3 h-100">
                <div class="d-flex gap-1 text-warning mb-2 small">★★★★★</div>
                <p class="text-muted small mb-3">"Absolutely breathtaking property. The architecture, the food, the service — everything was flawless. Felt like a true Maharaja. Will definitely return!"</p>
                <div class="d-flex align-items-center gap-2">
                  <img src="https://i.pravatar.cc/40?img=5" class="rounded-circle" width="36" height="36" alt="Guest"/>
                  <div>
                    <div class="fw-700 small">Priya Sharma</div>
                    <div class="text-muted" style="font-size:0.72rem">Mumbai · June 2026</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="review-card p-3 h-100">
                <div class="d-flex gap-1 text-warning mb-2 small">★★★★★</div>
                <p class="text-muted small mb-3">"The rooftop view of Amber Fort at sunset is something I will never forget. The Royal Suite was absolutely spectacular. Worth every rupee!"</p>
                <div class="d-flex align-items-center gap-2">
                  <img src="https://i.pravatar.cc/40?img=12" class="rounded-circle" width="36" height="36" alt="Guest"/>
                  <div>
                    <div class="fw-700 small">Rahul Verma</div>
                    <div class="text-muted" style="font-size:0.72rem">Delhi · May 2026</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="review-card p-3 h-100">
                <div class="d-flex gap-1 text-warning mb-2 small">★★★★⭐</div>
                <p class="text-muted small mb-3">"The heritage tour organised by the hotel was exceptional. Our guide was knowledgeable and the haveli's history is fascinating. Excellent breakfast spread!"</p>
                <div class="d-flex align-items-center gap-2">
                  <img src="https://i.pravatar.cc/40?img=21" class="rounded-circle" width="36" height="36" alt="Guest"/>
                  <div>
                    <div class="fw-700 small">Ananya Kapoor</div>
                    <div class="text-muted" style="font-size:0.72rem">Bangalore · April 2026</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 col-md-6">
              <div class="review-card p-3 h-100">
                <div class="d-flex gap-1 text-warning mb-2 small">★★★★★</div>
                <p class="text-muted small mb-3">"Booked the Maharaja Suite for our anniversary. The butler service and personalised attention was beyond 5-star. The spa treatments are world-class."</p>
                <div class="d-flex align-items-center gap-2">
                  <img src="https://i.pravatar.cc/40?img=33" class="rounded-circle" width="36" height="36" alt="Guest"/>
                  <div>
                    <div class="fw-700 small">Vikram Singh</div>
                    <div class="text-muted" style="font-size:0.72rem">Pune · March 2026</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center mt-3">
            <a href="#" class="btn btn-outline-primary btn-sm px-4">Load More Reviews</a>
          </div>
        </div>

      </div><!-- end left col -->

      <!-- RIGHT: Sticky Booking Sidebar -->
      <div class="col-12 col-lg-4">
        <div class="booking-card sticky-booking">
          <div class="booking-card-header text-white p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <h6 class="fw-700 mb-0"><i class="bi bi-calendar2-check me-2"></i>Book This Hotel</h6>
              <span class="badge bg-success small">Free Cancellation</span>
            </div>
            <?php bhPriceBlock(4680, 7200, false); ?>
          </div>
          <div class="booking-card-body">
            <!-- Dates -->
            <div class="row g-2 mb-3">
              <div class="col-6">
                <label class="form-label small fw-600 text-muted mb-1">CHECK-IN</label>
                <input type="date" class="form-control form-control-sm" id="bkCheckin" value="<?php echo htmlspecialchars($checkin_raw); ?>"/>
              </div>
              <div class="col-6">
                <label class="form-label small fw-600 text-muted mb-1">CHECK-OUT</label>
                <input type="date" class="form-control form-control-sm" id="bkCheckout" value="<?php echo htmlspecialchars($checkout_raw); ?>"/>
              </div>
            </div>
            <!-- Guests -->
            <div class="mb-3">
              <label class="form-label small fw-600 text-muted mb-1">GUESTS</label>
              <select class="form-select form-select-sm" id="bkGuests">
                <option value="1" <?php echo $guests_raw==1?'selected':''; ?>>1 Guest</option>
                <option value="2" <?php echo (!$guests_raw||$guests_raw==2)?'selected':''; ?>>2 Guests</option>
                <option value="3" <?php echo $guests_raw==3?'selected':''; ?>>3 Guests</option>
                <option value="4" <?php echo $guests_raw>=4?'selected':''; ?>>4 Guests</option>
              </select>
            </div>
            <!-- Price Breakdown -->
            <div class="price-breakdown-box mb-3" id="bkBreakdown">
              <?php
                $p_calc = bhCalcPricing(4680);
                $room_cost = 4680 * $nights;
                $tax_amt   = round($room_cost * bhTaxRate(4680));
                $disc      = round($room_cost * 0.35);
                $grand     = $room_cost - $disc + $tax_amt + SERVICE_CHARGE;
              ?>
              <div class="row-item"><span class="label">₹4,680 × <?php echo $nights; ?> night<?php echo $nights>1?'s':''; ?></span><span class="value">₹<?php echo number_format($room_cost); ?></span></div>
              <div class="row-item"><span class="label" style="color:#059669">Discount (35%)</span><span class="value" style="color:#059669">− ₹<?php echo number_format($disc); ?></span></div>
              <div class="row-item"><span class="label">GST (12%)</span><span class="value">₹<?php echo number_format($tax_amt); ?></span></div>
              <div class="row-item"><span class="label">Service Charge</span><span class="value">₹200</span></div>
              <div class="row-item total"><span>Total Payable</span><span class="value">₹<?php echo number_format($grand); ?></span></div>
              <div class="savings-row"><i class="bi bi-piggy-bank-fill"></i>You save ₹<?php echo number_format($disc); ?> on this booking!</div>
            </div>
            <!-- CTA -->
            <a href="review-booking.php?room=deluxe<?php echo $booking_qs; ?>" class="btn book-now-btn w-100 fw-700 py-3 mb-2">
              <i class="bi bi-calendar2-check me-2"></i>Book Now
            </a>
            <p class="text-muted text-center" style="font-size:.75rem"><i class="bi bi-shield-check text-success me-1"></i>Secure booking · No hidden charges</p>
          </div>
        </div>
      </div><!-- end right col -->

    </div><!-- end row -->
  </div><!-- end container -->
</section>

<!-- ========== SIMILAR HOTELS ========== -->
<section class="py-5 bg-white">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="fw-800 mb-1">Similar Hotels in Jaipur</h2>
        <p class="text-muted mb-0">You might also like these properties</p>
      </div>
      <a href="hotels.php" class="btn btn-outline-primary btn-sm">View All</a>
    </div>
    <div class="row g-4">
      <div class="col-12 col-md-6 col-lg-3">
        <div class="hotel-card card border-0 shadow-sm h-100">
          <div class="position-relative">
            <img src="https://images.unsplash.com/photo-1549294413-26f195200c16?w=400&q=80" class="card-img-top hotel-img" alt="Similar Hotel"/>
            <button class="btn-wishlist" aria-label="Wishlist"><i class="bi bi-heart"></i></button>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-1">
              <h6 class="fw-700 mb-0">Rambagh Palace</h6>
              <span class="rating-badge">4.8 <i class="bi bi-star-fill"></i></span>
            </div>
            <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>Jaipur, Rajasthan</p>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div><?php bhPriceBlock(8200); ?></div>
              <a href="hotel-details.php" class="btn btn-primary btn-sm px-3">View</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-3">
        <div class="hotel-card card border-0 shadow-sm h-100">
          <div class="position-relative">
            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&q=80" class="card-img-top hotel-img" alt="Similar Hotel"/>
            <button class="btn-wishlist" aria-label="Wishlist"><i class="bi bi-heart"></i></button>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-1">
              <h6 class="fw-700 mb-0">Jai Mahal Palace</h6>
              <span class="rating-badge">4.7 <i class="bi bi-star-fill"></i></span>
            </div>
            <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>Jaipur, Rajasthan</p>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div><?php bhPriceBlock(6500); ?></div>
              <a href="hotel-details.php" class="btn btn-primary btn-sm px-3">View</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-3">
        <div class="hotel-card card border-0 shadow-sm h-100">
          <div class="position-relative">
            <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=400&q=80" class="card-img-top hotel-img" alt="Similar Hotel"/>
            <button class="btn-wishlist" aria-label="Wishlist"><i class="bi bi-heart"></i></button>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-1">
              <h6 class="fw-700 mb-0">Samode Haveli</h6>
              <span class="rating-badge">4.6 <i class="bi bi-star-fill"></i></span>
            </div>
            <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>Jaipur, Rajasthan</p>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div><?php bhPriceBlock(5800); ?></div>
              <a href="hotel-details.php" class="btn btn-primary btn-sm px-3">View</a>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6 col-lg-3">
        <div class="hotel-card card border-0 shadow-sm h-100">
          <div class="position-relative">
            <img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=400&q=80" class="card-img-top hotel-img" alt="Similar Hotel"/>
            <button class="btn-wishlist" aria-label="Wishlist"><i class="bi bi-heart"></i></button>
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-1">
              <h6 class="fw-700 mb-0">Trident Jaipur</h6>
              <span class="rating-badge">4.5 <i class="bi bi-star-fill"></i></span>
            </div>
            <p class="text-muted small mb-2"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>Jaipur, Rajasthan</p>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div><?php bhPriceBlock(4200); ?></div>
              <a href="hotel-details.php" class="btn btn-primary btn-sm px-3">View</a>
            </div>
          </div>
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
        <p class="text-white-50 small">Your trusted travel partner since 2015. We make hotel booking simple, affordable, and enjoyable for millions of travellers.</p>
        <div class="d-flex gap-3 mt-3">
          <a href="#" class="text-white-50 social-icon"><i class="bi bi-facebook fs-5"></i></a>
          <a href="#" class="text-white-50 social-icon"><i class="bi bi-twitter-x fs-5"></i></a>
          <a href="#" class="text-white-50 social-icon"><i class="bi bi-instagram fs-5"></i></a>
          <a href="#" class="text-white-50 social-icon"><i class="bi bi-youtube fs-5"></i></a>
          <a href="#" class="text-white-50 social-icon"><i class="bi bi-linkedin fs-5"></i></a>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="fw-700 mb-3">Company</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="#">About Us</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="#">Press</a></li>
          <li><a href="#">Blog</a></li>
        </ul>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="fw-700 mb-3">Support</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="#">Help Center</a></li>
          <li><a href="contact.php">Contact Us</a></li>
          <li><a href="#">Cancellation Policy</a></li>
          <li><a href="#">Safety Info</a></li>
        </ul>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="fw-700 mb-3">Explore</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="hotels.php">Hotels</a></li>
          <li><a href="#">Destinations</a></li>
          <li><a href="my-bookings.php">My Bookings</a></li>
          <li><a href="#">Car Rentals</a></li>
        </ul>
      </div>
      <div class="col-6 col-md-2">
        <h6 class="fw-700 mb-3">Legal</h6>
        <ul class="list-unstyled footer-links">
          <li><a href="privacy-policy.php">Privacy Policy</a></li>
          <li><a href="terms-of-service.php">Terms of Use</a></li>
          <li><a href="cookie-policy.php">Cookie Policy</a></li>
          <li><a href="#">Sitemap</a></li>
        </ul>
      </div>
    </div>
    <hr class="border-secondary"/>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
      <p class="text-white-50 small mb-0">© 2026 bookHotel Technologies Pvt. Ltd. All rights reserved.</p>
      <div class="d-flex gap-2">
        <img src="https://img.shields.io/badge/Visa-1A1F71?style=flat&logo=visa&logoColor=white" height="20" alt="Visa"/>
        <img src="https://img.shields.io/badge/Mastercard-EB001B?style=flat&logo=mastercard&logoColor=white" height="20" alt="Mastercard"/>
        <img src="https://img.shields.io/badge/UPI-1a73e8?style=flat&logo=google-pay&logoColor=white" height="20" alt="UPI"/>
        <img src="https://img.shields.io/badge/PayPal-003087?style=flat&logo=paypal&logoColor=white" height="20" alt="PayPal"/>
      </div>
    </div>
  </div>
</footer>

<button id="backToTop" class="btn btn-warning btn-sm rounded-circle shadow" aria-label="Back to top" onclick="window.scrollTo({top:0,behavior:'smooth'})">
  <i class="bi bi-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="navbar.js"></script>
<script>
  window.addEventListener("scroll", () => {
    document.getElementById("mainNav").classList.toggle("scrolled", window.scrollY > 50);
    document.getElementById("backToTop")?.classList.toggle("show", window.scrollY > 300);
  });
  document.querySelectorAll(".btn-wishlist").forEach(btn => {
    btn.addEventListener("click", () => {
      const icon = btn.querySelector("i");
      icon.classList.toggle("bi-heart");
      icon.classList.toggle("bi-heart-fill");
      icon.classList.toggle("text-danger");
    });
  });
  // Hotel Details top search bar
  function hdSearch(e) {
    e.preventDefault();
    var city   = (document.getElementById("hdCity")?.value   || "").trim().toLowerCase().replace(/,.*$/, "").trim();
    var ci     = document.getElementById("hdCheckin")?.value  || "";
    var co     = document.getElementById("hdCheckout")?.value || "";
    var guests = document.getElementById("hdGuests")?.value   || "2";
    var qs = [];
    if (city)   qs.push("city="     + encodeURIComponent(city));
    if (ci)     qs.push("checkin="  + encodeURIComponent(ci));
    if (co)     qs.push("checkout=" + encodeURIComponent(co));
    if (guests) qs.push("guests="   + encodeURIComponent(guests));
    window.location.href = "hotels.php" + (qs.length ? "?" + qs.join("&") : "");
  }
  // Sync top search dates with sidebar booking card
  document.addEventListener("DOMContentLoaded", function() {
    var hdCi = document.getElementById("hdCheckin");
    var hdCo = document.getElementById("hdCheckout");
    var bkCi = document.getElementById("bkCheckin");
    var bkCo = document.getElementById("bkCheckout");
    function sync() { if(bkCi&&hdCi)bkCi.value=hdCi.value; if(bkCo&&hdCo)bkCo.value=hdCo.value; }
    if(hdCi) hdCi.addEventListener("change", sync);
    if(hdCo) hdCo.addEventListener("change", sync);
  });
</script>
</body>
</html>