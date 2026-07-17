<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Hotel Listing | Hotel Operations</title>
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
        <div class="ds-page-title">Manage Hotel Listing</div>
        <div class="ds-breadcrumb">Submit and track your property approval status</div>
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
      <div class="row g-4">
        <div class="col-12 col-md-8">
          <div class="ds-card mb-4">
            <div class="ds-ch"><div class="ds-ct"><i class="bi bi-building"></i> Hotel Information Form</div></div>
            <div class="ds-cb">
              <form>
                <div class="row g-3">
                  <div class="col-md-12">
                    <label class="form-label fw-600 small">Hotel Name</label>
                    <input type="text" class="ds-inp" value="The Grand Palace" />
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-600 small">City</label>
                    <input type="text" class="ds-inp" value="Mumbai" />
                  </div>
                  <div class="col-md-6">
                    <label class="form-label fw-600 small">State</label>
                    <input type="text" class="ds-inp" value="Maharashtra" />
                  </div>
                  <div class="col-md-12">
                    <label class="form-label fw-600 small">Full Address</label>
                    <textarea class="ds-inp" rows="2">Marine Drive, Mumbai</textarea>
                  </div>
                  <div class="col-md-12">
                    <label class="form-label fw-600 small">Description</label>
                    <textarea class="ds-inp" rows="4">Iconic luxury hotel overlooking the Arabian Sea with world-class dining and premium spa facilities.</textarea>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <div class="ds-card">
            <div class="ds-ch"><div class="ds-ct"><i class="bi bi-images"></i> Hotel Image Gallery</div></div>
            <div class="ds-cb">
              <div class="d-flex gap-3 overflow-auto pb-2">
                <div class="position-relative" style="min-width: 150px;">
                  <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=200&q=80" alt="Hotel" class="img-fluid rounded" style="height: 100px; width: 150px; object-fit: cover;">
                  <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" style="border-radius: 50%; padding: 0.1rem 0.3rem;"><i class="bi bi-x"></i></button>
                </div>
                <div class="d-flex align-items-center justify-content-center bg-light rounded border border-dashed" style="min-width: 150px; height: 100px; cursor: pointer;">
                  <div class="text-center text-muted">
                    <i class="bi bi-plus-circle fs-4"></i>
                    <div class="small mt-1">Add Image</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-4">
           <div class="ds-card mb-4 border-success" style="border-width: 2px;">
              <div class="ds-ch bg-success bg-opacity-10 border-bottom-0"><div class="ds-ct text-success"><i class="bi bi-patch-check-fill"></i> Listing Status</div></div>
              <div class="ds-cb text-center py-4">
                 <h4 class="fw-800 text-success mb-2">Approved</h4>
                 <p class="text-muted small mb-4">Your listing is live on the BookHotel platform and visible to all customers.</p>
                 <button class="ds-btn prim w-100"><i class="bi bi-send-fill"></i> Submit for Approval</button>
                 <div class="small text-muted mt-2">Submit again only if you've made major changes.</div>
              </div>
           </div>

           <div class="ds-card">
              <div class="ds-ch"><div class="ds-ct"><i class="bi bi-chat-left-text"></i> Admin Remarks</div></div>
              <div class="ds-cb">
                 <div class="p-3 bg-light rounded border-start border-4 border-primary">
                    <p class="mb-1 small">"Everything looks great! The new professional photos were approved."</p>
                    <small class="text-muted fw-600">- System Admin (12 Jun 2026)</small>
                 </div>
              </div>
           </div>
        </div>
      </div>
    </div>
  </main>
  <script src="dashboard.js"></script>
</body>
</html>

