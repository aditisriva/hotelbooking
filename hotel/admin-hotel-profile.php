<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>Hotel Profile -- Hotel Manager</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="dashboard.css"/>
</head><body><div class="ds-ov" id="dsOv"></div>
<aside class="ds-sb" id="dsSb">
  <a href="admin-dashboard.php" class="ds-logo"><div class="ds-logo-icon"><i class="bi bi-building-fill"></i></div><div><div class="ds-logo-name">bookHotel</div><div class="ds-logo-role">Manager Portal</div></div></a>
  <nav class="ds-nav">
    <div class="ds-sec">Main</div>
    <a href="admin-dashboard.php" class="ds-link"><i class="bi bi-grid-fill"></i> Dashboard</a>
    <a href="admin-hotel-profile.php" class="ds-link active"><i class="bi bi-building"></i> Hotel Profile</a>
    <div class="ds-sec">Operations</div>
    <a href="admin-rooms.php" class="ds-link "><i class="bi bi-door-open-fill"></i> Rooms</a>
    <a href="admin-bookings.php" class="ds-link "><i class="bi bi-calendar2-check-fill"></i> Bookings <span class="badge bg-danger">3</span></a>
    <a href="admin-guests.php" class="ds-link "><i class="bi bi-people-fill"></i> Guests</a>
    <div class="ds-sec">Insights</div>
    <a href="admin-reviews.php" class="ds-link "><i class="bi bi-star-fill"></i> Reviews <span class="badge bg-warning text-dark">5</span></a>
    <a href="admin-revenue.php" class="ds-link "><i class="bi bi-bar-chart-fill"></i> Revenue</a>
    <a href="admin-notifications.php" class="ds-link "><i class="bi bi-bell-fill"></i> Notifications <span class="badge bg-primary">8</span></a>
    <div class="ds-sec">Account</div>
    <a href="admin-settings.php" class="ds-link "><i class="bi bi-gear-fill"></i> Settings</a>
    <a href="index.php" class="ds-link"><i class="bi bi-box-arrow-left"></i> Back to Website</a>
  </nav>
  <div class="ds-foot"><a href="admin-hotel-profile.php" class="ds-hpill"><img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=80&q=80" alt=""/><div><div class="ds-hpill-name">The Grand Palace</div><div class="ds-hpill-status">&#9679; Active &middot; Mumbai</div></div></a></div>
</aside><header class="ds-top">
  <div class="ds-top-l">
    <button class="ds-ibtn d-lg-none" id="dsTog"><i class="bi bi-list fs-5"></i></button>
    <div><div class="ds-page-title">Hotel Profile</div><div class="ds-breadcrumb">Dashboard / Hotel Profile</div></div>
  </div>
  <div class="ds-top-r">
    <a href="admin-notifications.php" class="ds-ibtn"><i class="bi bi-bell-fill"></i><span class="ds-dot"></span></a>
    <div class="ds-avbtn" id="dsAvBtn"><div class="ds-av">AD</div><span class="ds-avname d-none d-sm-block">Aditi</span>
      <div class="ds-dropdown" id="dsAvMenu">
        <a href="admin-settings.php" class="ds-drop-item"><i class="bi bi-person-fill text-primary"></i> My Profile</a>
        <hr class="my-1 mx-2"/><a href="login.php" class="ds-drop-item danger"><i class="bi bi-box-arrow-right"></i> Sign Out</a>
      </div>
    </div>
  </div>
