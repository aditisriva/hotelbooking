<?php
require_once 'db.php';
require_once 'hotel_functions.php';
$hstats = bhHotelStats();
$total_hotels  = $hstats['total'];
$active_hotels = $hstats['active'];
$featured_hotels = $hstats['featured'];
$cities_count  = $hstats['cities'];

// Get user count from DB
$ucount_res = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM users WHERE status='active'");
$user_count = $ucount_res ? (int)mysqli_fetch_assoc($ucount_res)['cnt'] : 0;

// Get booking count (if table exists)
$bcount_res = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "' AND table_name = 'bookings'");
$bookings_table_exists = $bcount_res && (int)mysqli_fetch_assoc($bcount_res)['cnt'] > 0;
$booking_count = 0;
if ($bookings_table_exists) {
    $b = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM bookings");
    if ($b) $booking_count = (int)mysqli_fetch_assoc($b)['cnt'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BookHotel | Hotel Operations Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="dashboard.css" />
</head>
<body>
  <div class="ds-ov" id="dsOv"></div>
  <aside class="ds-sb" id="dsSb">
    <a href="admin-dashboard.php" class="ds-logo">
      <div class="ds-logo-icon"><i class="bi bi-buildings"></i></div>
      <div>
        <div class="ds-logo-name">BookHotel</div>
        <div class="ds-logo-role">Hotel Operations</div>
      </div>
    </a>
    <nav class="ds-nav">
      <div class="ds-sec">Main</div>
      <a href="admin-dashboard.php" class="ds-link active"><i class="bi bi-grid-fill"></i> Dashboard</a>
      <a href="admin-hotel-profile.php" class="ds-link"><i class="bi bi-building"></i> Hotel Profile</a>
      <div class="ds-sec">Operations</div>
      <a href="admin-rooms.php" class="ds-link"><i class="bi bi-door-open-fill"></i> Rooms</a>
      <a href="admin-bookings.php" class="ds-link"><i class="bi bi-calendar2-check-fill"></i> Bookings</a>
      <a href="admin-guests.php" class="ds-link"><i class="bi bi-people-fill"></i> Guests</a>
      <div class="ds-sec">Inventory & Pricing</div>
      <a href="admin-availability.php" class="ds-link"><i class="bi bi-calendar-range-fill"></i> Availability</a>
      <a href="admin-pricing.php" class="ds-link"><i class="bi bi-tags-fill"></i> Pricing</a>
      <a href="admin-discounts.php" class="ds-link"><i class="bi bi-percent"></i> Discounts</a>
      <div class="ds-sec">Insights</div>
      <a href="admin-reviews.php" class="ds-link"><i class="bi bi-star-fill"></i> Reviews</a>
      <a href="admin-revenue.php" class="ds-link"><i class="bi bi-bar-chart-fill"></i> Revenue</a>
      <a href="notifications.php" class="ds-link"><i class="bi bi-bell-fill"></i> Notifications</a>
      <div class="ds-sec">Account</div>
      <a href="admin-settings.php" class="ds-link"><i class="bi bi-sliders"></i> Settings</a>
      <a href="index.php" class="ds-link"><i class="bi bi-box-arrow-left"></i> Back to Website</a>
    </nav>
    <div class="ds-foot">
      <a href="admin-hotel-profile.php" class="ds-hpill">
        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=120&q=80" alt="Hotel" />
        <div>
          <div class="ds-hpill-name">The Grand Palace</div>
          <div class="ds-hpill-status">● Active · Mumbai</div>
        </div>
      </a>
    </div>
  </aside>

  <header class="ds-top">
    <div class="ds-top-l">
      <button class="ds-ibtn d-lg-none" id="dsTog"><i class="bi bi-list fs-5"></i></button>
      <div>
        <div class="ds-page-title">Hotel Operations Dashboard</div>
        <div class="ds-breadcrumb">Property control center · rooms, stays, and guest care</div>
      </div>
    </div>
    <div class="ds-top-r">
      <div class="ds-sw d-none d-md-block">
        <i class="bi bi-search ds-si-ic"></i>
        <input class="ds-inp search" type="text" placeholder="Search rooms, bookings, guests" />
      </div>
      <a href="notifications.php" class="ds-ibtn"><i class="bi bi-bell-fill"></i><span class="ds-dot"></span></a>
      <div class="ds-avbtn" id="dsAvBtn">
        <div class="ds-av">AD</div>
        <span class="ds-avname d-none d-sm-block">Aditi</span>
        <i class="bi bi-chevron-down ms-1" style="font-size:.7rem;color:var(--mut)"></i>
        <div class="ds-dropdown" id="dsAvMenu">
          <a href="admin-settings.php" class="ds-drop-item"><i class="bi bi-person-fill text-primary"></i> My Profile</a>
          <a href="admin-settings.php" class="ds-drop-item"><i class="bi bi-gear-fill text-primary"></i> Settings</a>
          <hr class="my-1 mx-2" />
          <a href="login.php" class="ds-drop-item danger"><i class="bi bi-box-arrow-right"></i> Sign Out</a>
        </div>
      </div>
    </div>
  </header>

  <main class="ds-main">
    <div class="admin-shell">
      <section class="hero-card">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-4">
          <div>
            <span class="hero-pill"><i class="bi bi-building-check"></i> Hotel Operations Console</span>
            <h1 class="hero-title">Welcome back, Aditi. The Grand Palace is running smoothly for today’s arrivals.</h1>
            <p class="hero-sub">Keep room readiness, guest stay quality, and revenue momentum on track from a refined property operations dashboard.</p>
            <div class="d-flex flex-wrap gap-2 mt-3">
              <a href="admin-bookings.php" class="ds-btn prim"><i class="bi bi-calendar2-check-fill"></i> View Today’s Arrivals</a>
              <a href="admin-rooms.php" class="ds-btn outl"><i class="bi bi-door-open-fill"></i> Open Room Board</a>
            </div>
          </div>
          <div class="hero-metrics">
            <div class="hero-metric"><span>Occupancy</span><strong>64%</strong></div>
            <div class="hero-metric"><span>Check-ins</span><strong>6 today</strong></div>
            <div class="hero-metric"><span>Service</span><strong>On track</strong></div>
          </div>
        </div>
      </section>

      <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3"><div class="ds-stat blue"><div class="ds-si"><i class="bi bi-building-fill"></i></div><div class="ds-sn"><?php echo $total_hotels; ?></div><div class="ds-sl">Total Hotels</div><div class="ds-tr up"><i class="bi bi-arrow-up-short"></i><?php echo $active_hotels; ?> active</div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="ds-stat green"><div class="ds-si"><i class="bi bi-person-check-fill"></i></div><div class="ds-sn"><?php echo $user_count; ?></div><div class="ds-sl">Active Users</div><div class="ds-tr up"><i class="bi bi-arrow-up-short"></i>Registered accounts</div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="ds-stat gold"><div class="ds-si"><i class="bi bi-star-fill"></i></div><div class="ds-sn"><?php echo $featured_hotels; ?></div><div class="ds-sl">Featured Hotels</div><div class="ds-tr up"><i class="bi bi-arrow-up-short"></i>Shown on homepage</div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="ds-stat purple"><div class="ds-si"><i class="bi bi-geo-alt-fill"></i></div><div class="ds-sn"><?php echo $cities_count; ?></div><div class="ds-sl">Cities Covered</div><div class="ds-tr up"><i class="bi bi-arrow-up-short"></i>Across India</div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="ds-stat gold"><div class="ds-si"><i class="bi bi-calendar2-check-fill"></i></div><div class="ds-sn"><?php echo $booking_count; ?></div><div class="ds-sl">Total Bookings</div><div class="ds-tr up"><i class="bi bi-arrow-up-short"></i>All time</div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="ds-stat red"><div class="ds-si"><i class="bi bi-bell-fill"></i></div><div class="ds-sn">8</div><div class="ds-sl">Pending Requests</div><div class="ds-tr down"><i class="bi bi-arrow-down-short"></i>2 urgent</div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="ds-stat orange"><div class="ds-si"><i class="bi bi-percent"></i></div><div class="ds-sn"><?php echo $total_hotels > 0 ? round($active_hotels/$total_hotels*100).'%' : '0%'; ?></div><div class="ds-sl">Availability Rate</div><div class="ds-tr up"><i class="bi bi-arrow-up-short"></i>Active hotels</div></div></div>
        <div class="col-12 col-sm-6 col-xl-3"><div class="ds-stat blue"><div class="ds-si"><i class="bi bi-star-fill"></i></div><div class="ds-sn">5</div><div class="ds-sl">Review Alerts</div><div class="ds-tr down"><i class="bi bi-arrow-down-short"></i>Reply needed</div></div></div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-12 col-xl-8">
          <div class="ds-card">
            <div class="ds-ch">
              <div class="ds-ct"><i class="bi bi-graph-up-arrow"></i> Occupancy Trend</div>
              <span class="ds-badge confirmed"><i class="bi bi-fire"></i> Strong week</span>
            </div>
            <div class="ds-cb"><div class="ds-chart"><canvas id="revChart" height="260"></canvas></div></div>
          </div>
        </div>
        <div class="col-12 col-xl-4">
          <div class="ds-card h-100">
            <div class="ds-ch"><div class="ds-ct"><i class="bi bi-broadcast"></i> Today’s Priority</div></div>
            <div class="ds-cb">
              <div class="activity-item"><div class="activity-icon blue"><i class="bi bi-door-open-fill"></i></div><div><div class="activity-title">Room housekeeping refresh</div><div class="activity-sub">4 rooms need turnaround before 3 PM</div></div></div>
              <div class="activity-item"><div class="activity-icon green"><i class="bi bi-person-check-fill"></i></div><div><div class="activity-title">Early arrivals</div><div class="activity-sub">2 VIP guests need welcome setup</div></div></div>
              <div class="activity-item"><div class="activity-icon gold"><i class="bi bi-chat-dots"></i></div><div><div class="activity-title">Guest review follow-up</div><div class="activity-sub">A recent stay needs a response</div></div></div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-12 col-lg-7">
          <div class="ds-card">
            <div class="ds-ch">
              <div class="ds-ct"><i class="bi bi-grid-3x3-gap-fill"></i> Hotel Operations Modules</div>
            </div>
            <div class="ds-cb">
              <div class="row g-3">
                <div class="col-12 col-sm-6 col-xl-4"><a href="admin-rooms.php" class="module-card"><i class="bi bi-door-open-fill"></i><strong>Rooms</strong><span>Manage room inventory, availability, and maintenance.</span></a></div>
                <div class="col-12 col-sm-6 col-xl-4"><a href="admin-bookings.php" class="module-card"><i class="bi bi-calendar2-check-fill"></i><strong>Bookings</strong><span>Track stays, arrivals, departures, and confirmations.</span></a></div>
                <div class="col-12 col-sm-6 col-xl-4"><a href="admin-guests.php" class="module-card"><i class="bi bi-people-fill"></i><strong>Guests</strong><span>Monitor in-house guests and returning visitor profiles.</span></a></div>
                <div class="col-12 col-sm-6 col-xl-4"><a href="admin-reviews.php" class="module-card"><i class="bi bi-star-fill"></i><strong>Reviews</strong><span>Reply to guest feedback and protect brand experience.</span></a></div>
                <div class="col-12 col-sm-6 col-xl-4"><a href="admin-revenue.php" class="module-card"><i class="bi bi-bar-chart-fill"></i><strong>Revenue</strong><span>Review daily earnings, occupancy, and room performance.</span></a></div>
                <div class="col-12 col-sm-6 col-xl-4"><a href="notifications.php" class="module-card"><i class="bi bi-bell-fill"></i><strong>Notifications</strong><span>Stay on top of service alerts, requests, and updates.</span></a></div>
                <div class="col-12 col-sm-6 col-xl-4"><a href="admin-hotel-profile.php" class="module-card"><i class="bi bi-building"></i><strong>Hotel Management</strong><span>Add, edit, delete hotels. All changes reflect on user site instantly.</span></a></div>
                <div class="col-12 col-sm-6 col-xl-4"><a href="admin-settings.php" class="module-card"><i class="bi bi-sliders"></i><strong>Settings</strong><span>Adjust hotel preferences, policies, and team access.</span></a></div>
                <div class="col-12 col-sm-6 col-xl-4"><a href="admin-availability.php" class="module-card"><i class="bi bi-calendar-range-fill"></i><strong>Availability</strong><span>Manage room availability and block dates.</span></a></div>
                <div class="col-12 col-sm-6 col-xl-4"><a href="admin-pricing.php" class="module-card"><i class="bi bi-tags-fill"></i><strong>Pricing</strong><span>Set base rates, weekend, and seasonal pricing.</span></a></div>
                <div class="col-12 col-sm-6 col-xl-4"><a href="admin-discounts.php" class="module-card"><i class="bi bi-percent"></i><strong>Discounts</strong><span>Create offers, promo codes, and special rates.</span></a></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-lg-5">
          <div class="ds-card">
            <div class="ds-ch"><div class="ds-ct"><i class="bi bi-clock-history"></i> Service Snapshot</div></div>
            <div class="ds-cb">
              <div class="health-row"><span>Housekeeping</span><strong>On schedule</strong></div>
              <div class="health-row"><span>Check-in readiness</span><strong>92%</strong></div>
              <div class="health-row"><span>Guest requests</span><strong>8 open</strong></div>
              <div class="ds-prog mt-3"><div class="ds-progf" style="width:92%;background:linear-gradient(90deg,var(--grn),#2dd4bf)"></div></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js" crossorigin="anonymous"></script>
  <script src="dashboard.js"></script>
  <script>renderRevenue('revChart');renderOcc('occChart');</script>
</body>
</html>

