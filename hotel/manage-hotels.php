<?php
session_start();
require_once 'db.php';
require_once 'hotel_functions.php';

$msg = ''; $msg_type = 'success';

// ── Handle POST actions ───────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $hotel_id_edit = (int)($_POST['hotel_id'] ?? 0);
        $amenities_arr = isset($_POST['amenities']) && is_array($_POST['amenities']) ? $_POST['amenities'] : [];
        $amenities_str = implode(',', array_map('trim', $amenities_arr));

        // Handle image upload or URL
        $images_json = $_POST['existing_images'] ?? '[]';
        $uploaded_path = bhHandleImageUpload('hotel_image', $hotel_id_edit ?: 0);
        if ($uploaded_path) {
            $images_json = json_encode([$uploaded_path]);
        } elseif (!empty($_POST['image_url'])) {
            $images_json = json_encode([trim($_POST['image_url'])]);
        }

        $data = [
            'hotel_name'          => trim($_POST['hotel_name'] ?? ''),
            'city'                => strtolower(trim($_POST['city'] ?? '')),
            'location'            => trim($_POST['location'] ?? ''),
            'state'               => trim($_POST['state'] ?? ''),
            'description'         => trim($_POST['description'] ?? ''),
            'price_per_night'     => (float)($_POST['price_per_night'] ?? 0),
            'original_price'      => (float)($_POST['original_price'] ?? 0),
            'discount_percentage' => (float)($_POST['discount_percentage'] ?? 0),
            'gst_percentage'      => (float)($_POST['gst_percentage'] ?? 12),
            'rating'              => (float)($_POST['rating'] ?? 4.0),
            'star_rating'         => (int)($_POST['star_rating'] ?? 3),
            'property_type'       => $_POST['property_type'] ?? 'hotel',
            'amenities'           => $amenities_str,
            'capacity'            => (int)($_POST['capacity'] ?? 2),
            'availability_status' => $_POST['availability_status'] ?? 'active',
            'hotel_images'        => $images_json,
            'featured'            => isset($_POST['featured']) ? 1 : 0,
            'checkin_time'        => $_POST['checkin_time'] ?? '14:00',
            'checkout_time'       => $_POST['checkout_time'] ?? '11:00',
            'phone'               => trim($_POST['phone'] ?? ''),
            'email'               => trim($_POST['email'] ?? ''),
        ];

        if (empty($data['hotel_name']) || empty($data['city']) || $data['price_per_night'] <= 0) {
            $msg = 'Hotel name, city, and price are required.';
            $msg_type = 'danger';
        } elseif ($action === 'add') {
            $new_id = bhInsertHotel($data);
            $msg = $new_id ? 'Hotel added successfully! ID: ' . $new_id : 'Error adding hotel.';
            $msg_type = $new_id ? 'success' : 'danger';
        } else {
            $ok = bhUpdateHotel($hotel_id_edit, $data);
            $msg = $ok ? 'Hotel updated successfully!' : 'Error updating hotel.';
            $msg_type = $ok ? 'success' : 'danger';
        }
    }

    if ($action === 'delete') {
        $del_id = (int)($_POST['hotel_id'] ?? 0);
        $ok = $del_id ? bhDeleteHotel($del_id) : false;
        $msg = $ok ? 'Hotel deleted.' : 'Error deleting hotel.';
        $msg_type = $ok ? 'success' : 'danger';
    }

    if ($action === 'toggle_status') {
        $tid = (int)($_POST['hotel_id'] ?? 0);
        $new_status = $_POST['new_status'] ?? 'active';
        $h = bhGetHotelById($tid);
        if ($h) {
            $h['availability_status'] = $new_status;
            bhUpdateHotel($tid, $h);
            $msg = 'Status updated.';
        }
    }
}

// ── Fetch all hotels ──────────────────────────────────────────────────────
$hotels = bhGetHotels();
$stats  = bhHotelStats();

