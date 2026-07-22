<?php
session_start();
require_once 'db.php';

// ── Auto-create rooms table ───────────────────────────────────────────────
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `rooms` (
  `room_id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `hotel_id`        INT UNSIGNED DEFAULT 1,
  `room_number`     VARCHAR(20) NOT NULL,
  `room_type`       ENUM('Standard','Deluxe','Suite','Presidential Suite','Family Room','Studio') DEFAULT 'Standard',
  `floor`           TINYINT DEFAULT 1,
  `adult_capacity`  TINYINT DEFAULT 2,
  `child_capacity`  TINYINT DEFAULT 0,
  `bed_type`        ENUM('Single','Double','Queen','King','Twin','Bunk') DEFAULT 'Double',
  `base_price`      DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `discount_pct`    DECIMAL(5,2) DEFAULT 0.00,
  `description`     TEXT DEFAULT NULL,
  `amenities`       VARCHAR(255) DEFAULT NULL,
  `room_images`     TEXT DEFAULT NULL,
  `status`          ENUM('available','occupied','maintenance') DEFAULT 'available',
  `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_hotel` (`hotel_id`),
  INDEX `idx_status`(`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// ── AJAX / POST handler ───────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $action = $_POST['action'] ?? '';

    // Helper: handle multiple image uploads
    function handleRoomImages(int $roomId): string {
        $paths = [];
        if (!empty($_FILES['room_images']['name'][0])) {
            $dir = __DIR__ . '/uploads/rooms/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            foreach ($_FILES['room_images']['tmp_name'] as $i => $tmp) {
                if ($_FILES['room_images']['error'][$i] !== UPLOAD_ERR_OK) continue;
                $ext = strtolower(pathinfo($_FILES['room_images']['name'][$i], PATHINFO_EXTENSION));
                if (!in_array($ext, ['jpg','jpeg','png','webp','gif'])) continue;
                $fname = 'room_' . $roomId . '_' . time() . '_' . $i . '.' . $ext;
                if (move_uploaded_file($tmp, $dir . $fname))
                    $paths[] = 'uploads/rooms/' . $fname;
            }
        }
        return $paths ? json_encode($paths) : '';
    }

    if ($action === 'add') {
        $rn   = mysqli_real_escape_string($conn, trim($_POST['room_number'] ?? ''));
        $rt   = mysqli_real_escape_string($conn, $_POST['room_type'] ?? 'Standard');
        $fl   = (int)($_POST['floor'] ?? 1);
        $ac   = (int)($_POST['adult_capacity'] ?? 2);
        $cc   = (int)($_POST['child_capacity'] ?? 0);
        $bt   = mysqli_real_escape_string($conn, $_POST['bed_type'] ?? 'Double');
        $bp   = (float)($_POST['base_price'] ?? 0);
        $dp   = (float)($_POST['discount_pct'] ?? 0);
        $desc = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
        $amen = mysqli_real_escape_string($conn, trim($_POST['amenities'] ?? ''));
        $st   = mysqli_real_escape_string($conn, $_POST['status'] ?? 'available');
        $hid  = 1; // default hotel

        if (!$rn || $bp <= 0) { echo json_encode(['success'=>false,'error'=>'Room number and price are required']); exit; }

        // Check duplicate room number
        $chk = mysqli_query($conn, "SELECT room_id FROM rooms WHERE room_number='$rn' AND hotel_id=$hid LIMIT 1");
        if (mysqli_num_rows($chk) > 0) { echo json_encode(['success'=>false,'error'=>'Room number already exists']); exit; }

        $stmt = mysqli_prepare($conn, "INSERT INTO rooms (hotel_id,room_number,room_type,floor,adult_capacity,child_capacity,bed_type,base_price,discount_pct,description,amenities,status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        mysqli_stmt_bind_param($stmt,'issiiisddsss', $hid,$rn,$rt,$fl,$ac,$cc,$bt,$bp,$dp,$desc,$amen,$st);
        if (mysqli_stmt_execute($stmt)) {
            $new_id = mysqli_insert_id($conn);
            $imgs = handleRoomImages($new_id);
            if ($imgs) mysqli_query($conn, "UPDATE rooms SET room_images='".mysqli_real_escape_string($conn,$imgs)."' WHERE room_id=$new_id");
            mysqli_stmt_close($stmt);
            echo json_encode(['success'=>true,'room_id'=>$new_id]);
        } else {
            $err = mysqli_error($conn);
            mysqli_stmt_close($stmt);
            echo json_encode(['success'=>false,'error'=>$err]);
        }
        exit;
    }

    if ($action === 'edit') {
        $rid  = (int)($_POST['room_id'] ?? 0);
        $rn   = mysqli_real_escape_string($conn, trim($_POST['room_number'] ?? ''));
        $rt   = mysqli_real_escape_string($conn, $_POST['room_type'] ?? 'Standard');
        $fl   = (int)($_POST['floor'] ?? 1);
        $ac   = (int)($_POST['adult_capacity'] ?? 2);
        $cc   = (int)($_POST['child_capacity'] ?? 0);
        $bt   = mysqli_real_escape_string($conn, $_POST['bed_type'] ?? 'Double');
        $bp   = (float)($_POST['base_price'] ?? 0);
        $dp   = (float)($_POST['discount_pct'] ?? 0);
        $desc = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
        $amen = mysqli_real_escape_string($conn, trim($_POST['amenities'] ?? ''));
        $st   = mysqli_real_escape_string($conn, $_POST['status'] ?? 'available');

        if (!$rid || !$rn || $bp <= 0) { echo json_encode(['success'=>false,'error'=>'Invalid input']); exit; }

        // Check duplicate (exclude self)
        $chk = mysqli_query($conn, "SELECT room_id FROM rooms WHERE room_number='$rn' AND hotel_id=1 AND room_id!=$rid LIMIT 1");
        if (mysqli_num_rows($chk) > 0) { echo json_encode(['success'=>false,'error'=>'Room number already used by another room']); exit; }

        // Handle new images if uploaded
        $imgSql = '';
        $imgs = handleRoomImages($rid);
        if ($imgs) $imgSql = ",room_images='".mysqli_real_escape_string($conn,$imgs)."'";

        $ok = mysqli_query($conn, "UPDATE rooms SET room_number='$rn',room_type='$rt',floor=$fl,adult_capacity=$ac,child_capacity=$cc,bed_type='$bt',base_price=$bp,discount_pct=$dp,description='$desc',amenities='$amen',status='$st'$imgSql WHERE room_id=$rid");
        echo json_encode(['success'=>$ok, 'error'=>$ok?'':mysqli_error($conn)]);
        exit;
    }

    if ($action === 'delete') {
        $rid = (int)($_POST['room_id'] ?? 0);
        $ok  = $rid ? mysqli_query($conn, "DELETE FROM rooms WHERE room_id=$rid") : false;
        echo json_encode(['success'=>(bool)$ok]);
        exit;
    }

    echo json_encode(['success'=>false,'error'=>'Unknown action']);
    exit;
}

