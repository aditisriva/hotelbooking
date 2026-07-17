<?php
require_once '../hotel/db.php';
require_once '../hotel/hotel_functions.php';

// Handle approval actions
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $hid    = (int)($_POST['hotel_id']??0);
    $action = $_POST['action'];
    if ($hid && in_array($action,['approve','reject','pending'])) {
        $status_map = ['approve'=>'approved','reject'=>'rejected','pending'=>'pending'];
        $new_status = $status_map[$action];
        $stmt = mysqli_prepare($conn,"UPDATE hotels SET approval_status=? WHERE hotel_id=?");
        mysqli_stmt_bind_param($stmt,'si',$new_status,$hid);
        $ok = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo json_encode(['success'=>$ok,'status'=>$new_status]);
    } else echo json_encode(['success'=>false]);
    exit;
}

// Stats
$total  = (int)($conn ? mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM hotels"))['c'] : 0);
$approved=(int)($conn ? mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM hotels WHERE approval_status='approved'"))['c'] : 0);
$pending =(int)($conn ? mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM hotels WHERE approval_status='pending'"))['c'] : 0);
$rejected=(int)($conn ? mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM hotels WHERE approval_status='rejected'"))['c'] : 0);

// Filters
$fapp = isset($_GET['approval']) ? $_GET['approval'] : '';
$fs   = isset($_GET['q']) ? trim($_GET['q']) : '';
$where=['1=1'];
if ($fapp) $where[]="approval_status='".mysqli_real_escape_string($conn,$fapp)."'";
if ($fs) { $s=mysqli_real_escape_string($conn,$fs); $where[]="(hotel_name LIKE '%$s%' OR city LIKE '%$s%')"; }
$hotels_list=[];
$res=mysqli_query($conn,"SELECT * FROM hotels WHERE ".implode(' AND ',$where)." ORDER BY created_at DESC");
if($res) while($row=mysqli_fetch_assoc($res)) $hotels_list[]=$row;

$pageTitle='Hotel Management';
$pageSubtitle='Property listings, approvals, and platform quality · Live DB';
include 'partials/header.php';
?>
<section class="row g-3 mb-3">
  <div class="col-12 col-md-6 col-xl-3"><div class="ds-stat blue"><div class="ds-si"><i class="bi bi-buildings"></i></div><div class="ds-sn"><?php echo $total;?></div><div class="ds-sl">Total Hotels</div></div></div>
  <div class="col-12 col-md-6 col-xl-3"><div class="ds-stat green"><div class="ds-si"><i class="bi bi-check-circle-fill"></i></div><div class="ds-sn"><?php echo $approved;?></div><div class="ds-sl">Approved</div></div></div>
  <div class="col-12 col-md-6 col-xl-3"><div class="ds-stat gold"><div class="ds-si"><i class="bi bi-hourglass-split"></i></div><div class="ds-sn"><?php echo $pending;?></div><div class="ds-sl">Pending Review</div></div></div>
  <div class="col-12 col-md-6 col-xl-3"><div class="ds-stat red"><div class="ds-si"><i class="bi bi-x-circle-fill"></i></div><div class="ds-sn"><?php echo $rejected;?></div><div class="ds-sl">Rejected</div></div></div>
</section>

<div class="ds-card">
  <div class="ds-ch">
    <div class="ds-ct"><i class="bi bi-buildings me-2"></i>Hotel Listings (<?php echo count($hotels_list);?>)</div>
    <div class="d-flex flex-wrap gap-2">
      <form method="GET" class="d-flex gap-2 flex-wrap">
        <div class="ds-sw"><i class="bi bi-search ds-si-ic"></i>
          <input class="ds-inp search" name="q" placeholder="Search hotels..." value="<?php echo htmlspecialchars($fs);?>" style="width:180px"/>
        </div>
        <select class="ds-inp ds-sel" name="approval" style="width:150px" onchange="this.form.submit()">
          <option value="">All</option>
          <option value="approved" <?php echo $fapp==='approved'?'selected':'';?>>Approved</option>
          <option value="pending"  <?php echo $fapp==='pending'?'selected':'';?>>Pending</option>
          <option value="rejected" <?php echo $fapp==='rejected'?'selected':'';?>>Rejected</option>
        </select>
        <button type="submit" class="ds-btn prim sm"><i class="bi bi-search"></i></button>
        <a href="hotels.php" class="ds-btn gho sm">Clear</a>
      </form>
    </div>
  </div>
  <div class="ds-cb p-0">
    <?php if(empty($hotels_list)): ?>
    <div class="text-center py-5 text-muted"><i class="bi bi-buildings" style="font-size:3rem;opacity:.3"></i>
      <div class="fw-700 mt-3">No hotels found</div></div>
    <?php else: ?>
    <div style="overflow-x:auto">
      <table class="ds-tbl">
        <thead><tr><th>Hotel</th><th>City</th><th>Price/Night</th><th>Rating</th><th>Status</th><th>Approval</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach($hotels_list as $h):
          $img = bhFirstImage($h['hotel_images']??'','https://images.unsplash.com/photo-1566073771259-6a8506099945?w=80&q=60');
          $apSt= $h['approval_status']??'approved';
          $apColor=['approved'=>'confirmed','pending'=>'pending','rejected'=>'cancelled'];
        ?>
          <tr id="hotelRow<?php echo $h['hotel_id'];?>">
            <td>
              <div class="d-flex align-items-center gap-2">
                <img src="<?php echo htmlspecialchars($img);?>" style="width:50px;height:38px;object-fit:cover;border-radius:6px" alt=""
                     onerror="this.src='https://images.unsplash.com/photo-1566073771259-6a8506099945?w=80&q=60'"/>
                <div>
                  <div class="fw-700 small"><?php echo htmlspecialchars($h['hotel_name']);?></div>
                  <div style="font-size:.72rem;color:#64748b"><?php echo htmlspecialchars($h['property_type']);?></div>
                </div>
              </div>
            </td>
            <td class="small"><?php echo htmlspecialchars(ucfirst($h['city']));?></td>
            <td class="fw-700 small">₹<?php echo number_format($h['price_per_night']);?></td>
            <td><span class="badge bg-warning text-dark"><?php echo $h['rating'];?> ★</span></td>
            <td><span class="ds-badge <?php echo $h['availability_status']==='active'?'confirmed':'pending';?>"><?php echo ucfirst($h['availability_status']);?></span></td>
            <td id="ap<?php echo $h['hotel_id'];?>">
              <span class="ds-badge <?php echo $apColor[$apSt]??'pending';?>"><?php echo ucfirst($apSt);?></span>
            </td>
            <td>
              <?php if($apSt==='pending' || $apSt==='rejected'): ?>
              <button class="ds-btn prim sm me-1" onclick="approveHotel(<?php echo $h['hotel_id'];?>,'approve')">
                <i class="bi bi-check-lg"></i> Approve
              </button>
              <?php endif; ?>
              <?php if($apSt==='pending' || $apSt==='approved'): ?>
              <button class="ds-btn sm me-1" style="background:#ef4444;color:#fff" onclick="approveHotel(<?php echo $h['hotel_id'];?>,'reject')">
                <i class="bi bi-x-lg"></i> Reject
              </button>
              <?php endif; ?>
              <a href="../hotel/admin-hotel-profile.php?edit=<?php echo $h['hotel_id'];?>" class="ds-btn gho sm">
                <i class="bi bi-pencil-fill"></i>
              </a>
            </td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>
    </div>
    <?php endif;?>
  </div>
</div>

<script>
function approveHotel(id, action) {
  fetch('hotels.php', {
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:'action='+action+'&hotel_id='+id
  })
  .then(r=>r.json())
  .then(d=>{
    if(d.success) {
      const colors={approved:'confirmed',rejected:'cancelled',pending:'pending'};
      document.getElementById('ap'+id).innerHTML=
        '<span class="ds-badge '+colors[d.status]+'">'+d.status.charAt(0).toUpperCase()+d.status.slice(1)+'</span>';
      dsToast('Hotel '+(action==='approve'?'approved':'rejected')+' successfully!', d.success?'success':'error');
    }
  });
}
</script>
<?php include 'partials/footer.php'; ?>
