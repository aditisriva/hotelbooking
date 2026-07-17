<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1.0"/><title>Room Management -- Hotel Manager</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"/><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous"/><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/><link rel="stylesheet" href="dashboard.css"/></head><body><div class="ds-ov" id="dsOv"></div><aside class="ds-sb" id="dsSb"><a href="admin-dashboard.php" class="ds-logo"><div class="ds-logo-icon"><i class="bi bi-building-fill"></i></div><div><div class="ds-logo-name">bookHotel</div><div class="ds-logo-role">Manager Portal</div></div></a><nav class="ds-nav" id="mainSidebar">
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
    <script>document.addEventListener("DOMContentLoaded",()=>{let c=location.pathname.split("/").pop()||"admin-dashboard.php";document.querySelectorAll("#mainSidebar a").forEach(l=>{l.getAttribute("href")===c?l.classList.add("active"):l.classList.remove("active")})});</script><div class="ds-foot"><a href="admin-hotel-profile.php" class="ds-hpill"><img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=80&q=80" alt=""/><div><div class="ds-hpill-name">The Grand Palace</div><div class="ds-hpill-status">Active</div></div></a></div></aside><header class="ds-top"><div class="ds-top-l"><button class="ds-ibtn d-lg-none" id="dsTog"><i class="bi bi-list fs-5"></i></button><div><div class="ds-page-title">Room Management</div><div class="ds-breadcrumb">Dashboard / Room Management</div></div></div><div class="ds-top-r"><a href="admin-notifications.php" class="ds-ibtn"><i class="bi bi-bell-fill"></i><span class="ds-dot"></span></a><div class="ds-avbtn" id="dsAvBtn"><div class="ds-av">AD</div><span class="ds-avname d-none d-sm-block">Aditi</span><div class="ds-dropdown" id="dsAvMenu"><a href="admin-settings.php" class="ds-drop-item"><i class="bi bi-person-fill text-primary"></i> My Profile</a><hr class="my-1 mx-2"/><a href="login.php" class="ds-drop-item danger"><i class="bi bi-box-arrow-right"></i> Sign Out</a></div></div></div></header><main class="ds-main">
  <!-- Stat Cards -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3"><div class="ds-stat blue"><div class="ds-si"><i class="bi bi-door-open-fill"></i></div><div class="ds-sn">30</div><div class="ds-sl">Total Rooms</div></div></div>
    <div class="col-6 col-md-3"><div class="ds-stat green"><div class="ds-si"><i class="bi bi-check-circle-fill"></i></div><div class="ds-sn">9</div><div class="ds-sl">Available</div></div></div>
    <div class="col-6 col-md-3"><div class="ds-stat red"><div class="ds-si"><i class="bi bi-person-fill"></i></div><div class="ds-sn">18</div><div class="ds-sl">Occupied</div></div></div>
    <div class="col-6 col-md-3"><div class="ds-stat gold"><div class="ds-si"><i class="bi bi-tools"></i></div><div class="ds-sn">3</div><div class="ds-sl">Maintenance</div></div></div>
  </div>

  <!-- Rooms Table -->
  <div class="ds-card">
    <div class="ds-ch">
      <div class="ds-ct"><i class="bi bi-door-open-fill"></i> All Rooms</div>
      <div class="d-flex gap-2 flex-wrap">
        <div class="ds-sw"><i class="bi bi-search ds-si-ic"></i><input class="ds-inp search" placeholder="Search rooms…" style="width:200px" oninput="filterRooms(this.value)"/></div>
        <select class="ds-inp ds-sel" style="width:auto" onchange="filterRoomType(this.value)">
          <option value="">All Types</option><option>Standard</option><option>Deluxe</option><option>Suite</option><option>Presidential</option>
        </select>
        <button class="ds-btn prim" data-bs-toggle="modal" data-bs-target="#addRoomModal"><i class="bi bi-plus-lg"></i> Add Room</button>
      </div>
    </div>
    <div style="overflow-x:auto">
      <table class="ds-tbl" id="roomTable">
        <thead><tr><th>Room No.</th><th>Preview</th><th>Type</th><th>Floor</th><th>Capacity</th><th>Price/Night</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          <tr data-type="Standard">
            <td class="fw-700">101</td>
            <td><img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=80&q=80" style="width:60px;height:40px;object-fit:cover;border-radius:6px" alt=""/></td>
            <td>Standard Twin</td><td>1st</td><td>2 Adults</td>
            <td class="fw-700 text-primary">₹3,500</td>
            <td><span class="ds-badge available">Available</span></td>
            <td><div class="d-flex gap-1">
              <button class="ds-btn gho ico" data-bs-toggle="modal" data-bs-target="#editRoomModal" title="Edit"><i class="bi bi-pencil-fill"></i></button>
              <button class="ds-btn dng ico" onclick="dsConfirm('Delete Room 101?',()=>{this.closest('tr').remove();dsToast('Room deleted','error')})" title="Delete"><i class="bi bi-trash-fill"></i></button>
            </div></td>
          </tr>
          <tr data-type="Deluxe">
            <td class="fw-700">201</td>
            <td><img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=80&q=80" style="width:60px;height:40px;object-fit:cover;border-radius:6px" alt=""/></td>
            <td>Deluxe King</td><td>2nd</td><td>2 Adults</td>
            <td class="fw-700 text-primary">₹5,500</td>
            <td><span class="ds-badge occupied">Occupied</span></td>
            <td><div class="d-flex gap-1">
              <button class="ds-btn gho ico" data-bs-toggle="modal" data-bs-target="#editRoomModal"><i class="bi bi-pencil-fill"></i></button>
              <button class="ds-btn dng ico" onclick="dsConfirm('Delete Room 201?',()=>{this.closest('tr').remove();dsToast('Room deleted','error')})"><i class="bi bi-trash-fill"></i></button>
            </div></td>
          </tr>
          <tr data-type="Suite">
            <td class="fw-700">301</td>
            <td><img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=80&q=80" style="width:60px;height:40px;object-fit:cover;border-radius:6px" alt=""/></td>
            <td>Ocean Suite</td><td>3rd</td><td>2 Adults, 1 Child</td>
            <td class="fw-700 text-primary">₹9,000</td>
            <td><span class="ds-badge occupied">Occupied</span></td>
            <td><div class="d-flex gap-1">
              <button class="ds-btn gho ico" data-bs-toggle="modal" data-bs-target="#editRoomModal"><i class="bi bi-pencil-fill"></i></button>
              <button class="ds-btn dng ico" onclick="dsConfirm('Delete?',()=>{this.closest('tr').remove();dsToast('Deleted','error')})"><i class="bi bi-trash-fill"></i></button>
            </div></td>
          </tr>
          <tr data-type="Presidential">
            <td class="fw-700">401</td>
            <td><img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=80&q=80" style="width:60px;height:40px;object-fit:cover;border-radius:6px" alt=""/></td>
            <td>Presidential Suite</td><td>4th</td><td>4 Adults</td>
            <td class="fw-700 text-primary">₹24,000</td>
            <td><span class="ds-badge available">Available</span></td>
            <td><div class="d-flex gap-1">
              <button class="ds-btn gho ico" data-bs-toggle="modal" data-bs-target="#editRoomModal"><i class="bi bi-pencil-fill"></i></button>
              <button class="ds-btn dng ico" onclick="dsConfirm('Delete?',()=>{this.closest('tr').remove();dsToast('Deleted','error')})"><i class="bi bi-trash-fill"></i></button>
            </div></td>
          </tr>
          <tr data-type="Standard">
            <td class="fw-700">102</td>
            <td><img src="https://images.unsplash.com/photo-1582582621959-48d27397dc69?w=80&q=80" style="width:60px;height:40px;object-fit:cover;border-radius:6px" alt=""/></td>
            <td>Standard Single</td><td>1st</td><td>1 Adult</td>
            <td class="fw-700 text-primary">₹2,500</td>
            <td><span class="ds-badge maintenance">Maintenance</span></td>
            <td><div class="d-flex gap-1">
              <button class="ds-btn gho ico" data-bs-toggle="modal" data-bs-target="#editRoomModal"><i class="bi bi-pencil-fill"></i></button>
              <button class="ds-btn dng ico" onclick="dsConfirm('Delete?',()=>{this.closest('tr').remove();dsToast('Deleted','error')})"><i class="bi bi-trash-fill"></i></button>
            </div></td>
          </tr>
          <tr data-type="Deluxe">
            <td class="fw-700">202</td>
            <td><img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=80&q=80" style="width:60px;height:40px;object-fit:cover;border-radius:6px" alt=""/></td>
            <td>Deluxe Twin</td><td>2nd</td><td>2 Adults</td>
            <td class="fw-700 text-primary">₹5,200</td>
            <td><span class="ds-badge occupied">Occupied</span></td>
            <td><div class="d-flex gap-1">
              <button class="ds-btn gho ico" data-bs-toggle="modal" data-bs-target="#editRoomModal"><i class="bi bi-pencil-fill"></i></button>
              <button class="ds-btn dng ico" onclick="dsConfirm('Delete?',()=>{this.closest('tr').remove();dsToast('Deleted','error')})"><i class="bi bi-trash-fill"></i></button>
            </div></td>
          </tr>
          <tr data-type="Suite">
            <td class="fw-700">302</td>
            <td><img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=80&q=80" style="width:60px;height:40px;object-fit:cover;border-radius:6px" alt=""/></td>
            <td>Deluxe Suite</td><td>3rd</td><td>3 Adults</td>
            <td class="fw-700 text-primary">₹11,000</td>
            <td><span class="ds-badge available">Available</span></td>
            <td><div class="d-flex gap-1">
              <button class="ds-btn gho ico" data-bs-toggle="modal" data-bs-target="#editRoomModal"><i class="bi bi-pencil-fill"></i></button>
              <button class="ds-btn dng ico" onclick="dsConfirm('Delete?',()=>{this.closest('tr').remove();dsToast('Deleted','error')})"><i class="bi bi-trash-fill"></i></button>
            </div></td>
          </tr>
          <tr data-type="Standard">
            <td class="fw-700">103</td>
            <td><img src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=80&q=80" style="width:60px;height:40px;object-fit:cover;border-radius:6px" alt=""/></td>
            <td>Standard Twin</td><td>1st</td><td>2 Adults</td>
            <td class="fw-700 text-primary">₹3,500</td>
            <td><span class="ds-badge occupied">Occupied</span></td>
            <td><div class="d-flex gap-1">
              <button class="ds-btn gho ico" data-bs-toggle="modal" data-bs-target="#editRoomModal"><i class="bi bi-pencil-fill"></i></button>
              <button class="ds-btn dng ico" onclick="dsConfirm('Delete?',()=>{this.closest('tr').remove();dsToast('Deleted','error')})"><i class="bi bi-trash-fill"></i></button>
            </div></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="d-flex justify-content-between align-items-center p-3 border-top" style="font-size:.82rem;color:var(--mut)">
      <span>Showing 8 of 30 rooms</span>
      <div class="d-flex gap-1">
        <button class="ds-btn gho sm"><i class="bi bi-chevron-left"></i></button>
        <button class="ds-btn prim sm">1</button>
        <button class="ds-btn gho sm">2</button>
        <button class="ds-btn gho sm">3</button>
        <button class="ds-btn gho sm"><i class="bi bi-chevron-right"></i></button>
      </div>
    </div>
  </div>