// ── Filters & pagination ──────────────────────────────────────────────────
$search    = trim($_GET['q']      ?? '');
$fstatus   = trim($_GET['status'] ?? '');
$page      = max(1, (int)($_GET['page'] ?? 1));
$per_page  = 10;
$where     = ['hotel_id = 1'];
if ($search) {
    $s = mysqli_real_escape_string($conn, $search);
    $where[] = "(room_number LIKE '%$s%' OR room_type LIKE '%$s%')";
}
if ($fstatus) $where[] = "status = '".mysqli_real_escape_string($conn,$fstatus)."'";
$whereSQL  = implode(' AND ', $where);
$offset    = ($page - 1) * $per_page;

$total_rows = (int)mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM rooms WHERE $whereSQL"))['c'];
$total_pages = max(1, ceil($total_rows / $per_page));

$rooms = [];
$res = mysqli_query($conn, "SELECT * FROM rooms WHERE $whereSQL ORDER BY room_number ASC LIMIT $per_page OFFSET $offset");
if ($res) while ($row = mysqli_fetch_assoc($res)) $rooms[] = $row;

// Stats
$stats_res = mysqli_query($conn, "SELECT
    COUNT(*) AS total,
    SUM(status='available') AS available,
    SUM(status='occupied') AS occupied,
    SUM(status='maintenance') AS maintenance
    FROM rooms WHERE hotel_id=1");
$stats = mysqli_fetch_assoc($stats_res) ?? ['total'=>0,'available'=>0,'occupied'=>0,'maintenance'=>0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>Manage Rooms – Hotel Manager</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" crossorigin="anonymous"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="dashboard.css"/>
<style>
.room-thumb{width:64px;height:48px;object-fit:cover;border-radius:8px;border:1.5px solid var(--bdr)}
.room-thumb-placeholder{width:64px;height:48px;border-radius:8px;background:var(--srf);border:1.5px dashed var(--bdr);display:flex;align-items:center;justify-content:center;color:var(--mut);font-size:1.2rem}
.amenity-chip{display:inline-flex;align-items:center;gap:4px;background:var(--pr-lt);color:var(--pr);border-radius:20px;padding:2px 10px;font-size:.72rem;font-weight:600;cursor:pointer;user-select:none;border:1.5px solid transparent;transition:.15s}
.amenity-chip:has(input:checked),.amenity-chip.selected{background:var(--pr);color:#fff}
.amenity-chip input{display:none}
.img-upload-box{border:2px dashed var(--bdr);border-radius:10px;padding:1.5rem;text-align:center;cursor:pointer;transition:.2s;background:var(--srf)}
.img-upload-box:hover{border-color:var(--pr);background:var(--pr-lt)}
.img-preview-wrap{display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.75rem}
.img-preview-wrap img{width:70px;height:55px;object-fit:cover;border-radius:8px;border:1.5px solid var(--bdr)}
.final-price{color:var(--grn);font-weight:800}
.orig-price{text-decoration:line-through;color:var(--mut);font-size:.78rem}
.ds-lbl{font-size:.72rem;font-weight:700;color:var(--mut);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.35rem}
.am-chip{display:inline-flex;align-items:center;gap:4px;background:var(--pr-lt);color:var(--pr);border-radius:20px;padding:3px 10px;font-size:.75rem;font-weight:600;margin:2px}
.page-link-ds{display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:8px;border:1.5px solid var(--bdr);font-size:.82rem;font-weight:600;color:var(--txt2);text-decoration:none;transition:.15s;cursor:pointer}
.page-link-ds:hover,.page-link-ds.active{background:var(--pr);color:#fff;border-color:var(--pr)}
</style>
</head>
<body>
<div class="ds-ov" id="dsOv"></div>

<!-- SIDEBAR -->
<aside class="ds-sb" id="dsSb">
  <a href="admin-dashboard.php" class="ds-logo">
    <div class="ds-logo-icon"><i class="bi bi-buildings"></i></div>
    <div><div class="ds-logo-name">bookHotel</div><div class="ds-logo-role">Hotel Operations</div></div>
  </a>
  <nav class="ds-nav">
    <div class="ds-sec">Main</div>
    <a href="admin-dashboard.php" class="ds-link"><i class="bi bi-grid-fill"></i> Dashboard</a>
    <a href="admin-hotel-profile.php" class="ds-link"><i class="bi bi-building"></i> Hotel Management</a>
    <div class="ds-sec">Operations</div>
    <a href="admin-rooms.php" class="ds-link active"><i class="bi bi-door-open-fill"></i> Rooms</a>
    <a href="admin-bookings.php" class="ds-link"><i class="bi bi-calendar2-check-fill"></i> Bookings</a>
    <a href="admin-guests.php" class="ds-link"><i class="bi bi-people-fill"></i> Guests</a>
    <div class="ds-sec">Insights</div>
    <a href="admin-reviews.php" class="ds-link"><i class="bi bi-star-fill"></i> Reviews</a>
    <a href="admin-revenue.php" class="ds-link"><i class="bi bi-bar-chart-fill"></i> Revenue</a>
    <a href="admin-notifications.php" class="ds-link"><i class="bi bi-bell-fill"></i> Notifications</a>
    <div class="ds-sec">Account</div>
    <a href="admin-settings.php" class="ds-link"><i class="bi bi-sliders"></i> Settings</a>
    <a href="index.php" class="ds-link"><i class="bi bi-box-arrow-left"></i> Back to Website</a>
  </nav>
</aside>

<!-- HEADER -->
<header class="ds-top">
  <div class="ds-top-l">
    <button class="ds-ibtn d-lg-none" id="dsTog"><i class="bi bi-list fs-5"></i></button>
    <div>
      <div class="ds-page-title">Manage Rooms</div>
      <div class="ds-breadcrumb">Dashboard / Rooms · <?php echo (int)$stats['total']; ?> rooms in inventory</div>
    </div>
  </div>
  <div class="ds-top-r">
    <a href="admin-notifications.php" class="ds-ibtn"><i class="bi bi-bell-fill"></i></a>
    <div class="ds-avbtn" id="dsAvBtn">
      <div class="ds-av">AD</div><span class="ds-avname d-none d-sm-block">Admin</span>
      <div class="ds-dropdown" id="dsAvMenu">
        <a href="admin-settings.php" class="ds-drop-item"><i class="bi bi-gear-fill text-primary"></i> Settings</a>
        <hr class="my-1 mx-2"/>
        <a href="login.php" class="ds-drop-item danger"><i class="bi bi-box-arrow-right"></i> Sign Out</a>
      </div>
    </div>
  </div>
</header>

<main class="ds-main">

<!-- STAT CARDS -->
<div class="row g-3 mb-4">
  <div class="col-6 col-xl-3">
    <div class="ds-stat blue"><div class="ds-si"><i class="bi bi-door-open-fill"></i></div>
      <div class="ds-sn"><?php echo (int)$stats['total']; ?></div><div class="ds-sl">Total Rooms</div></div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="ds-stat green"><div class="ds-si"><i class="bi bi-check-circle-fill"></i></div>
      <div class="ds-sn"><?php echo (int)$stats['available']; ?></div><div class="ds-sl">Available</div></div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="ds-stat red"><div class="ds-si"><i class="bi bi-person-fill"></i></div>
      <div class="ds-sn"><?php echo (int)$stats['occupied']; ?></div><div class="ds-sl">Occupied</div></div>
  </div>
  <div class="col-6 col-xl-3">
    <div class="ds-stat gold"><div class="ds-si"><i class="bi bi-tools"></i></div>
      <div class="ds-sn"><?php echo (int)$stats['maintenance']; ?></div><div class="ds-sl">Maintenance</div></div>
  </div>
</div>

<!-- ROOMS TABLE CARD -->
<div class="ds-card">
  <div class="ds-ch">
    <div class="ds-ct"><i class="bi bi-door-open-fill"></i> Room Inventory
      <span class="badge bg-primary ms-2" style="font-size:.72rem"><?php echo $total_rows; ?> rooms</span>
    </div>
    <div class="d-flex flex-wrap gap-2 align-items-center">
      <!-- Search + Filter -->
      <form method="GET" class="d-flex flex-wrap gap-2 align-items-center">
        <div class="ds-sw">
          <i class="bi bi-search ds-si-ic"></i>
          <input class="ds-inp search" name="q" placeholder="Room no. or type..." value="<?php echo htmlspecialchars($search); ?>" style="width:190px"/>
        </div>
        <select class="ds-inp ds-sel" name="status" style="width:145px" onchange="this.form.submit()">
          <option value="">All Statuses</option>
          <option value="available"   <?php echo $fstatus==='available'?'selected':''; ?>>Available</option>
          <option value="occupied"    <?php echo $fstatus==='occupied'?'selected':''; ?>>Occupied</option>
          <option value="maintenance" <?php echo $fstatus==='maintenance'?'selected':''; ?>>Maintenance</option>
        </select>
        <button type="submit" class="ds-btn prim sm"><i class="bi bi-search"></i></button>
        <?php if ($search||$fstatus): ?>
        <a href="admin-rooms.php" class="ds-btn gho sm"><i class="bi bi-x-lg"></i></a>
        <?php endif; ?>
      </form>
      <button class="ds-btn prim" data-bs-toggle="modal" data-bs-target="#addRoomModal">
        <i class="bi bi-plus-lg"></i> Add Room
      </button>
    </div>
  </div>

  <div class="ds-cb p-0">
    <?php if (empty($rooms)): ?>
    <div class="text-center py-5">
      <i class="bi bi-door-open" style="font-size:3rem;color:#cbd5e1"></i>
      <div class="fw-700 mt-3" style="color:#64748b">No rooms found</div>
      <div class="text-muted small mt-1">
        <?php echo ($search||$fstatus) ? 'Try clearing filters.' : 'Add your first room using the button above.'; ?>
      </div>
    </div>
    <?php else: ?>
    <div style="overflow-x:auto">
      <table class="ds-tbl">
        <thead>
          <tr>
            <th class="ps-3">Image</th>
            <th>Room No.</th>
            <th>Type</th>
            <th>Floor</th>
            <th>Capacity</th>
            <th>Base Price</th>
            <th>Discount</th>
            <th>Final Price</th>
            <th>Status</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($rooms as $r):
          $images = $r['room_images'] ? json_decode($r['room_images'], true) : [];
          $thumb  = $images ? $images[0] : '';
          $disc   = (float)$r['discount_pct'];
          $base   = (float)$r['base_price'];
          $final  = round($base * (1 - $disc / 100));
          $statusBadge = ['available'=>'available','occupied'=>'occupied','maintenance'=>'maintenance'];
          $cap = $r['adult_capacity'] . ' Adult' . ($r['adult_capacity']>1?'s':'');
          if ($r['child_capacity'] > 0) $cap .= ', ' . $r['child_capacity'] . ' Child' . ($r['child_capacity']>1?'ren':'');
        ?>
          <tr>
            <td class="ps-3">
              <?php if ($thumb): ?>
              <img src="<?php echo htmlspecialchars($thumb); ?>" class="room-thumb" alt=""
                   onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"/>
              <div class="room-thumb-placeholder" style="display:none"><i class="bi bi-image"></i></div>
              <?php else: ?>
              <div class="room-thumb-placeholder"><i class="bi bi-image"></i></div>
              <?php endif; ?>
            </td>
            <td><span class="fw-800" style="color:var(--pr);font-size:.95rem"><?php echo htmlspecialchars($r['room_number']); ?></span></td>
            <td>
              <div class="fw-600 small"><?php echo htmlspecialchars($r['room_type']); ?></div>
              <div class="text-muted" style="font-size:.72rem"><?php echo htmlspecialchars($r['bed_type']); ?> Bed</div>
            </td>
            <td class="small">Floor <?php echo (int)$r['floor']; ?></td>
            <td class="small"><?php echo htmlspecialchars($cap); ?></td>
            <td class="small">₹<?php echo number_format($base); ?></td>
            <td class="small">
              <?php if ($disc > 0): ?>
              <span class="badge bg-danger" style="font-size:.7rem"><?php echo $disc; ?>% OFF</span>
              <?php else: ?>
              <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($disc > 0): ?>
              <div class="orig-price">₹<?php echo number_format($base); ?></div>
              <?php endif; ?>
              <div class="final-price small">₹<?php echo number_format($final); ?></div>
            </td>
            <td><span class="ds-badge <?php echo $statusBadge[$r['status']] ?? 'available'; ?>"><?php echo ucfirst($r['status']); ?></span></td>
            <td class="text-center">
              <div class="d-flex gap-1 justify-content-center">
                <button class="ds-btn gho sm" title="View" onclick='viewRoom(<?php echo htmlspecialchars(json_encode($r),ENT_QUOTES); ?>)'>
                  <i class="bi bi-eye-fill"></i>
                </button>
                <button class="ds-btn outl sm" title="Edit" onclick='editRoom(<?php echo htmlspecialchars(json_encode($r),ENT_QUOTES); ?>)'>
                  <i class="bi bi-pencil-fill"></i>
                </button>
                <button class="ds-btn sm" style="background:var(--red-lt);color:var(--red);border-color:var(--red-lt)" title="Delete"
                  onclick="confirmDelete(<?php echo (int)$r['room_id']; ?>,'<?php echo htmlspecialchars(addslashes($r['room_number'])); ?>')">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top" style="border-color:var(--bdr)!important">
      <div class="text-muted small">
        Showing <?php echo $offset+1; ?>–<?php echo min($offset+$per_page,$total_rows); ?> of <?php echo $total_rows; ?> rooms
      </div>
      <div class="d-flex gap-1 flex-wrap">
        <?php
        $qs = http_build_query(['q'=>$search,'status'=>$fstatus]);
        if ($page > 1): ?>
        <a class="page-link-ds" href="?page=<?php echo $page-1; ?>&<?php echo $qs; ?>"><i class="bi bi-chevron-left"></i></a>
        <?php endif;
        for ($p = max(1,$page-2); $p <= min($total_pages,$page+2); $p++): ?>
        <a class="page-link-ds <?php echo $p===$page?'active':''; ?>" href="?page=<?php echo $p; ?>&<?php echo $qs; ?>"><?php echo $p; ?></a>
        <?php endfor;
        if ($page < $total_pages): ?>
        <a class="page-link-ds" href="?page=<?php echo $page+1; ?>&<?php echo $qs; ?>"><i class="bi bi-chevron-right"></i></a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</div>

</main>

<?php
// Shared amenity list + icons
$amenity_defs = [
    'wifi'      => ['WiFi',      'bi-wifi'],
    'ac'        => ['AC',        'bi-fan'],
    'tv'        => ['TV',        'bi-tv-fill'],
    'breakfast' => ['Breakfast', 'bi-cup-hot-fill'],
    'parking'   => ['Parking',   'bi-car-front-fill'],
    'balcony'   => ['Balcony',   'bi-house-door-fill'],
    'minibar'   => ['Mini Bar',  'bi-cup-straw'],
];
function amenityChips(string $selected='', string $prefix='add'): void {
    global $amenity_defs;
    $sel = array_filter(array_map('trim', explode(',', $selected)));
    foreach ($amenity_defs as $key => [$label, $icon]):
        $chk = in_array($key,$sel) ? 'checked' : '';
        $cls = in_array($key,$sel) ? 'amenity-chip selected' : 'amenity-chip';
        echo "<label class=\"$cls\"><input type=\"checkbox\" name=\"amenities[]\" value=\"$key\" $chk
              onchange=\"this.closest('label').classList.toggle('selected',this.checked)\"/>
              <i class=\"bi $icon\"></i> $label</label>";
    endforeach;
}
?>

<!-- ═══════════════ ADD ROOM MODAL ═══════════════ -->
<div class="modal fade ds-modal" id="addRoomModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-plus-circle-fill me-2"></i>Add New Room</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="addRoomForm" enctype="multipart/form-data">
        <input type="hidden" name="action" value="add"/>
        <div class="modal-body p-4">
          <div class="row g-3">
            <div class="col-md-4">
              <div class="ds-lbl">Room Number <span class="text-danger">*</span></div>
              <input class="ds-inp" name="room_number" required placeholder="e.g. 101, A-201"/>
            </div>
            <div class="col-md-4">
              <div class="ds-lbl">Room Type <span class="text-danger">*</span></div>
              <select class="ds-inp ds-sel" name="room_type">
                <?php foreach(['Standard','Deluxe','Suite','Presidential Suite','Family Room','Studio'] as $rt): ?>
                <option><?php echo $rt; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <div class="ds-lbl">Floor</div>
              <input class="ds-inp" type="number" name="floor" min="1" max="50" value="1"/>
            </div>
            <div class="col-md-3">
              <div class="ds-lbl">Adult Capacity</div>
              <input class="ds-inp" type="number" name="adult_capacity" min="1" max="10" value="2"/>
            </div>
            <div class="col-md-3">
              <div class="ds-lbl">Child Capacity</div>
              <input class="ds-inp" type="number" name="child_capacity" min="0" max="6" value="0"/>
            </div>
            <div class="col-md-3">
              <div class="ds-lbl">Bed Type</div>
              <select class="ds-inp ds-sel" name="bed_type">
                <?php foreach(['Single','Double','Queen','King','Twin','Bunk'] as $bt): ?>
                <option><?php echo $bt; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <div class="ds-lbl">Status</div>
              <select class="ds-inp ds-sel" name="status">
                <option value="available">Available</option>
                <option value="occupied">Occupied</option>
                <option value="maintenance">Maintenance</option>
              </select>
            </div>
            <div class="col-md-6">
              <div class="ds-lbl">Base Price (₹) <span class="text-danger">*</span></div>
              <div class="input-group">
                <span class="input-group-text bg-white" style="border:1.5px solid var(--bdr);border-right:none">₹</span>
                <input class="ds-inp" type="number" name="base_price" min="0" step="0.01" placeholder="e.g. 3500" style="border-left:none"/>
              </div>
            </div>
            <div class="col-md-6">
              <div class="ds-lbl">Discount %</div>
              <div class="input-group">
                <input class="ds-inp" type="number" name="discount_pct" min="0" max="100" step="0.01" value="0" placeholder="e.g. 15" style="border-right:none"/>
                <span class="input-group-text bg-white" style="border:1.5px solid var(--bdr);border-left:none">%</span>
              </div>
            </div>
            <div class="col-12">
              <div class="ds-lbl">Description</div>
              <textarea class="ds-inp" name="description" rows="2" placeholder="Describe the room features..."></textarea>
            </div>
            <div class="col-12">
              <div class="ds-lbl">Amenities</div>
              <div class="d-flex flex-wrap gap-2 mt-1" id="addAmenities">
                <?php amenityChips('','add'); ?>
                <input type="hidden" name="amenities" id="addAmenitiesHidden"/>
              </div>
            </div>
            <div class="col-12">
              <div class="ds-lbl">Room Images</div>
              <div class="img-upload-box" onclick="document.getElementById('addImgInput').click()">
                <i class="bi bi-cloud-arrow-up" style="font-size:1.8rem;color:var(--mut)"></i>
                <div class="fw-600 small mt-1" style="color:var(--mut)">Click to upload images</div>
                <div style="font-size:.72rem;color:var(--mut)">JPG, PNG, WEBP · Multiple allowed</div>
              </div>
              <input type="file" id="addImgInput" name="room_images[]" multiple accept="image/*" class="d-none"
                     onchange="previewImages(this,'addImgPreview')"/>
              <div class="img-preview-wrap" id="addImgPreview"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="ds-btn gho" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="ds-btn prim"><i class="bi bi-check-lg me-1"></i>Add Room</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ═══════════════ EDIT ROOM MODAL ═══════════════ -->
<div class="modal fade ds-modal" id="editRoomModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-pencil-fill me-2"></i>Edit Room</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form id="editRoomForm" enctype="multipart/form-data">
        <input type="hidden" name="action" value="edit"/>
        <input type="hidden" name="room_id" id="editRoomId"/>
        <div class="modal-body p-4">
          <div class="row g-3">
            <div class="col-md-4">
              <div class="ds-lbl">Room Number <span class="text-danger">*</span></div>
              <input class="ds-inp" name="room_number" id="editRoomNumber" required/>
            </div>
            <div class="col-md-4">
              <div class="ds-lbl">Room Type</div>
              <select class="ds-inp ds-sel" name="room_type" id="editRoomType">
                <?php foreach(['Standard','Deluxe','Suite','Presidential Suite','Family Room','Studio'] as $rt): ?>
                <option><?php echo $rt; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <div class="ds-lbl">Floor</div>
              <input class="ds-inp" type="number" name="floor" id="editFloor" min="1" max="50"/>
            </div>
            <div class="col-md-3">
              <div class="ds-lbl">Adult Capacity</div>
              <input class="ds-inp" type="number" name="adult_capacity" id="editAdultCap" min="1" max="10"/>
            </div>
            <div class="col-md-3">
              <div class="ds-lbl">Child Capacity</div>
              <input class="ds-inp" type="number" name="child_capacity" id="editChildCap" min="0" max="6"/>
            </div>
            <div class="col-md-3">
              <div class="ds-lbl">Bed Type</div>
              <select class="ds-inp ds-sel" name="bed_type" id="editBedType">
                <?php foreach(['Single','Double','Queen','King','Twin','Bunk'] as $bt): ?>
                <option><?php echo $bt; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <div class="ds-lbl">Status</div>
              <select class="ds-inp ds-sel" name="status" id="editStatus">
                <option value="available">Available</option>
                <option value="occupied">Occupied</option>
                <option value="maintenance">Maintenance</option>
              </select>
            </div>
            <div class="col-md-6">
              <div class="ds-lbl">Base Price (₹)</div>
              <input class="ds-inp" type="number" name="base_price" id="editBasePrice" min="0" step="0.01"/>
            </div>
            <div class="col-md-6">
              <div class="ds-lbl">Discount %</div>
              <input class="ds-inp" type="number" name="discount_pct" id="editDiscount" min="0" max="100" step="0.01"/>
            </div>
            <div class="col-12">
              <div class="ds-lbl">Description</div>
              <textarea class="ds-inp" name="description" id="editDesc" rows="2"></textarea>
            </div>
            <div class="col-12">
              <div class="ds-lbl">Amenities</div>
              <div class="d-flex flex-wrap gap-2 mt-1" id="editAmenitiesWrap"></div>
            </div>
            <div class="col-12">
              <div class="ds-lbl">Upload New Images (replaces existing)</div>
              <div class="img-upload-box" onclick="document.getElementById('editImgInput').click()">
                <i class="bi bi-cloud-arrow-up" style="font-size:1.8rem;color:var(--mut)"></i>
                <div class="small fw-600 mt-1" style="color:var(--mut)">Click to upload new images</div>
              </div>
              <input type="file" id="editImgInput" name="room_images[]" multiple accept="image/*" class="d-none"
                     onchange="previewImages(this,'editImgPreview')"/>
              <div class="img-preview-wrap" id="editImgPreview"></div>
              <div id="editCurrentImages" class="mt-2"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="ds-btn gho" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="ds-btn prim"><i class="bi bi-check-lg me-1"></i>Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ═══════════════ VIEW ROOM MODAL ═══════════════ -->
<div class="modal fade ds-modal" id="viewRoomModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-eye-fill me-2"></i>Room Details</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4" id="viewRoomBody"></div>
    </div>
  </div>
</div>

<!-- ═══════════════ DELETE CONFIRM MODAL ═══════════════ -->
<div class="modal fade ds-modal" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background:linear-gradient(135deg,#7f1d1d,var(--red))">
        <h5 class="modal-title text-white"><i class="bi bi-trash-fill me-2"></i>Delete Room</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <div class="d-flex gap-3 align-items-center">
          <div style="width:52px;height:52px;border-radius:14px;background:var(--red-lt);display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
          </div>
          <div>
            <div class="fw-700 mb-1">Are you sure you want to delete Room <strong id="deleteRoomNum"></strong>?</div>
            <div class="text-muted small">This action cannot be undone. All room data will be permanently removed.</div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="ds-btn gho" data-bs-dismiss="modal">Cancel</button>
        <button class="ds-btn" id="confirmDeleteBtn" style="background:var(--red);color:#fff;border-color:var(--red)">
          <i class="bi bi-trash-fill me-1"></i>Delete Room
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="dashboard.js"></script>
<script>
const amenityDefs = <?php echo json_encode($amenity_defs); ?>;

// ── Image preview ─────────────────────────────────────────────────────────
function previewImages(input, previewId) {
  const wrap = document.getElementById(previewId);
  wrap.innerHTML = '';
  Array.from(input.files).forEach(file => {
    const reader = new FileReader();
    reader.onload = e => {
      const img = document.createElement('img');
      img.src = e.target.result;
      wrap.appendChild(img);
    };
    reader.readAsDataURL(file);
  });
}

// ── Amenity chips builder for edit modal ─────────────────────────────────
function buildAmenityChips(wrap, selected) {
  wrap.innerHTML = '';
  const sel = selected ? selected.split(',').map(s => s.trim()) : [];
  Object.entries(amenityDefs).forEach(([key, [label, icon]]) => {
    const isSelected = sel.includes(key);
    const lbl = document.createElement('label');
    lbl.className = 'amenity-chip' + (isSelected ? ' selected' : '');
    lbl.innerHTML = `<input type="checkbox" name="amenities[]" value="${key}" ${isSelected?'checked':''}
      onchange="this.closest('label').classList.toggle('selected',this.checked)"/>
      <i class="bi ${icon}"></i> ${label}`;
    wrap.appendChild(lbl);
  });
}

// ── View Room ─────────────────────────────────────────────────────────────
function viewRoom(r) {
  const imgs  = r.room_images ? JSON.parse(r.room_images) : [];
  const disc  = parseFloat(r.discount_pct)||0;
  const base  = parseFloat(r.base_price)||0;
  const final = Math.round(base*(1-disc/100));
  const sel   = r.amenities ? r.amenities.split(',').map(s=>s.trim()) : [];
  const amenHtml = sel.map(k => {
    const def = amenityDefs[k];
    return def ? `<span class="am-chip"><i class="bi ${def[1]}"></i> ${def[0]}</span>` : '';
  }).join('');
  const imgHtml = imgs.length
    ? imgs.map(i=>`<img src="${i}" style="width:100px;height:75px;object-fit:cover;border-radius:8px;border:1.5px solid var(--bdr)" onerror="this.style.display='none'">`).join(' ')
    : '<span class="text-muted small">No images</span>';
  const statusColors = {available:'confirmed',occupied:'occupied',maintenance:'maintenance'};

  document.getElementById('viewRoomBody').innerHTML = `
    <div class="row g-3">
      <div class="col-12">${imgHtml}</div>
      <div class="col-md-4"><div class="ds-lbl">Room Number</div><div class="fw-800 fs-5" style="color:var(--pr)">${r.room_number}</div></div>
      <div class="col-md-4"><div class="ds-lbl">Type</div><div class="fw-600">${r.room_type}</div></div>
      <div class="col-md-4"><div class="ds-lbl">Status</div>
        <span class="ds-badge ${statusColors[r.status]||'available'}">${r.status}</span></div>
      <div class="col-md-3"><div class="ds-lbl">Floor</div><div>${r.floor}</div></div>
      <div class="col-md-3"><div class="ds-lbl">Bed Type</div><div>${r.bed_type}</div></div>
      <div class="col-md-3"><div class="ds-lbl">Adults</div><div>${r.adult_capacity}</div></div>
      <div class="col-md-3"><div class="ds-lbl">Children</div><div>${r.child_capacity}</div></div>
      <div class="col-md-4"><div class="ds-lbl">Base Price</div><div class="fw-700">₹${base.toLocaleString('en-IN')}</div></div>
      <div class="col-md-4"><div class="ds-lbl">Discount</div><div class="fw-700 text-danger">${disc}%</div></div>
      <div class="col-md-4"><div class="ds-lbl">Final Price</div><div class="fw-800" style="color:var(--grn);font-size:1.1rem">₹${final.toLocaleString('en-IN')}</div></div>
      ${r.description?`<div class="col-12"><div class="ds-lbl">Description</div><div class="text-muted small">${r.description}</div></div>`:''}
      ${amenHtml?`<div class="col-12"><div class="ds-lbl">Amenities</div><div class="mt-1">${amenHtml}</div></div>`:''}
    </div>`;
  new bootstrap.Modal(document.getElementById('viewRoomModal')).show();
}

// ── Edit Room ─────────────────────────────────────────────────────────────
function editRoom(r) {
  document.getElementById('editRoomId').value      = r.room_id;
  document.getElementById('editRoomNumber').value  = r.room_number;
  document.getElementById('editFloor').value       = r.floor;
  document.getElementById('editAdultCap').value    = r.adult_capacity;
  document.getElementById('editChildCap').value    = r.child_capacity;
  document.getElementById('editBasePrice').value   = r.base_price;
  document.getElementById('editDiscount').value    = r.discount_pct;
  document.getElementById('editDesc').value        = r.description||'';

  // Set selects
  ['editRoomType','editBedType','editStatus'].forEach(id => {
    const el = document.getElementById(id);
    const field = {editRoomType:'room_type',editBedType:'bed_type',editStatus:'status'}[id];
    for (let o of el.options) if (o.value===r[field]) { o.selected=true; break; }
  });

  // Amenity chips
  buildAmenityChips(document.getElementById('editAmenitiesWrap'), r.amenities||'');

  // Show current images
  const imgs = r.room_images ? JSON.parse(r.room_images) : [];
  document.getElementById('editCurrentImages').innerHTML = imgs.length
    ? '<div class="ds-lbl mt-2">Current Images</div><div class="img-preview-wrap">'
      + imgs.map(i=>`<img src="${i}" onerror="this.style.display='none'">`).join('')+'</div>'
    : '';

  document.getElementById('editImgPreview').innerHTML='';
  new bootstrap.Modal(document.getElementById('editRoomModal')).show();
}

// ── Delete ────────────────────────────────────────────────────────────────
let _deleteRoomId = null;
function confirmDelete(id, num) {
  _deleteRoomId = id;
  document.getElementById('deleteRoomNum').textContent = num;
  new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
  if (!_deleteRoomId) return;
  const fd = new FormData();
  fd.append('action','delete');
  fd.append('room_id',_deleteRoomId);
  fetch('admin-rooms.php',{method:'POST',body:fd})
    .then(r=>r.json())
    .then(d=>{
      if(d.success) {
        dsToast('Room deleted successfully','success');
        bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
        setTimeout(()=>location.reload(),700);
      } else dsToast('Delete failed','error');
    });
});

// ── Add Room Form ─────────────────────────────────────────────────────────
document.getElementById('addRoomForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const fd = new FormData(this);
  // Collect checked amenities
  const checked = [...this.querySelectorAll('input[name="amenities[]"]:checked')].map(c=>c.value);
  fd.delete('amenities[]');
  fd.set('amenities', checked.join(','));
  const btn = this.querySelector('[type="submit"]');
  btn.innerHTML='<span class="spinner-border spinner-border-sm me-1"></span>Saving...';
  btn.disabled=true;
  fetch('admin-rooms.php',{method:'POST',body:fd})
    .then(r=>r.json())
    .then(d=>{
      if(d.success) {
        dsToast('Room added successfully!','success');
        bootstrap.Modal.getInstance(document.getElementById('addRoomModal')).hide();
        this.reset();
        document.getElementById('addImgPreview').innerHTML='';
        setTimeout(()=>location.reload(),800);
      } else dsToast('Error: '+(d.error||'Unknown error'),'error');
      btn.innerHTML='<i class="bi bi-check-lg me-1"></i>Add Room';
      btn.disabled=false;
    });
});

// ── Edit Room Form ────────────────────────────────────────────────────────
document.getElementById('editRoomForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const fd = new FormData(this);
  const checked = [...this.querySelectorAll('input[name="amenities[]"]:checked')].map(c=>c.value);
  fd.delete('amenities[]');
  fd.set('amenities', checked.join(','));
  const btn = this.querySelector('[type="submit"]');
  btn.innerHTML='<span class="spinner-border spinner-border-sm me-1"></span>Saving...';
  btn.disabled=true;
  fetch('admin-rooms.php',{method:'POST',body:fd})
    .then(r=>r.json())
    .then(d=>{
      if(d.success) {
        dsToast('Room updated successfully!','success');
        bootstrap.Modal.getInstance(document.getElementById('editRoomModal')).hide();
        setTimeout(()=>location.reload(),800);
      } else dsToast('Error: '+(d.error||'Unknown error'),'error');
      btn.innerHTML='<i class="bi bi-check-lg me-1"></i>Save Changes';
      btn.disabled=false;
    });
});
</script>
</body>
</html>