// Edit mode: load hotel for editing
$edit_hotel = null;
if (isset($_GET['edit'])) {
    $edit_hotel = bhGetHotelById((int)$_GET['edit']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>Hotel Management – BookHotel Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="dashboard.css"/>
<style>
.hotel-thumb{width:60px;height:45px;object-fit:cover;border-radius:8px}
.status-dot{width:8px;height:8px;border-radius:50%;display:inline-block;margin-right:5px}
.status-active{background:#10b981}.status-inactive{background:#ef4444}.status-maintenance{background:#f59e0b}
.amenity-chip{display:inline-flex;align-items:center;gap:4px;background:#e8f0fe;color:#1a56db;border-radius:20px;padding:3px 10px;font-size:.75rem;font-weight:600;cursor:pointer;user-select:none}
.amenity-chip input{display:none}
.amenity-chip.selected,.amenity-chip:has(input:checked){background:#1a56db;color:#fff}
.img-preview{width:100%;height:160px;object-fit:cover;border-radius:12px;border:2px dashed #cbd5e1}
.hotel-row:hover{background:#f8fafc}
.action-btn{border:none;background:transparent;padding:4px 8px;border-radius:6px;cursor:pointer;transition:.15s}
.action-btn:hover{background:#e2e8f0}
</style>
</head>
<body>
<div class="ds-ov" id="dsOv"></div>
<aside class="ds-sb" id="dsSb">
  <a href="admin-dashboard.php" class="ds-logo">
    <div class="ds-logo-icon"><i class="bi bi-buildings"></i></div>
    <div><div class="ds-logo-name">bookHotel</div><div class="ds-logo-role">Hotel Operations</div></div>
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
</aside>
<header class="ds-top">
  <div class="ds-top-l">
    <button class="ds-ibtn d-lg-none" id="dsTog"><i class="bi bi-list fs-5"></i></button>
    <div><div class="ds-page-title">Hotel Management</div><div class="ds-breadcrumb">Dashboard / Hotels</div></div>
  </div>
  <div class="ds-top-r">
    <a href="admin-notifications.php" class="ds-ibtn"><i class="bi bi-bell-fill"></i></a>
    <div class="ds-avbtn" id="dsAvBtn">
      <div class="ds-av">AD</div><span class="ds-avname d-none d-sm-block">Admin</span>
      <div class="ds-dropdown" id="dsAvMenu">
        <a href="admin-settings.php" class="ds-drop-item"><i class="bi bi-gear-fill text-primary"></i> Settings</a>
        <hr class="my-1 mx-2"/><a href="login.php" class="ds-drop-item danger"><i class="bi bi-box-arrow-right"></i> Sign Out</a>
      </div>
    </div>
  </div>
</header>
<main class="ds-main">

<?php if ($msg): ?>
<div class="alert alert-<?php echo $msg_type; ?> alert-dismissible fade show mx-3 mt-2" role="alert">
  <i class="bi bi-<?php echo $msg_type==='success'?'check-circle-fill':'exclamation-triangle-fill'; ?> me-2"></i>
  <?php echo htmlspecialchars($msg); ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- Stat cards -->
<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3">
    <div class="ds-stat blue"><div class="ds-si"><i class="bi bi-building-fill"></i></div>
      <div class="ds-sn"><?php echo $stats['total']; ?></div><div class="ds-sl">Total Hotels</div></div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="ds-stat green"><div class="ds-si"><i class="bi bi-check-circle-fill"></i></div>
      <div class="ds-sn"><?php echo $stats['active']; ?></div><div class="ds-sl">Active</div></div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="ds-stat gold"><div class="ds-si"><i class="bi bi-star-fill"></i></div>
      <div class="ds-sn"><?php echo $stats['featured']; ?></div><div class="ds-sl">Featured</div></div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="ds-stat purple"><div class="ds-si"><i class="bi bi-geo-alt-fill"></i></div>
      <div class="ds-sn"><?php echo $stats['cities']; ?></div><div class="ds-sl">Cities</div></div>
  </div>
</div>

<!-- Hotel List + Add Form -->
<div class="row g-4">
  <!-- LEFT: Hotel List -->
  <div class="col-12 col-xl-7">
    <div class="ds-card">
      <div class="ds-ch">
        <div class="ds-ct"><i class="bi bi-list-ul me-2"></i>All Hotels (<?php echo count($hotels); ?>)</div>
        <button class="ds-btn prim sm" data-bs-toggle="modal" data-bs-target="#addHotelModal">
          <i class="bi bi-plus-lg"></i> Add Hotel
        </button>
      </div>
      <div class="ds-cb p-0">
        <!-- Search bar -->
        <div class="p-3 border-bottom">
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control" id="hotelSearch" placeholder="Search hotels by name or city..."/>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="hotelsTable">
            <thead style="background:#f8fafc;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;color:#64748b">
              <tr>
                <th class="ps-3">Hotel</th>
                <th>City</th>
                <th>Price/Night</th>
                <th>Rating</th>
                <th>Status</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($hotels as $h):
                $himg = bhFirstImage($h['hotel_images'] ?? '', 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=100&q=60');
                $statusClass = 'status-' . $h['availability_status'];
              ?>
              <tr class="hotel-row" data-name="<?php echo strtolower($h['hotel_name']); ?>" data-city="<?php echo strtolower($h['city']); ?>">
                <td class="ps-3">
                  <div class="d-flex align-items-center gap-2">
                    <img src="<?php echo htmlspecialchars($himg); ?>" class="hotel-thumb" alt=""
                         onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=100&q=60'"/>
                    <div>
                      <div class="fw-700" style="font-size:.875rem"><?php echo htmlspecialchars($h['hotel_name']); ?></div>
                      <div class="text-muted" style="font-size:.75rem"><?php echo htmlspecialchars($h['property_type']); ?></div>
                    </div>
                  </div>
                </td>
                <td class="small"><?php echo htmlspecialchars(ucfirst($h['city'])); ?></td>
                <td class="small fw-700">₹<?php echo number_format($h['price_per_night']); ?></td>
                <td><span class="badge bg-warning text-dark"><?php echo $h['rating']; ?> ★</span></td>
                <td>
                  <span class="status-dot <?php echo $statusClass; ?>"></span>
                  <span style="font-size:.8rem"><?php echo ucfirst($h['availability_status']); ?></span>
                </td>
                <td class="text-center">
                  <a href="?edit=<?php echo $h['hotel_id']; ?>" class="action-btn text-primary" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                  <button class="action-btn text-danger" title="Delete"
                    onclick="confirmDelete(<?php echo $h['hotel_id']; ?>,'<?php echo htmlspecialchars(addslashes($h['hotel_name'])); ?>')">
                    <i class="bi bi-trash-fill"></i>
                  </button>
                  <a href="../hotel-details.php?id=<?php echo $h['hotel_id']; ?>" target="_blank" class="action-btn text-success" title="View on site"><i class="bi bi-eye-fill"></i></a>
                </td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($hotels)): ?>
              <tr><td colspan="6" class="text-center py-4 text-muted">No hotels found. Add your first hotel!</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- RIGHT: Edit or Quick Stats -->
  <div class="col-12 col-xl-5">
    <?php if ($edit_hotel): ?>
    <!-- Edit Form -->
    <div class="ds-card">
      <div class="ds-ch">
        <div class="ds-ct"><i class="bi bi-pencil-fill me-2"></i>Edit Hotel</div>
        <a href="admin-hotel-profile.php" class="ds-btn gho sm"><i class="bi bi-x-lg"></i> Cancel</a>
      </div>
      <div class="ds-cb">
        <form method="POST" enctype="multipart/form-data">
          <input type="hidden" name="action" value="edit"/>
          <input type="hidden" name="hotel_id" value="<?php echo $edit_hotel['hotel_id']; ?>"/>
          <input type="hidden" name="existing_images" value="<?php echo htmlspecialchars($edit_hotel['hotel_images'] ?? '[]'); ?>"/>
          <?php $eh = $edit_hotel; include '_hotel_form_fields.php'; ?>
          <div class="d-flex gap-2 mt-3">
            <button type="submit" class="ds-btn prim flex-grow-1"><i class="bi bi-check-lg me-1"></i> Save Changes</button>
            <a href="admin-hotel-profile.php" class="ds-btn gho">Cancel</a>
          </div>
        </form>
      </div>
    </div>
    <?php else: ?>
    <!-- Quick stats panel -->
    <div class="ds-card mb-4">
      <div class="ds-ch"><div class="ds-ct"><i class="bi bi-lightning-fill me-2"></i>Quick Actions</div></div>
      <div class="ds-cb">
        <div class="d-grid gap-2">
          <button class="ds-btn prim" data-bs-toggle="modal" data-bs-target="#addHotelModal">
            <i class="bi bi-plus-circle-fill me-2"></i>Add New Hotel
          </button>
          <a href="../hotels.php" target="_blank" class="ds-btn outl">
            <i class="bi bi-eye me-2"></i>View User Hotel Page
          </a>
        </div>
        <hr/>
        <div class="row g-3 mt-1">
          <?php foreach ($hotels as $h):
            $si = bhFirstImage($h['hotel_images']??'','https://images.unsplash.com/photo-1566073771259-6a8506099945?w=80&q=60');
          ?>
          <div class="col-12">
            <div class="d-flex align-items-center gap-3 p-2 rounded-3 border">
              <img src="<?php echo htmlspecialchars($si); ?>" style="width:50px;height:38px;object-fit:cover;border-radius:6px" alt=""
                   onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=80&q=60'"/>
              <div class="flex-grow-1">
                <div class="fw-700" style="font-size:.8rem"><?php echo htmlspecialchars($h['hotel_name']); ?></div>
                <div class="text-muted" style="font-size:.72rem"><?php echo ucfirst($h['city']); ?> · ₹<?php echo number_format($h['price_per_night']); ?>/night</div>
              </div>
              <a href="?edit=<?php echo $h['hotel_id']; ?>" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size:.75rem">Edit</a>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div><!-- end row -->

<!-- ───────── ADD HOTEL MODAL ───────── -->
<div class="modal fade ds-modal" id="addHotelModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-plus-circle-fill me-2"></i>Add New Hotel</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add"/>
        <div class="modal-body p-4">
          <?php $eh = null; include '_hotel_form_fields.php'; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="ds-btn gho" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="ds-btn prim"><i class="bi bi-check-lg me-1"></i> Add Hotel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ───────── DELETE CONFIRM MODAL ───────── -->
<div class="modal fade ds-modal" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title text-danger"><i class="bi bi-trash-fill me-2"></i>Delete Hotel</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <p>Are you sure you want to delete <strong id="deleteHotelName"></strong>? This action cannot be undone.</p>
      </div>
      <div class="modal-footer">
        <button class="ds-btn gho" data-bs-dismiss="modal">Cancel</button>
        <form method="POST" style="display:inline">
          <input type="hidden" name="action" value="delete"/>
          <input type="hidden" name="hotel_id" id="deleteHotelId"/>
          <button type="submit" class="ds-btn" style="background:#ef4444;color:#fff"><i class="bi bi-trash-fill me-1"></i>Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>

</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="dashboard.js"></script>
<script>
function confirmDelete(id, name) {
  document.getElementById('deleteHotelId').value = id;
  document.getElementById('deleteHotelName').textContent = name;
  new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

// Live search
document.getElementById('hotelSearch').addEventListener('input', function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll('#hotelsTable tbody tr.hotel-row').forEach(row => {
    row.style.display = (row.dataset.name.includes(q) || row.dataset.city.includes(q)) ? '' : 'none';
  });
});

// Image preview
function previewImage(input, previewId) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => document.getElementById(previewId).src = e.target.result;
    reader.readAsDataURL(input.files[0]);
  }
}
</script>
</body>
</html>

