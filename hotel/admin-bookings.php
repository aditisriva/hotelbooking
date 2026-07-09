<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>Booking Management — Hotel Manager</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="dashboard.css"/>
</head><body>
<div class="ds-ov" id="dsOv"></div>
<aside class="ds-sb" id="dsSb">
  <a href="admin-dashboard.php" class="ds-logo"><div class="ds-logo-icon"><i class="bi bi-building-fill"></i></div><div><div class="ds-logo-name">bookHotel</div><div class="ds-logo-role">Manager Portal</div></div></a>
  <nav class="ds-nav">
    <div class="ds-sec">Main</div>
    <a href="admin-dashboard.php" class="ds-link"><i class="bi bi-grid-fill"></i> Dashboard</a>
    <a href="admin-hotel-profile.php" class="ds-link"><i class="bi bi-building"></i> Hotel Profile</a>
    <div class="ds-sec">Operations</div>
    <a href="admin-rooms.php" class="ds-link"><i class="bi bi-door-open-fill"></i> Rooms</a>
    <a href="admin-bookings.php" class="ds-link active"><i class="bi bi-calendar2-check-fill"></i> Bookings <span class="badge bg-danger">3</span></a>
    <a href="admin-guests.php" class="ds-link"><i class="bi bi-people-fill"></i> Guests</a>
    <div class="ds-sec">Insights</div>
    <a href="admin-reviews.php" class="ds-link"><i class="bi bi-star-fill"></i> Reviews <span class="badge bg-warning text-dark">5</span></a>
    <a href="admin-revenue.php" class="ds-link"><i class="bi bi-bar-chart-fill"></i> Revenue</a>
    <a href="admin-notifications.php" class="ds-link"><i class="bi bi-bell-fill"></i> Notifications <span class="badge bg-primary">8</span></a>
    <div class="ds-sec">Account</div>
    <a href="admin-settings.php" class="ds-link"><i class="bi bi-gear-fill"></i> Settings</a>
    <a href="index.php" class="ds-link"><i class="bi bi-box-arrow-left"></i> Back to Website</a>
  </nav>
  <div class="ds-foot"><a href="admin-hotel-profile.php" class="ds-hpill"><img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=80&q=80" alt=""/><div><div class="ds-hpill-name">The Grand Palace</div><div class="ds-hpill-status">● Active · Mumbai</div></div></a></div>
</aside>
<header class="ds-top">
  <div class="ds-top-l">
    <button class="ds-ibtn d-lg-none" id="dsTog"><i class="bi bi-list fs-5"></i></button>
    <div><div class="ds-page-title">Booking Management</div><div class="ds-breadcrumb">Operations &rsaquo; Bookings</div></div>
  </div>
  <div class="ds-top-r">
    <a href="admin-notifications.php" class="ds-ibtn"><i class="bi bi-bell-fill"></i><span class="ds-dot"></span></a>
    <div class="ds-avbtn" id="dsAvBtn"><div class="ds-av">AD</div><span class="ds-avname d-none d-sm-block">Aditi</span><i class="bi bi-chevron-down ms-1" style="font-size:.7rem;color:var(--mut)"></i>
      <div class="ds-dropdown" id="dsAvMenu">
        <a href="admin-settings.php" class="ds-drop-item"><i class="bi bi-person-fill text-primary"></i> My Profile</a>
        <a href="admin-settings.php" class="ds-drop-item"><i class="bi bi-gear-fill text-primary"></i> Settings</a>
        <hr class="my-1 mx-2"/>
        <a href="login.php" class="ds-drop-item danger"><i class="bi bi-box-arrow-right"></i> Sign Out</a>
      </div>
    </div>
  </div>
