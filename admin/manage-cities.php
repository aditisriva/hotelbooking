<?php
require_once 'auth_guard.php';
require_once '../hotel/db.php';

// ── Ensure cities table exists ──────────────────────────────────────────────
$create_cities = "CREATE TABLE IF NOT EXISTS `cities` (
  `city_id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `city_name` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) DEFAULT NULL,
  `status` ENUM('active','inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_city_name` (`city_name`),
  INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
mysqli_query($conn, $create_cities);

// ── AJAX Handlers ───────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $action = $_POST['action'];

    if ($action === 'create_city') {
        $city_name = trim($_POST['city_name'] ?? '');
        $state     = trim($_POST['state'] ?? '');
        $status    = in_array($_POST['status'] ?? '', ['active','inactive']) ? $_POST['status'] : 'active';

        if (!$city_name) {
            echo json_encode(['success'=>false,'message'=>'City name is required.']); exit;
        }

        $chk = mysqli_prepare($conn, "SELECT city_id FROM cities WHERE city_name = ? LIMIT 1");
        mysqli_stmt_bind_param($chk, 's', $city_name);
        mysqli_stmt_execute($chk);
        mysqli_stmt_store_result($chk);
        if (mysqli_stmt_num_rows($chk) > 0) {
            mysqli_stmt_close($chk);
            echo json_encode(['success'=>false,'message'=>'A city with this name already exists.']); exit;
        }
        mysqli_stmt_close($chk);

        $stmt = mysqli_prepare($conn, "INSERT INTO cities (city_name, state, status) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'sss', $city_name, $state, $status);
        $ok = mysqli_stmt_execute($stmt);
        $new_id = $ok ? mysqli_insert_id($conn) : 0;
        mysqli_stmt_close($stmt);

        echo json_encode(['success'=>$ok,'message'=>$ok?'City added successfully!':'Database error.','city_id'=>$new_id]);
        exit;
    }

    if ($action === 'get_city') {
        $cid = (int)($_POST['city_id'] ?? 0);
        $st = mysqli_prepare($conn, "SELECT city_id, city_name, state, status FROM cities WHERE city_id = ? LIMIT 1");
        mysqli_stmt_bind_param($st, 'i', $cid);
        mysqli_stmt_execute($st);
        $res = mysqli_stmt_get_result($st);
        $city = mysqli_fetch_assoc($res);
        mysqli_stmt_close($st);
        echo json_encode($city ?: ['error'=>'Not found']);
        exit;
    }

    if ($action === 'update_city') {
        $city_id   = (int)($_POST['city_id'] ?? 0);
        $city_name = trim($_POST['city_name'] ?? '');
        $state     = trim($_POST['state'] ?? '');
        $status    = in_array($_POST['status'] ?? '', ['active','inactive']) ? $_POST['status'] : 'active';

        if (!$city_id || !$city_name) {
            echo json_encode(['success'=>false,'message'=>'City name is required.']); exit;
        }

        $chk = mysqli_prepare($conn, "SELECT city_id FROM cities WHERE city_name = ? AND city_id != ? LIMIT 1");
        mysqli_stmt_bind_param($chk, 'si', $city_name, $city_id);
        mysqli_stmt_execute($chk);
        mysqli_stmt_store_result($chk);
        if (mysqli_stmt_num_rows($chk) > 0) {
            mysqli_stmt_close($chk);
            echo json_encode(['success'=>false,'message'=>'Another city with this name already exists.']); exit;
        }
        mysqli_stmt_close($chk);

        $stmt = mysqli_prepare($conn, "UPDATE cities SET city_name = ?, state = ?, status = ? WHERE city_id = ?");
        mysqli_stmt_bind_param($stmt, 'sssi', $city_name, $state, $status, $city_id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo json_encode(['success'=>$ok,'message'=>$ok?'City updated successfully!':'Database error.']);
        exit;
    }

    if ($action === 'delete_city') {
        $city_id = (int)($_POST['city_id'] ?? 0);
        if (!$city_id) {
            echo json_encode(['success'=>false,'message'=>'Invalid city.']); exit;
        }

        $hotel_count = (int)mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM hotels WHERE city = (SELECT city_name FROM cities WHERE city_id = $city_id)"))['c'];
        if ($hotel_count > 0) {
            echo json_encode(['success'=>false,'message'=>'This city cannot be deleted because hotels are assigned to it.']); exit;
        }

        $stmt = mysqli_prepare($conn, "DELETE FROM cities WHERE city_id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $city_id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo json_encode(['success'=>$ok,'message'=>$ok?'City deleted successfully!':'Database error.']);
        exit;
    }

    if ($action === 'toggle_status') {
        $city_id = (int)($_POST['city_id'] ?? 0);
        $new_status = $_POST['status'] ?? '';
        if (!in_array($new_status, ['active','inactive'])) {
            echo json_encode(['success'=>false,'message'=>'Invalid status.']); exit;
        }

        $stmt = mysqli_prepare($conn, "UPDATE cities SET status = ? WHERE city_id = ?");
        mysqli_stmt_bind_param($stmt, 'si', $new_status, $city_id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo json_encode(['success'=>$ok,'status'=>$new_status]);
        exit;
    }
}

// ── Stats ───────────────────────────────────────────────────────────────────
$total_cities    = (int)mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM cities"))['c'];
$active_cities   = (int)mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM cities WHERE status='active'"))['c'];
$inactive_cities = (int)mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM cities WHERE status='inactive'"))['c'];
$total_hotels    = (int)mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM hotels"))['c'];

// ── Filters ─────────────────────────────────────────────────────────────────
$fs    = trim($_GET['q'] ?? '');
$fstat = $_GET['status'] ?? '';
$where = ['1=1'];
if ($fs !== '') {
    $s = mysqli_real_escape_string($conn, $fs);
    $where[] = "(city_name LIKE '%$s%' OR state LIKE '%$s%')";
}
if (in_array($fstat, ['active','inactive'])) {
    $where[] = "status = '" . mysqli_real_escape_string($conn, $fstat) . "'";
}

$cities_list = [];
$res = mysqli_query($conn, "SELECT * FROM cities WHERE " . implode(' AND ', $where) . " ORDER BY created_at DESC");
if ($res) while ($row = mysqli_fetch_assoc($res)) $cities_list[] = $row;

$pageTitle    = 'Manage Cities';
$pageSubtitle = 'Manage operational cities for hotels';
include 'partials/header.php';
?>

<!-- Stats -->
<section class="row g-3 mb-3">
  <div class="col-12 col-md-6 col-xl-3">
    <div class="ds-stat blue"><div class="ds-si"><i class="bi bi-geo-alt-fill"></i></div>
      <div class="ds-sn"><?= $total_cities ?></div><div class="ds-sl">Total Cities</div></div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="ds-stat green"><div class="ds-si"><i class="bi bi-check-circle-fill"></i></div>
      <div class="ds-sn"><?= $active_cities ?></div><div class="ds-sl">Active Cities</div></div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="ds-stat gold"><div class="ds-si"><i class="bi bi-building"></i></div>
      <div class="ds-sn"><?= $total_hotels ?></div><div class="ds-sl">Total Hotels Across Cities</div></div>
  </div>
  <div class="col-12 col-md-6 col-xl-3">
    <div class="ds-stat red"><div class="ds-si"><i class="bi bi-x-circle-fill"></i></div>
      <div class="ds-sn"><?= $inactive_cities ?></div><div class="ds-sl">Inactive Cities</div></div>
  </div>
</section>

<!-- Cities Table -->
<div class="ds-card">
  <div class="ds-ch">
    <div class="ds-ct"><i class="bi bi-geo-alt-fill me-2"></i>Cities (<?= count($cities_list) ?>)</div>
    <div class="d-flex flex-wrap gap-2 align-items-center">
      <form method="GET" class="d-flex gap-2 flex-wrap align-items-center" id="filterForm">
        <div class="ds-sw">
          <i class="bi bi-search ds-si-ic"></i>
          <input class="ds-inp search" name="q" placeholder="Search city or state…"
                 value="<?= htmlspecialchars($fs) ?>" style="width:220px"/>
        </div>
        <select class="ds-inp ds-sel" name="status" style="width:140px" onchange="this.form.submit()">
          <option value="">All Cities</option>
          <option value="active" <?= $fstat==='active'?'selected':'' ?>>Active</option>
          <option value="inactive" <?= $fstat==='inactive'?'selected':'' ?>>Inactive</option>
        </select>
        <button type="submit" class="ds-btn prim sm"><i class="bi bi-search"></i></button>
        <a href="manage-cities.php" class="ds-btn gho sm">Clear</a>
      </form>
      <button class="ds-btn prim sm ms-auto" onclick="openAddCityModal()">
        <i class="bi bi-plus-lg me-1"></i>Add City
      </button>
    </div>
  </div>
  <div class="ds-cb p-0" id="cityTableContainer">
    <?php if (empty($cities_list)): ?>
      <div class="text-center py-5 text-muted">
        <i class="bi bi-geo-alt" style="font-size:3rem;opacity:.3"></i>
        <div class="fw-bold mt-3">No cities found</div>
        <div class="small mt-1">Add a city to get started.</div>
      </div>
    <?php else: ?>
    <div style="overflow-x:auto">
      <table class="ds-tbl">
        <thead>
          <tr>
            <th>City ID</th>
            <th>City Name</th>
            <th>State</th>
            <th>Total Hotels</th>
            <th>Status</th>
            <th>Created Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($cities_list as $c):
            $hotel_count = (int)mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM hotels WHERE city = '" . mysqli_real_escape_string($conn, strtolower($c['city_name'])) . "'"))['c'];
            $badge = $c['status'] === 'active' ? 'confirmed' : 'pending';
            $created_at = date('d M Y, h:i A', strtotime($c['created_at']));
        ?>
          <tr id="cityRow<?= $c['city_id'] ?>">
            <td class="text-muted small">#<?= $c['city_id'] ?></td>
            <td><div class="fw-700"><?= htmlspecialchars($c['city_name']) ?></div></td>
            <td class="small"><?= htmlspecialchars($c['state'] ?? '—') ?></td>
            <td class="small"><?= $hotel_count ?></td>
            <td id="status-cell-<?= $c['city_id'] ?>"><span class="ds-badge <?= $badge ?>"><?= ucfirst($c['status']) ?></span></td>
            <td class="small"><?= $created_at ?></td>
            <td>
              <div class="d-flex gap-1 flex-wrap">
                <button class="ds-btn gho sm" onclick="openEditCityModal(<?= $c['city_id'] ?>)" title="Edit">
                  <i class="bi bi-pencil-fill"></i> Edit
                </button>
                <?php if ($c['status'] === 'active'): ?>
                  <button id="toggle-btn-<?= $c['city_id'] ?>" class="ds-btn sm" style="background:#ef4444;color:#fff"
                          onclick="toggleCityStatus(<?= $c['city_id'] ?>, 'inactive', this)">
                    <i class="bi bi-slash-circle"></i> Deactivate
                  </button>
                <?php else: ?>
                  <button id="toggle-btn-<?= $c['city_id'] ?>" class="ds-btn sm" style="background:#10b981;color:#fff"
                          onclick="toggleCityStatus(<?= $c['city_id'] ?>, 'active', this)">
                    <i class="bi bi-check-circle"></i> Activate
                  </button>
                <?php endif; ?>
                <button class="ds-btn sm" style="color:#ef4444" title="Delete" onclick="confirmDeleteCity(<?= $c['city_id'] ?>, '<?= htmlspecialchars(addslashes($c['city_name'])) ?>')">
                  <i class="bi bi-trash-fill"></i>
                </button>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- Add City Modal -->
<div class="modal fade ds-modal" id="addCityModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-plus-circle-fill me-2"></i>Add New City</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" id="addCityForm">
        <input type="hidden" name="action" value="create_city"/>
        <div class="modal-body p-4">
          <div id="addCityAlert" class="alert d-none mb-3"></div>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="ds-lbl">City Name <span class="text-danger">*</span></label>
              <input class="ds-inp" name="city_name" placeholder="e.g. Mumbai" required/>
            </div>
            <div class="col-md-6">
              <label class="ds-lbl">State</label>
              <input class="ds-inp" name="state" placeholder="e.g. Maharashtra"/>
            </div>
            <div class="col-md-6">
              <label class="ds-lbl">Status</label>
              <select class="ds-inp ds-sel" name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="ds-btn gho" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="ds-btn prim" id="addCityBtn"><i class="bi bi-check-lg me-1"></i>Add City</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit City Modal -->
<div class="modal fade ds-modal" id="editCityModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-pencil-fill me-2"></i>Edit City</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" id="editCityForm">
        <input type="hidden" name="action" value="update_city"/>
        <input type="hidden" name="city_id" id="editCityId"/>
        <div class="modal-body p-4">
          <div id="editCityAlert" class="alert d-none mb-3"></div>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="ds-lbl">City Name <span class="text-danger">*</span></label>
              <input class="ds-inp" name="city_name" id="editCityName" required/>
            </div>
            <div class="col-md-6">
              <label class="ds-lbl">State</label>
              <input class="ds-inp" name="state" id="editCityState"/>
            </div>
            <div class="col-md-6">
              <label class="ds-lbl">Status</label>
              <select class="ds-inp ds-sel" name="status" id="editCityStatus">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="ds-btn gho" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="ds-btn prim" id="editCityBtn"><i class="bi bi-check-lg me-1"></i>Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Confirm Modal -->
<div class="modal fade ds-modal" id="deleteCityModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger"><i class="bi bi-trash-fill me-2"></i>Delete City</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <p>Are you sure you want to delete <strong id="deleteCityName"></strong>? This action cannot be undone.</p>
        <div id="deleteCityError" class="alert alert-danger d-none mt-3"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="ds-btn gho" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="ds-btn" style="background:#ef4444;color:#fff" onclick="submitDeleteCity()" id="confirmDeleteBtn">
          <i class="bi bi-trash-fill me-1"></i>Delete
        </button>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="deleteCityId"/>

<script>
function openAddCityModal() {
  document.getElementById('addCityForm').reset();
  document.getElementById('addCityAlert').className = 'alert d-none mb-3';
  new bootstrap.Modal(document.getElementById('addCityModal')).show();
}

document.getElementById('addCityForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const alertEl = document.getElementById('addCityAlert');
  const cityName = this.querySelector('[name="city_name"]').value.trim();
  if (!cityName) {
    alertEl.className = 'alert alert-danger mb-3';
    alertEl.textContent = 'City name is required.'; return;
  }
  const btn = document.getElementById('addCityBtn');
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving…';
  btn.disabled = true;

  const fd = new FormData(this);
  fetch('manage-cities.php', { method:'POST', body: fd })
  .then(r => r.json())
  .then(d => {
    btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Add City'; btn.disabled = false;
    if (d.success) {
      alertEl.className = 'alert alert-success mb-3';
      alertEl.textContent = '✓ ' + d.message + ' Refreshing…';
      setTimeout(() => location.reload(), 1200);
    } else {
      alertEl.className = 'alert alert-danger mb-3';
      alertEl.textContent = '✗ ' + d.message;
    }
  })
  .catch(() => { btn.innerHTML='<i class="bi bi-check-lg me-1"></i>Add City'; btn.disabled=false; });
});

function openEditCityModal(cityId) {
  const modal   = new bootstrap.Modal(document.getElementById('editCityModal'));
  const alertEl = document.getElementById('editCityAlert');
  alertEl.className = 'alert d-none mb-3';

  document.getElementById('editCityId').value = cityId;

  const fd = new FormData();
  fd.append('action', 'get_city');
  fd.append('city_id', cityId);

  fetch('manage-cities.php', { method:'POST', body: fd })
  .then(r => r.json())
  .then(c => {
    if (c.error) { alert('Could not load city data.'); return; }
    document.getElementById('editCityName').value = c.city_name || '';
    document.getElementById('editCityState').value = c.state || '';
    document.getElementById('editCityStatus').value = c.status || 'active';
    modal.show();
  })
  .catch(() => { alert('Network error.'); });
}

document.getElementById('editCityForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const alertEl = document.getElementById('editCityAlert');
  const btn = document.getElementById('editCityBtn');
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving…';
  btn.disabled = true;

  const fd = new FormData(this);
  fetch('manage-cities.php', { method:'POST', body: fd })
  .then(r => r.json())
  .then(d => {
    btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Save Changes'; btn.disabled = false;
    if (d.success) {
      alertEl.className = 'alert alert-success mb-3';
      alertEl.textContent = '✓ ' + d.message + ' Refreshing…';
      setTimeout(() => location.reload(), 1200);
    } else {
      alertEl.className = 'alert alert-danger mb-3';
      alertEl.textContent = '✗ ' + d.message;
    }
  })
  .catch(() => { btn.innerHTML='<i class="bi bi-check-lg me-1"></i>Save Changes'; btn.disabled=false; });
});

function toggleCityStatus(cityId, newStatus, btn) {
  if (!confirm('Are you sure you want to ' + (newStatus === 'active' ? 'activate' : 'deactivate') + ' this city?')) return;
  const fd = new FormData();
  fd.append('action', 'toggle_status');
  fd.append('city_id', cityId);
  fd.append('status', newStatus);

  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
  fetch('manage-cities.php', { method:'POST', body: fd })
  .then(r => r.json())
  .then(d => {
    if (d.success) {
      const cell = document.getElementById('status-cell-' + cityId);
      const bmap = {active:'confirmed',inactive:'pending'};
      cell.innerHTML = '<span class="ds-badge '+(bmap[d.status]||'pending')+'">' +
                       d.status.charAt(0).toUpperCase()+d.status.slice(1)+'</span>';
      if (d.status === 'active') {
        btn.style.background='#ef4444'; btn.style.color='#fff';
        btn.innerHTML='<i class="bi bi-slash-circle"></i> Deactivate';
        btn.onclick=()=>toggleCityStatus(cityId,'inactive',btn);
      } else {
        btn.style.background='#10b981'; btn.style.color='#fff';
        btn.innerHTML='<i class="bi bi-check-circle"></i> Activate';
        btn.onclick=()=>toggleCityStatus(cityId,'active',btn);
      }
      btn.disabled = false;
    } else {
      alert('Failed to update status.'); btn.disabled = false;
    }
  })
  .catch(()=>{ alert('Network error.'); btn.disabled = false; });
}

function confirmDeleteCity(cityId, cityName) {
  document.getElementById('deleteCityId').value = cityId;
  document.getElementById('deleteCityName').textContent = cityName;
  document.getElementById('deleteCityError').className = 'alert alert-danger d-none mt-3';
  document.getElementById('confirmDeleteBtn').disabled = false;
  new bootstrap.Modal(document.getElementById('deleteCityModal')).show();
}

function submitDeleteCity() {
  const cityId = document.getElementById('deleteCityId').value;
  const errorEl = document.getElementById('deleteCityError');
  const btn = document.getElementById('confirmDeleteBtn');

  const fd = new FormData();
  fd.append('action','delete_city'); fd.append('city_id', cityId);

  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Deleting…';

  fetch('manage-cities.php', {method:'POST', body: fd})
  .then(r => r.json())
  .then(d => {
    if (d.success) {
      const row = document.getElementById('cityRow' + cityId);
      if (row) row.remove();
      bootstrap.Modal.getInstance(document.getElementById('deleteCityModal')).hide();
      setTimeout(() => location.reload(), 600);
    } else {
      errorEl.textContent = d.message;
      errorEl.className = 'alert alert-danger mt-3';
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-trash-fill me-1"></i>Delete';
    }
  })
  .catch(() => {
    errorEl.textContent = 'Network error. Please try again.';
    errorEl.className = 'alert alert-danger mt-3';
    btn.disabled = false;
    btn.innerHTML = '<i class="bi bi-trash-fill me-1"></i>Delete';
  });
}
</script>

<?php include 'partials/footer.php'; ?>
