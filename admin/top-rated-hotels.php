<?php
$pageTitle = 'Top Rated Hotels';
$pageSubtitle = 'View and manage top rated hotels';
include 'partials/header.php';
?>
<div class="ds-card">
  <div class="ds-ch">
    <div class="ds-ct"><i class="bi bi-award-fill"></i> Top Rated Hotels Ranking</div>
    <div class="d-flex flex-wrap gap-2">
      <select class="ds-inp" style="width:150px"><option>All Cities</option><option>Mumbai</option><option>Delhi</option></select>
    </div>
  </div>
  <div class="ds-cb">
    <div style="overflow-x:auto">
      <table class="ds-tbl">
        <thead><tr><th>Rank</th><th>Hotel</th><th>City</th><th>Average Rating</th><th>Total Reviews</th><th>Actions</th></tr></thead>
        <tbody>
          <tr><td><div class="fw-700 fs-5 text-warning">#1</div></td><td><div class="d-flex align-items-center gap-2"><div style="width:40px;height:40px;border-radius:8px;background:#e2e8f0;display:flex;align-items:center;justify-content:center"><i class="bi bi-image text-muted"></i></div> <div class="fw-700">The Grand Taj</div></div></td><td>Mumbai</td><td><div class="d-flex align-items-center gap-1 text-warning fw-600"><i class="bi bi-star-fill"></i> 4.9</div></td><td>1,245</td><td><button class="ds-btn outl sm">View Details</button></td></tr>
          <tr><td><div class="fw-700 fs-5 text-secondary">#2</div></td><td><div class="d-flex align-items-center gap-2"><div style="width:40px;height:40px;border-radius:8px;background:#e2e8f0;display:flex;align-items:center;justify-content:center"><i class="bi bi-image text-muted"></i></div> <div class="fw-700">Oberoi Splendor</div></div></td><td>Delhi</td><td><div class="d-flex align-items-center gap-1 text-warning fw-600"><i class="bi bi-star-fill"></i> 4.8</div></td><td>980</td><td><button class="ds-btn outl sm">View Details</button></td></tr>
          <tr><td><div class="fw-700 fs-5" style="color:#cd7f32">#3</div></td><td><div class="d-flex align-items-center gap-2"><div style="width:40px;height:40px;border-radius:8px;background:#e2e8f0;display:flex;align-items:center;justify-content:center"><i class="bi bi-image text-muted"></i></div> <div class="fw-700">Marriott Riverside</div></div></td><td>Pune</td><td><div class="d-flex align-items-center gap-1 text-warning fw-600"><i class="bi bi-star-fill"></i> 4.7</div></td><td>842</td><td><button class="ds-btn outl sm">View Details</button></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include 'partials/footer.php'; ?>