</main>

<!-- Add Room Modal -->
<div class="modal fade ds-modal" id="addRoomModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title"><i class="bi bi-plus-circle-fill me-2"></i>Add New Room</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <div class="modal-body p-4">
        <div class="row g-3">
          <div class="col-md-4"><label class="ds-lbl">Room Number</label><input class="ds-inp" placeholder="e.g. 205"/></div>
          <div class="col-md-4"><label class="ds-lbl">Room Type</label>
            <select class="ds-inp ds-sel">
              <option>Standard Single</option><option>Standard Twin</option><option>Deluxe King</option><option>Deluxe Twin</option><option>Ocean Suite</option><option>Deluxe Suite</option><option>Presidential Suite</option>
            </select>
          </div>
          <div class="col-md-4"><label class="ds-lbl">Floor</label><input class="ds-inp" placeholder="e.g. 2nd"/></div>
          <div class="col-md-4"><label class="ds-lbl">Price / Night (₹)</label><input type="number" class="ds-inp" placeholder="5500"/></div>
          <div class="col-md-4"><label class="ds-lbl">Capacity</label><input class="ds-inp" placeholder="e.g. 2 Adults"/></div>
          <div class="col-md-4"><label class="ds-lbl">Status</label>
            <select class="ds-inp ds-sel"><option>Available</option><option>Maintenance</option></select>
          </div>
          <div class="col-12"><label class="ds-lbl">Amenities</label><input class="ds-inp" placeholder="WiFi, AC, TV, Mini-bar, Safe…"/></div>
          <div class="col-12"><label class="ds-lbl">Description</label><textarea class="ds-inp" rows="3" placeholder="Describe the room…"></textarea></div>
          <div class="col-12"><label class="ds-lbl">Room Image URL</label><input class="ds-inp" placeholder="https://…"/></div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="ds-btn gho" data-bs-dismiss="modal">Cancel</button>
        <button class="ds-btn prim" onclick="dsToast('Room added successfully!','success')" data-bs-dismiss="modal"><i class="bi bi-plus-lg"></i> Add Room</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Room Modal -->