</header>
<main class="ds-main">
  <!-- Stat Cards -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-xl-3"><div class="ds-stat blue"><div class="ds-si"><i class="bi bi-calendar2-check-fill"></i></div><div class="ds-sn">248</div><div class="ds-sl">Total Bookings</div><div class="ds-tr up"><i class="bi bi-arrow-up-short"></i>12% this month</div></div></div>
    <div class="col-6 col-xl-3"><div class="ds-stat gold"><div class="ds-si"><i class="bi bi-hourglass-split"></i></div><div class="ds-sn">12</div><div class="ds-sl">Pending</div><div class="ds-tr down"><i class="bi bi-arrow-down-short"></i>3 need action</div></div></div>
    <div class="col-6 col-xl-3"><div class="ds-stat green"><div class="ds-si"><i class="bi bi-check-circle-fill"></i></div><div class="ds-sn">218</div><div class="ds-sl">Confirmed</div><div class="ds-tr up"><i class="bi bi-arrow-up-short"></i>88% of total</div></div></div>
    <div class="col-6 col-xl-3"><div class="ds-stat red"><div class="ds-si"><i class="bi bi-x-circle-fill"></i></div><div class="ds-sn">18</div><div class="ds-sl">Cancelled</div><div class="ds-tr down"><i class="bi bi-arrow-down-short"></i>7.2% rate</div></div></div>
  </div>
  <!-- Filter Bar -->
  <div class="ds-card mb-4">
    <div class="ds-ch"><div class="ds-ct"><i class="bi bi-funnel-fill"></i> Filter Bookings</div>
      <button class="ds-btn prim sm" onclick="dsToast('CSV exported successfully!','success')"><i class="bi bi-download me-1"></i>Export CSV</button>
    </div>
    <div class="ds-cb">
      <div class="row g-2">
        <div class="col-12 col-sm-5 col-md-4">
          <div class="ds-sw"><i class="bi bi-search ds-si-ic"></i><input class="ds-inp" id="bkSearch" type="text" placeholder="Search guest, ID, room..." oninput="filterBookings()"/></div>
        </div>
        <div class="col-6 col-sm-4 col-md-3">
          <select class="ds-inp ds-sel" id="bkStatus" onchange="filterBookings()">
            <option value="">All Statuses</option>
            <option value="confirmed">Confirmed</option>
            <option value="checkin">Checked In</option>
            <option value="pending">Pending</option>
            <option value="cancelled">Cancelled</option>
            <option value="checkout">Checked Out</option>
          </select>
        </div>
        <div class="col-6 col-sm-3 col-md-3">
          <input class="ds-inp" type="date" id="bkDate" onchange="filterBookings()" title="Filter by check-in date"/>
        </div>
      </div>
    </div>
  </div>
  <!-- Bookings Table -->
  <div class="ds-card mb-3">
    <div class="ds-ch"><div class="ds-ct"><i class="bi bi-table"></i> All Bookings <span class="badge bg-secondary ms-1">248</span></div></div>
    <div style="overflow-x:auto">
      <table class="ds-tbl" id="bkTable">
        <thead><tr><th>Booking ID</th><th>Guest</th><th>Room</th><th>Check-in</th><th>Check-out</th><th>Nights</th><th>Amount</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          <tr data-status="confirmed">
            <td class="fw-700 text-primary">#BKH001</td>
            <td><div class="d-flex align-items-center gap-2"><div class="ds-gav">RS</div><div><div class="fw-600">Rahul Sharma</div><div style="font-size:.72rem;color:var(--mut)">rahul.s@email.com</div></div></div></td>
            <td>Deluxe King · 204</td><td>12 Jul 2026</td><td>15 Jul 2026</td><td>3</td>
            <td class="fw-700 text-primary">₹8,598</td>
            <td><span class="ds-badge confirmed"><i class="bi bi-check-circle-fill"></i>Confirmed</span></td>
            <td><div class="d-flex gap-1">
              <button class="ds-btn gho ico sm" title="View Details" onclick="openBkModal('#BKH001','Rahul Sharma','RS','rahul.s@email.com','+91 98001 11222','Deluxe King · 204','12 Jul 2026','15 Jul 2026',3,'₹8,598','Confirmed','UPI')"><i class="bi bi-eye-fill"></i></button>
              <button class="ds-btn suc ico sm" title="Check In" onclick="updateStatus(this, 'checkin', 'Rahul Sharma checked in successfully!')"><i class="bi bi-box-arrow-in-right"></i></button>
              <button class="ds-btn dng ico sm" title="Cancel" onclick="updateStatus(this, 'cancelled', 'Booking cancelled.')"><i class="bi bi-x-lg"></i></button>
            </div></td>
          </tr>
          <tr data-status="checkin">
            <td class="fw-700 text-primary">#BKH002</td>
            <td><div class="d-flex align-items-center gap-2"><div class="ds-gav">PG</div><div><div class="fw-600">Priya Gupta</div><div style="font-size:.72rem;color:var(--mut)">priya.g@email.com</div></div></div></td>
            <td>Ocean Suite · 501</td><td>10 Jul 2026</td><td>14 Jul 2026</td><td>4</td>
            <td class="fw-700 text-primary">₹16,497</td>
            <td><span class="ds-badge checkin"><i class="bi bi-door-open"></i>Checked In</span></td>
            <td><div class="d-flex gap-1">
              <button class="ds-btn gho ico sm" title="View Details" onclick="openBkModal('#BKH002','Priya Gupta','PG','priya.g@email.com','+91 98001 22333','Ocean Suite · 501','10 Jul 2026','14 Jul 2026',4,'₹16,497','Checked In','Credit Card')"><i class="bi bi-eye-fill"></i></button>
              <button class="ds-btn suc ico sm" title="Check Out" onclick="updateStatus(this, 'checkout', 'Priya Gupta checked out.')"><i class="bi bi-box-arrow-right"></i></button>
              <button class="ds-btn dng ico sm" title="Cancel" onclick="updateStatus(this, 'cancelled', 'Booking cancelled.')"><i class="bi bi-x-lg"></i></button>
            </div></td>
          </tr>
          <tr data-status="pending">
            <td class="fw-700 text-primary">#BKH003</td>
            <td><div class="d-flex align-items-center gap-2"><div class="ds-gav">AK</div><div><div class="fw-600">Amit Kumar</div><div style="font-size:.72rem;color:var(--mut)">amit.k@email.com</div></div></div></td>
            <td>Standard Twin · 108</td><td>15 Jul 2026</td><td>17 Jul 2026</td><td>2</td>
            <td class="fw-700 text-primary">₹5,200</td>
            <td><span class="ds-badge pending"><i class="bi bi-hourglass-split"></i>Pending</span></td>
            <td><div class="d-flex gap-1">
              <button class="ds-btn gho ico sm" title="View Details" onclick="openBkModal('#BKH003','Amit Kumar','AK','amit.k@email.com','+91 98001 33444','Standard Twin · 108','15 Jul 2026','17 Jul 2026',2,'₹5,200','Pending','Net Banking')"><i class="bi bi-eye-fill"></i></button>
              <button class="ds-btn suc ico sm" title="Confirm" onclick="updateStatus(this, 'confirmed', 'Booking #BKH003 confirmed!')"><i class="bi bi-check-lg"></i></button>
              <button class="ds-btn dng ico sm" title="Cancel" onclick="updateStatus(this, 'cancelled', 'Booking cancelled.')"><i class="bi bi-x-lg"></i></button>
            </div></td>
          </tr>
          <tr data-status="confirmed">
            <td class="fw-700 text-primary">#BKH004</td>
            <td><div class="d-flex align-items-center gap-2"><div class="ds-gav">SR</div><div><div class="fw-600">Sneha Rao</div><div style="font-size:.72rem;color:var(--mut)">sneha.r@email.com</div></div></div></td>
            <td>Presidential Suite · 601</td><td>16 Jul 2026</td><td>20 Jul 2026</td><td>4</td>
            <td class="fw-700 text-primary">₹24,000</td>
            <td><span class="ds-badge confirmed"><i class="bi bi-check-circle-fill"></i>Confirmed</span></td>
            <td><div class="d-flex gap-1">
              <button class="ds-btn gho ico sm" title="View Details" onclick="openBkModal('#BKH004','Sneha Rao','SR','sneha.r@email.com','+91 98001 44555','Presidential Suite · 601','16 Jul 2026','20 Jul 2026',4,'₹24,000','Confirmed','Debit Card')"><i class="bi bi-eye-fill"></i></button>
              <button class="ds-btn suc ico sm" title="Check In" onclick="updateStatus(this, 'checkin', 'Sneha Rao checked in successfully!')"><i class="bi bi-box-arrow-in-right"></i></button>
              <button class="ds-btn dng ico sm" title="Cancel" onclick="updateStatus(this, 'cancelled', 'Booking cancelled.')"><i class="bi bi-x-lg"></i></button>
            </div></td>
          </tr>
          <tr data-status="cancelled">
            <td class="fw-700 text-primary">#BKH005</td>
            <td><div class="d-flex align-items-center gap-2"><div class="ds-gav">VJ</div><div><div class="fw-600">Vijay Joshi</div><div style="font-size:.72rem;color:var(--mut)">vijay.j@email.com</div></div></div></td>
            <td>Deluxe King · 305</td><td>18 Jul 2026</td><td>20 Jul 2026</td><td>2</td>
            <td class="fw-700 text-primary">₹8,598</td>
            <td><span class="ds-badge cancelled"><i class="bi bi-x-circle-fill"></i>Cancelled</span></td>
            <td><div class="d-flex gap-1">
              <button class="ds-btn gho ico sm" title="View Details" onclick="openBkModal('#BKH005','Vijay Joshi','VJ','vijay.j@email.com','+91 98001 55666','Deluxe King · 305','18 Jul 2026','20 Jul 2026',2,'₹8,598','Cancelled','UPI')"><i class="bi bi-eye-fill"></i></button>
              <button class="ds-btn gho ico sm disabled" title="Cancelled"><i class="bi bi-box-arrow-in-right"></i></button>
              <button class="ds-btn gho ico sm disabled" title="Cancelled"><i class="bi bi-x-lg"></i></button>
            </div></td>
          </tr>
          <tr data-status="checkout">
            <td class="fw-700 text-primary">#BKH006</td>
            <td><div class="d-flex align-items-center gap-2"><div class="ds-gav">NK</div><div><div class="fw-600">Neha Kapoor</div><div style="font-size:.72rem;color:var(--mut)">neha.k@email.com</div></div></div></td>
            <td>Deluxe Twin · 210</td><td>5 Jul 2026</td><td>10 Jul 2026</td><td>5</td>
            <td class="fw-700 text-primary">₹13,200</td>
            <td><span class="ds-badge checkout"><i class="bi bi-box-arrow-right"></i>Checked Out</span></td>
            <td><div class="d-flex gap-1">
              <button class="ds-btn gho ico sm" title="View Details" onclick="openBkModal('#BKH006','Neha Kapoor','NK','neha.k@email.com','+91 98001 66777','Deluxe Twin · 210','5 Jul 2026','10 Jul 2026',5,'₹13,200','Checked Out','Credit Card')"><i class="bi bi-eye-fill"></i></button>
              <button class="ds-btn gho ico sm disabled" title="Checked Out"><i class="bi bi-box-arrow-in-right"></i></button>
              <button class="ds-btn gho ico sm disabled" title="Checked Out"><i class="bi bi-x-lg"></i></button>
            </div></td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Pagination -->
    <div class="ds-cb border-top pt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
      <div style="font-size:.82rem;color:var(--mut)">Showing 1–6 of 248 bookings</div>
      <nav><ul class="pagination pagination-sm mb-0 gap-1">
        <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#" onclick="dsToast('Page 2 loaded','info');return false">2</a></li>
        <li class="page-item"><a class="page-link" href="#" onclick="dsToast('Page 3 loaded','info');return false">3</a></li>
        <li class="page-item"><a class="page-link" href="#">...</a></li>
        <li class="page-item"><a class="page-link" href="#" onclick="dsToast('Page 42 loaded','info');return false">42</a></li>
        <li class="page-item"><a class="page-link" href="#" onclick="dsToast('Page 2 loaded','info');return false">&raquo;</a></li>
      </ul></nav>
    </div>
  </div>
