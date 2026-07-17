<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>On/Off Hotel Bookings | Hotel Operations</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
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
    <nav class="ds-nav" id="mainSidebar">
      <div class="ds-sec">Main</div>
      <a href="admin-dashboard.php" class="ds-link"><i class="bi bi-grid-fill"></i> Dashboard</a>
      <a href="manage-bookings.php" class="ds-link"><i class="bi bi-calendar2-check-fill"></i> Manage Bookings</a>
      <a href="check-in-order.php" class="ds-link"><i class="bi bi-person-check-fill"></i> Check In Order</a>
      <a href="manage-hotels.php" class="ds-link"><i class="bi bi-building"></i> Manage Hotels</a>
      <a href="manage-hotel-listing.php" class="ds-link"><i class="bi bi-card-checklist"></i> Manage Hotel Listing</a>
      <a href="on-off-hotel-bookings.php" class="ds-link"><i class="bi bi-toggle-on"></i> On/Off Hotel Bookings</a>
      <a href="manage-rooms.php" class="ds-link"><i class="bi bi-door-open-fill"></i> Manage Rooms</a>
      <a href="view-ratings.php" class="ds-link"><i class="bi bi-star-fill"></i> View Ratings</a>
      <a href="transaction-history.php" class="ds-link"><i class="bi bi-cash-stack"></i> Transaction History</a>
      <a href="logout.php" class="ds-link"><i class="bi bi-box-arrow-left"></i> Logout</a>
    </nav>
    <script>document.addEventListener("DOMContentLoaded",()=>{let c=location.pathname.split("/").pop()||"admin-dashboard.php";document.querySelectorAll("#mainSidebar a").forEach(l=>{l.getAttribute("href")===c?l.classList.add("active"):l.classList.remove("active")})});</script>
    <div class="ds-foot">
      <a href="#" class="ds-hpill">
        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=120&q=80" alt="Hotel" />
        <div>
          <div class="ds-hpill-name">Hotel Manager</div>
          <div class="ds-hpill-status">● Active</div>
        </div>
      </a>
    </div>
  </aside>

  <header class="ds-top">
    <div class="ds-top-l">
      <button class="ds-ibtn d-lg-none" id="dsTog"><i class="bi bi-list fs-5"></i></button>
      <div>
        <div class="ds-page-title">On/Off Hotel Bookings</div>
        <div class="ds-breadcrumb">Toggle property visibility and booking engine</div>
      </div>
    </div>
    <div class="ds-top-r">
      <div class="ds-avbtn" id="dsAvBtn">
        <div class="ds-av">M</div>
        <span class="ds-avname d-none d-sm-block">Manager</span>
      </div>
    </div>
  </header>

  <main class="ds-main">
    <div class="admin-shell">
      
      <div class="alert alert-info border-0 d-flex align-items-center mb-4" style="border-radius:12px; background-color: #e0f2fe; color: #0284c7;">
        <i class="bi bi-info-circle-fill fs-4 me-3"></i>
        <div>
           <strong>Visibility Info:</strong> Disabling bookings will hide your property from search results on the main website. Existing reservations will not be affected.
        </div>
      </div>

      <div class="ds-card text-center py-5" style="max-width: 600px; margin: 0 auto; border: 2px solid #10b981;">
        <div class="ds-cb d-flex flex-column align-items-center">
           
           <div class="mb-4">
              <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
           </div>
           
           <h3 class="fw-800 mb-2 text-success">Bookings are Enabled</h3>
           <p class="text-muted mb-5 px-3">Your property is currently accepting reservations. Users can view your rooms and book stays directly from the main website.</p>

           <div class="booking-control-section bg-light p-4 rounded-3 w-100 d-flex flex-column align-items-center">
              <div class="form-check form-switch" style="transform: scale(2); margin-bottom: 1.5rem;">
                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked style="cursor: pointer;">
              </div>
              <p class="small text-muted fw-600 mb-0">Toggle the switch to instantly disable or enable all incoming bookings.</p>
           </div>
           
        </div>
      </div>
    </div>
  </main>
  <script src="dashboard.js"></script>
</body>
</html>