<div class="modal fade ds-modal" id="editRoomModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title"><i class="bi bi-pencil-fill me-2"></i>Edit Room</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <div class="modal-body p-4">
        <div class="row g-3">
          <div class="col-md-4"><label class="ds-lbl">Room Number</label><input class="ds-inp" value="201"/></div>
          <div class="col-md-4"><label class="ds-lbl">Room Type</label>
            <select class="ds-inp ds-sel"><option selected>Deluxe King</option><option>Standard Single</option><option>Standard Twin</option><option>Suite</option><option>Presidential Suite</option></select>
          </div>
          <div class="col-md-4"><label class="ds-lbl">Floor</label><input class="ds-inp" value="2nd"/></div>
          <div class="col-md-4"><label class="ds-lbl">Price / Night (₹)</label><input type="number" class="ds-inp" value="5500"/></div>
          <div class="col-md-4"><label class="ds-lbl">Capacity</label><input class="ds-inp" value="2 Adults"/></div>
          <div class="col-md-4"><label class="ds-lbl">Status</label>
            <select class="ds-inp ds-sel"><option>Available</option><option selected>Occupied</option><option>Maintenance</option></select>
          </div>
          <div class="col-12"><label class="ds-lbl">Amenities</label><input class="ds-inp" value="WiFi, AC, 55&quot; TV, Mini-bar, Safe, King Bed"/></div>
          <div class="col-12"><label class="ds-lbl">Description</label><textarea class="ds-inp" rows="3">Spacious king-bed room with city view, premium bath amenities and 24-hour room service.</textarea></div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="ds-btn gho" data-bs-dismiss="modal">Cancel</button>
        <button class="ds-btn prim" onclick="dsToast('Room updated!','success')" data-bs-dismiss="modal"><i class="bi bi-check-lg"></i> Save Changes</button>
      </div>
    </div>
  </div>
</div>

<script>
function filterRooms(q){
  q=q.toLowerCase();
  document.querySelectorAll('#roomTable tbody tr').forEach(r=>{r.style.display=r.textContent.toLowerCase().includes(q)?'':'none'});
}
function filterRoomType(t){
  document.querySelectorAll('#roomTable tbody tr').forEach(r=>{r.style.display=(!t||r.dataset.type===t)?'':'none'});
}
</script><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script><script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js" crossorigin="anonymous"></script><script src="dashboard.js"></script></body></html>