</header><main class="ds-main"><div class="row g-4">
  <div class="col-12 col-xl-8">
    <div class="ds-card">
      <div class="ds-ch"><div class="ds-ct"><i class="bi bi-building-fill"></i> Hotel Information</div><button class="ds-btn prim" data-bs-toggle="modal" data-bs-target="#editHotelModal"><i class="bi bi-pencil-fill"></i> Edit Hotel</button></div>
      <div class="ds-cb">
        <div class="row g-4 align-items-start">
          <div class="col-md-4"><img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400&q=80" style="width:100%;border-radius:12px;object-fit:cover;height:200px" alt=""/><div class="mt-2 d-flex gap-2"><span class="ds-badge confirmed">Active</span><span class="ds-badge checkin">5 Star</span></div></div>
          <div class="col-md-8"><h4 style="font-weight:800;color:var(--txt)">The Grand Palace</h4><p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill text-danger me-1"></i>Marine Drive, Mumbai, Maharashtra 400020</p><p style="font-size:.875rem;color:var(--txt2);line-height:1.7;margin-bottom:1rem">Iconic luxury hotel overlooking the Arabian Sea with world-class dining, premium spa, and state-of-the-art conference facilities.</p>
          <div class="row g-3"><div class="col-6"><div class="ds-lbl">Phone</div><div style="font-size:.875rem;font-weight:600">+91 9876543210</div></div><div class="col-6"><div class="ds-lbl">Email</div><div style="font-size:.875rem;font-weight:600">info@grandpalace.com</div></div><div class="col-6"><div class="ds-lbl">Check-in</div><div style="font-size:.875rem;font-weight:600">2:00 PM</div></div><div class="col-6"><div class="ds-lbl">Check-out</div><div style="font-size:.875rem;font-weight:600">11:00 AM</div></div></div></div>
        </div>
      </div>
    </div>
    <div class="ds-card mt-4"><div class="ds-ch"><div class="ds-ct"><i class="bi bi-stars"></i> Amenities</div><button class="ds-btn outl sm" onclick="dsToast('Amenity added!','success')"><i class="bi bi-plus-lg"></i> Add</button></div><div class="ds-cb"><div class="d-flex flex-wrap gap-2"><span class="am-chip"><i class="bi bi-wifi"></i> Free WiFi</span><span class="am-chip"><i class="bi bi-droplet-fill"></i> Pool</span><span class="am-chip"><i class="bi bi-cup-hot-fill"></i> Breakfast</span><span class="am-chip"><i class="bi bi-car-front-fill"></i> Parking</span><span class="am-chip"><i class="bi bi-flower1"></i> Spa</span><span class="am-chip"><i class="bi bi-dumbbell"></i> Gym</span><span class="am-chip"><i class="bi bi-fan"></i> AC</span><span class="am-chip"><i class="bi bi-cup-straw"></i> Restaurant</span></div></div></div>
    <div class="ds-card mt-4"><div class="ds-ch"><div class="ds-ct"><i class="bi bi-images"></i> Gallery</div><button class="ds-btn prim sm" onclick="dsToast('Upload feature ready!','info')"><i class="bi bi-upload"></i> Upload</button></div><div class="ds-cb"><div class="row g-3">
      <div class="col-6 col-sm-4 col-xl-2"><img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=300&q=80" class="gal-img" alt=""/></div>
      <div class="col-6 col-sm-4 col-xl-2"><img src="https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=300&q=80" class="gal-img" alt=""/></div>
      <div class="col-6 col-sm-4 col-xl-2"><img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=300&q=80" class="gal-img" alt=""/></div>
      <div class="col-6 col-sm-4 col-xl-2"><img src="https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=300&q=80" class="gal-img" alt=""/></div>
      <div class="col-6 col-sm-4 col-xl-2"><img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=300&q=80" class="gal-img" alt=""/></div>
      <div class="col-6 col-sm-4 col-xl-2"><div class="gal-img d-flex align-items-center justify-content-center flex-column gap-2" style="background:var(--srf);border:2px dashed var(--bdr);cursor:pointer" onclick="dsToast('Add photo','info')"><i class="bi bi-plus-circle" style="font-size:2rem;color:var(--mut)"></i><span style="font-size:.75rem;color:var(--mut)">Add Photo</span></div></div>
    </div></div></div>
  </div>
  <div class="col-12 col-xl-4">
    <div class="ds-card"><div class="ds-ch"><div class="ds-ct"><i class="bi bi-speedometer2"></i> Quick Stats</div></div><div class="ds-cb"><div class="d-flex flex-column gap-3">
      <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background:var(--pr-lt)"><div><div style="font-size:.78rem;font-weight:700;color:var(--pr);text-transform:uppercase">Total Rooms</div><div style="font-size:1.5rem;font-weight:800;color:var(--pr)">30</div></div><i class="bi bi-door-open-fill" style="font-size:2rem;color:rgba(26,86,219,.3)"></i></div>
      <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background:var(--grn-lt)"><div><div style="font-size:.78rem;font-weight:700;color:var(--grn);text-transform:uppercase">Avg. Rating</div><div style="font-size:1.5rem;font-weight:800;color:var(--grn)">4.8 ?</div></div><i class="bi bi-star-fill" style="font-size:2rem;color:rgba(5,150,105,.3)"></i></div>
      <div class="d-flex justify-content-between align-items-center p-3 rounded-3" style="background:#fef3c7"><div><div style="font-size:.78rem;font-weight:700;color:#d97706;text-transform:uppercase">Total Reviews</div><div style="font-size:1.5rem;font-weight:800;color:#d97706">1,248</div></div><i class="bi bi-chat-fill" style="font-size:2rem;color:rgba(245,158,11,.3)"></i></div>
    </div></div></div>
  </div>