</main>
<!-- Booking Detail Modal -->
<div class="ds-modal modal fade" id="bkModal" tabindex="-1" aria-labelledby="bkModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title fw-700" id="bkModalLabel"><i class="bi bi-calendar2-check-fill text-primary me-2"></i>Booking Details</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="ds-card h-100">
              <div class="ds-ch"><div class="ds-ct"><i class="bi bi-person-fill"></i> Guest Information</div></div>
              <div class="ds-cb">
                <div class="d-flex align-items-center gap-3 mb-3">
                  <div class="ds-gav" id="bkAvatar" style="width:48px;height:48px;font-size:1.1rem"></div>
                  <div><div class="fw-700" id="bkGuestName" style="font-size:1rem"></div><div id="bkGuestEmail" style="font-size:.82rem;color:var(--mut)"></div></div>
                </div>
                <div class="row g-2">
                  <div class="col-6"><label class="ds-lbl">Phone</label><div id="bkGuestPhone" class="fw-600"></div></div>
                  <div class="col-6"><label class="ds-lbl">Booking ID</label><div id="bkId" class="fw-700 text-primary"></div></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="ds-card h-100">
              <div class="ds-ch"><div class="ds-ct"><i class="bi bi-door-open-fill"></i> Booking Summary</div></div>
              <div class="ds-cb">
                <div class="row g-2">
                  <div class="col-12"><label class="ds-lbl">Room</label><div id="bkRoom" class="fw-600"></div></div>
                  <div class="col-6"><label class="ds-lbl">Check-in</label><div id="bkCheckin" class="fw-600"></div></div>
                  <div class="col-6"><label class="ds-lbl">Check-out</label><div id="bkCheckout" class="fw-600"></div></div>
                  <div class="col-6"><label class="ds-lbl">Nights</label><div id="bkNights" class="fw-700 text-primary"></div></div>
                  <div class="col-6"><label class="ds-lbl">Status</label><div id="bkStatusDisplay"></div></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="ds-card">
              <div class="ds-ch"><div class="ds-ct"><i class="bi bi-credit-card-fill"></i> Payment Details</div></div>
              <div class="ds-cb">
                <div class="row g-3">
                  <div class="col-6 col-md-3"><label class="ds-lbl">Total Amount</label><div id="bkAmount" class="fw-800 text-primary" style="font-size:1.15rem"></div></div>
                  <div class="col-6 col-md-3"><label class="ds-lbl">Payment Method</label><div id="bkPayMethod" class="fw-600"></div></div>
                  <div class="col-6 col-md-3"><label class="ds-lbl">Payment Status</label><span class="ds-badge confirmed"><i class="bi bi-check-circle-fill"></i>Paid</span></div>
                  <div class="col-6 col-md-3"><label class="ds-lbl">GST (18%)</label><div id="bkGst" class="fw-600"></div></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer gap-2">
        <button class="ds-btn gho sm" data-bs-dismiss="modal">Close</button>
        <button class="ds-btn prim sm" onclick="dsToast('Invoice sent to guest email!','success')"><i class="bi bi-envelope-fill me-1"></i>Send Invoice</button>
        <button class="ds-btn suc sm" onclick="dsToast('Booking confirmed!','success')"><i class="bi bi-check-lg me-1"></i>Confirm</button>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="dashboard.js"></script>
