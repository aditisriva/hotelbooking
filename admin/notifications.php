<?php
session_start();
require_once 'db.php';
require_once 'auth_guard.php';

$admin_id = $_SESSION['admin_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $action = $_POST['action'] ?? '';
    
    if ($action === 'mark_read' && isset($_POST['id'])) {
        $stmt = mysqli_prepare($conn, "UPDATE notifications SET is_read=1 WHERE id=? AND user_id=?");
        mysqli_stmt_bind_param($stmt, 'ii', $_POST['id'], $admin_id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo json_encode(['success'=>$ok]);
        exit;
    }
    
    if ($action === 'mark_all_read') {
        $stmt = mysqli_prepare($conn, "UPDATE notifications SET is_read=1 WHERE user_id=? AND is_read=0");
        mysqli_stmt_bind_param($stmt, 'i', $admin_id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo json_encode(['success'=>$ok]);
        exit;
    }
    
    if ($action === 'delete' && isset($_POST['id'])) {
        $stmt = mysqli_prepare($conn, "DELETE FROM notifications WHERE id=? AND user_id=?");
        mysqli_stmt_bind_param($stmt, 'ii', $_POST['id'], $admin_id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo json_encode(['success'=>$ok]);
        exit;
    }
    
    if ($action === 'delete_all') {
        $stmt = mysqli_prepare($conn, "DELETE FROM notifications WHERE user_id=?");
        mysqli_stmt_bind_param($stmt, 'i', $admin_id);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo json_encode(['success'=>$ok]);
        exit;
    }
}

$unread_count = 0;
$res = mysqli_query($conn, "SELECT COUNT(*) AS cnt FROM notifications WHERE user_id=$admin_id AND is_read=0");
if ($res) { $unread_count = (int)mysqli_fetch_assoc($res)['cnt']; }

$search = trim($_GET['q'] ?? '');
$filter = $_GET['filter'] ?? 'all';

$where = ["user_id=$admin_id"];
if ($filter === 'unread') $where[] = "is_read=0";
if ($filter === 'read') $where[] = "is_read=1";
if ($search) {
    $fs = mysqli_real_escape_string($conn, $search);
    $where[] = "(title LIKE '%$fs%' OR message LIKE '%$fs%')";
}

$where_sql = implode(' AND ', $where);
$notifications = [];
$res = mysqli_query($conn, "SELECT * FROM notifications WHERE $where_sql ORDER BY created_at DESC");
if ($res) while ($row = mysqli_fetch_assoc($res)) $notifications[] = $row;

$pageTitle = 'Notifications';
$pageSubtitle = 'All platform alerts and updates';
include 'partials/header.php';
?>
  <div class="row g-3 mb-4">
    <div class="col-12">
      <div class="ds-card">
        <div class="ds-ch">
          <div class="ds-ct"><i class="bi bi-bell-fill"></i> All Notifications
            <span class="badge bg-primary ms-2" style="font-size:.72rem"><?php echo count($notifications); ?></span>
            <span class="badge bg-danger ms-1" style="font-size:.72rem"><?php echo $unread_count; ?> unread</span>
          </div>
          <div class="d-flex gap-2 flex-wrap">
            <form method="GET" class="d-flex gap-2 flex-wrap align-items-center">
              <div class="ds-sw"><i class="bi bi-search ds-si-ic"></i>
                <input class="ds-inp search" name="q" placeholder="Search notifications..." value="<?php echo htmlspecialchars($search); ?>" style="width:220px"/>
              </div>
              <select class="ds-inp ds-sel" name="filter" style="width:auto" onchange="this.form.submit()">
                <option value="all" <?php echo $filter==='all'?'selected':''; ?>>All</option>
                <option value="unread" <?php echo $filter==='unread'?'selected':''; ?>>Unread</option>
                <option value="read" <?php echo $filter==='read'?'selected':''; ?>>Read</option>
              </select>
              <button type="submit" class="ds-btn prim sm"><i class="bi bi-search"></i></button>
              <?php if ($search || $filter !== 'all'): ?>
              <a href="notifications.php" class="ds-btn gho sm">Clear</a>
              <?php endif; ?>
            </form>
            <div class="d-flex gap-2">
              <button class="ds-btn prim sm" onclick="markAllRead()"><i class="bi bi-check2-all me-1"></i>Mark All Read</button>
              <button class="ds-btn gho sm" onclick="deleteAll()"><i class="bi bi-trash3 me-1"></i>Clear All</button>
            </div>
          </div>
        </div>
        <div class="ds-cb">
          <?php if (empty($notifications)): ?>
          <div class="text-center py-5">
            <i class="bi bi-bell-slash" style="font-size:3rem;color:#cbd5e1"></i>
            <div class="fw-700 mt-3" style="color:#64748b">No notifications found</div>
            <div class="text-muted small mt-1"><?php echo $search || $filter !== 'all' ? 'Try adjusting your search or filter.' : 'You\'ll see platform alerts here.'; ?></div>
            <?php if ($search || $filter !== 'all'): ?>
            <a href="notifications.php" class="btn btn-outline-primary btn-sm mt-3">Clear Filters</a>
            <?php endif; ?>
          </div>
          <?php else: ?>
          <div style="overflow-x:auto">
            <table class="ds-tbl" id="notifTable">
              <thead>
                <tr>
                  <th>Status</th>
                  <th>Type</th>
                  <th>Title</th>
                  <th>Description</th>
                  <th>Date & Time</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($notifications as $n): 
                  $iconMap = [
                    'new_user'=>'bi-person-plus-fill',
                    'new_hotel_request'=>'bi-building-add-fill',
                    'hotel_approval_pending'=>'bi-hourglass-split-fill',
                    'new_booking'=>'bi-calendar2-check-fill',
                    'booking_cancelled'=>'bi-calendar-x-fill',
                    'new_review'=>'bi-chat-square-text-fill',
                    'new_rating'=>'bi-star-fill',
                    'manager_assigned'=>'bi-person-badge-fill',
                    'system_alert'=>'bi-gear-fill'
                  ];
                  $icon = $iconMap[$n['type']] ?? 'bi-bell-fill';
                  $colorMap = [
                    'new_user'=>'blue',
                    'new_hotel_request'=>'purple',
                    'hotel_approval_pending'=>'gold',
                    'new_booking'=>'green',
                    'booking_cancelled'=>'red',
                    'new_review'=>'blue',
                    'new_rating'=>'gold',
                    'manager_assigned'=>'green',
                    'system_alert'=>'red'
                  ];
                  $color = $colorMap[$n['type']] ?? 'blue';
                ?>
                <tr id="notif-<?php echo $n['id']; ?>" style="<?php echo $n['is_read'] ? '' : 'background:#f8faff;'; ?>">
                  <td>
                    <span class="ds-badge <?php echo $n['is_read'] ? 'blocked' : 'available'; ?>">
                      <?php echo $n['is_read'] ? 'Read' : 'Unread'; ?>
                    </span>
                  </td>
                  <td>
                    <div class="ds-si" style="width:38px;height:38px;border-radius:10px;background:var(--<?php echo $n['is_read'] ? 'bdr' : ($color==='blue'?'pr-lt':($color==='green'?'grn-lt':($color==='red'?'red-lt':($color==='gold'?'#fef3c7':'var(--purp-lt)')))); ?>);color:var(--<?php echo $color==='blue'?'pr':($color==='green'?'grn':($color==='red'?'red':($color==='gold'?'gold':'purp'))); ?>);display:flex;align-items:center;justify-content:center;font-size:1rem;">
                      <i class="bi <?php echo $icon; ?>"></i>
                    </div>
                  </td>
                  <td class="fw-700 small"><?php echo htmlspecialchars($n['title']); ?></td>
                  <td class="small text-muted" style="max-width:300px"><?php echo htmlspecialchars($n['message'] ?? ''); ?></td>
                  <td class="small fw-600"><?php echo date('d M Y', strtotime($n['created_at'])); ?><br><span class="text-muted" style="font-size:.7rem"><?php echo date('h:i A', strtotime($n['created_at'])); ?></span></td>
                  <td>
                    <div class="d-flex gap-1">
                      <?php if (!$n['is_read']): ?>
                      <button class="ds-btn gho sm ico" onclick="markRead(<?php echo $n['id']; ?>)" title="Mark as Read"><i class="bi bi-check2"></i></button>
                      <?php endif; ?>
                      <button class="ds-btn dng sm ico" onclick="deleteNotif(<?php echo $n['id']; ?>)" title="Delete"><i class="bi bi-trash3"></i></button>
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
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="dashboard.js"></script>
<script>
function markRead(id){
  fetch('notifications.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'action=mark_read&id='+id})
  .then(r=>r.json()).then(d=>{
    if(d.success){
      const row=document.getElementById('notif-'+id);
      if(row){row.style.background='';row.querySelector('.ds-badge').className='ds-badge blocked';row.querySelector('.ds-badge').textContent='Read';
      const btn=row.querySelector('button[title="Mark as Read"]');if(btn)btn.remove();}
      updateBellCount(-1);
    }
  });
}
function markAllRead(){
  fetch('notifications.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'action=mark_all_read'})
  .then(r=>r.json()).then(d=>{
    if(d.success){document.querySelectorAll('#notifTable tbody tr').forEach(row=>{row.style.background='';const badge=row.querySelector('.ds-badge');if(badge){badge.className='ds-badge blocked';badge.textContent='Read';}const btn=row.querySelector('button[title="Mark as Read"]');if(btn)btn.remove();});updateBellCount(0);dsToast('All notifications marked as read','success');}
  });
}
function deleteNotif(id){
  fetch('notifications.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'action=delete&id='+id})
  .then(r=>r.json()).then(d=>{
    if(d.success){const row=document.getElementById('notif-'+id);if(row){row.remove();}updateBellCount(-1);dsToast('Notification deleted','error');}
  });
}
function deleteAll(){
  if(!confirm('Delete all notifications?'))return;
  fetch('notifications.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},body:'action=delete_all'})
  .then(r=>r.json()).then(d=>{
    if(d.success){document.getElementById('notifTable').querySelector('tbody').innerHTML='';updateBellCount(0);dsToast('All notifications cleared','error');}
  });
}
function updateBellCount(delta){
  const el=document.getElementById('bellCount');
  if(!el)return;
  let c=parseInt(el.textContent)||0;
  if(delta===0){c=0;}else{c=Math.max(0,c+delta);}
  el.textContent=c>99?'99+':c;
  if(c<=0){el.style.display='none';}
  const dot=document.querySelector('.ds-dot');
  if(dot){dot.style.display=c>0?'block':'none';}
}
</script>
<?php include 'partials/footer.php'; ?>