</div>
<!-- Edit Modal -->
<div class="modal fade ds-modal" id="editHotelModal" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered"><div class="modal-content"><div class="modal-header"><h5 class="modal-title"><i class="bi bi-pencil-fill me-2"></i>Edit Hotel</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body p-4"><div class="row g-3"><div class="col-md-6"><label class="ds-lbl">Hotel Name</label><input class="ds-inp" value="The Grand Palace"/></div><div class="col-md-6"><label class="ds-lbl">Star Rating</label><select class="ds-inp ds-sel"><option>5 Star</option><option>4 Star</option></select></div><div class="col-12"><label class="ds-lbl">Address</label><input class="ds-inp" value="Marine Drive, Mumbai 400020"/></div><div class="col-md-6"><label class="ds-lbl">Phone</label><input class="ds-inp" value="+91 9876543210"/></div><div class="col-md-6"><label class="ds-lbl">Email</label><input class="ds-inp" value="info@grandpalace.com"/></div><div class="col-md-6"><label class="ds-lbl">Check-in Time</label><input type="time" class="ds-inp" value="14:00"/></div><div class="col-md-6"><label class="ds-lbl">Check-out Time</label><input type="time" class="ds-inp" value="11:00"/></div><div class="col-12"><label class="ds-lbl">Description</label><textarea class="ds-inp" rows="4">Iconic luxury hotel overlooking the Arabian Sea...</textarea></div></div></div><div class="modal-footer"><button class="ds-btn gho" data-bs-dismiss="modal">Cancel</button><button class="ds-btn prim" onclick="saveHotelProfile()" data-bs-dismiss="modal"><i class="bi bi-check-lg"></i> Save</button></div></div></div></div></main><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js" crossorigin="anonymous"></script>
<script src="dashboard.js"></script>
<script>
function saveHotelProfile() {
  const inputs = document.querySelectorAll('#editHotelModal .ds-inp');
  document.querySelector('h4').textContent = inputs[0].value;
  document.querySelector('.text-muted.small.mb-3').innerHTML = '<i class="bi bi-geo-alt-fill text-danger me-1"></i>' + inputs[2].value;
  document.querySelectorAll('.col-6 > div:nth-child(2)')[0].textContent = inputs[3].value; // phone
  document.querySelectorAll('.col-6 > div:nth-child(2)')[1].textContent = inputs[4].value; // email
  document.querySelectorAll('.col-6 > div:nth-child(2)')[2].textContent = inputs[5].value; // check-in
  document.querySelectorAll('.col-6 > div:nth-child(2)')[3].textContent = inputs[6].value; // check-out
  document.querySelector('p[style*="line-height:1.7"]').textContent = inputs[7].value;
  dsToast('Hotel profile updated successfully!','success');
}
</script>
</body></html>