<script>
function filterBookings(){
  const s=document.getElementById('bkSearch').value.toLowerCase();
  const st=document.getElementById('bkStatus').value.toLowerCase();
  const dt=document.getElementById('bkDate').value;
  document.querySelectorAll('#bkTable tbody tr').forEach(r=>{
    const txt=r.innerText.toLowerCase();
    const rowSt=r.dataset.status||'';
    const matchS=!s||txt.includes(s);
    const matchSt=!st||rowSt===st;
    r.style.display=(matchS&&matchSt)?'':'none';
  });
}
function openBkModal(id,name,av,email,phone,room,ci,co,nights,amt,status,pay){
  document.getElementById('bkId').textContent=id;
  document.getElementById('bkGuestName').textContent=name;
  document.getElementById('bkAvatar').textContent=av;
  document.getElementById('bkGuestEmail').textContent=email;
  document.getElementById('bkGuestPhone').textContent=phone;
  document.getElementById('bkRoom').textContent=room;
  document.getElementById('bkCheckin').textContent=ci;
  document.getElementById('bkCheckout').textContent=co;
  document.getElementById('bkNights').textContent=nights+' nights';
  document.getElementById('bkAmount').textContent=amt;
  document.getElementById('bkPayMethod').textContent=pay;
  const amtNum=parseInt(amt.replace(/[^\d]/g,''));
  document.getElementById('bkGst').textContent='₹'+Math.round(amtNum*0.18).toLocaleString('en-IN');
  const stMap={Confirmed:'confirmed','Checked In':'checkin',Pending:'pending',Cancelled:'cancelled','Checked Out':'checkout'};
  const stCls=stMap[status]||'pending';
  document.getElementById('bkStatusDisplay').innerHTML='<span class="ds-badge '+stCls+'">'+status+'</span>';
  new bootstrap.Modal(document.getElementById('bkModal')).show();
}
function updateStatus(btn, newStatus, msg) {
  dsConfirm('Confirm this action?', () => {
    const tr = btn.closest('tr');
    tr.dataset.status = newStatus;
    const badgeTd = tr.querySelector('td:nth-child(8)');
    const actionsTd = tr.querySelector('td:nth-child(9)');
    let badgeHtml = '';
    let actionsHtml = actionsTd.innerHTML;
    const viewBtn = actionsTd.querySelector('button').outerHTML;
    if(newStatus === 'checkin') {
      badgeHtml = '<span class="ds-badge checkin"><i class="bi bi-door-open"></i>Checked In</span>';
      actionsHtml = '<div class="d-flex gap-1">'+viewBtn+'<button class="ds-btn suc ico sm" title="Check Out" onclick="updateStatus(this, \'checkout\', \'Checked out successfully!\')"><i class="bi bi-box-arrow-right"></i></button><button class="ds-btn dng ico sm" title="Cancel" onclick="updateStatus(this, \'cancelled\', \'Booking cancelled.\')"><i class="bi bi-x-lg"></i></button></div>';
    } else if(newStatus === 'confirmed') {
      badgeHtml = '<span class="ds-badge confirmed"><i class="bi bi-check-circle-fill"></i>Confirmed</span>';
      actionsHtml = '<div class="d-flex gap-1">'+viewBtn+'<button class="ds-btn suc ico sm" title="Check In" onclick="updateStatus(this, \'checkin\', \'Checked in successfully!\')"><i class="bi bi-box-arrow-in-right"></i></button><button class="ds-btn dng ico sm" title="Cancel" onclick="updateStatus(this, \'cancelled\', \'Booking cancelled.\')"><i class="bi bi-x-lg"></i></button></div>';
    } else if(newStatus === 'checkout') {
      badgeHtml = '<span class="ds-badge checkout"><i class="bi bi-box-arrow-right"></i>Checked Out</span>';
      actionsHtml = '<div class="d-flex gap-1">'+viewBtn+'<button class="ds-btn gho ico sm disabled"><i class="bi bi-box-arrow-in-right"></i></button><button class="ds-btn gho ico sm disabled"><i class="bi bi-x-lg"></i></button></div>';
    } else if(newStatus === 'cancelled') {
      badgeHtml = '<span class="ds-badge cancelled"><i class="bi bi-x-circle-fill"></i>Cancelled</span>';
      actionsHtml = '<div class="d-flex gap-1">'+viewBtn+'<button class="ds-btn gho ico sm disabled"><i class="bi bi-box-arrow-in-right"></i></button><button class="ds-btn gho ico sm disabled"><i class="bi bi-x-lg"></i></button></div>';
    }
    badgeTd.innerHTML = badgeHtml;
    actionsTd.innerHTML = actionsHtml;
    dsToast(msg, newStatus === 'cancelled' ? 'error' : 'success');
    filterBookings();
  });
}
</script>
</body></html>

