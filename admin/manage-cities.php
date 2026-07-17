<?php
$pageTitle = 'Manage Cities';
$pageSubtitle = 'Manage operational cities for hotels';
include 'partials/header.php';
?>
<section class="row g-3 mb-3">
  <div class="col-12 col-md-6 col-xl-4"><div class="ds-stat blue"><div class="ds-si"><i class="bi bi-geo-alt-fill"></i></div><div class="ds-sn">24</div><div class="ds-sl">Active Cities</div><div class="ds-tr up"><i class="bi bi-arrow-up-short"></i>+2 new this month</div></div></div>
  <div class="col-12 col-md-6 col-xl-4"><div class="ds-stat green"><div class="ds-si"><i class="bi bi-building"></i></div><div class="ds-sn">4,120</div><div class="ds-sl">Total Hotels Across Cities</div><div class="ds-tr up"><i class="bi bi-arrow-up-short"></i>Growing steadily</div></div></div>
</section>

<div class="ds-card">
  <div class="ds-ch">
    <div class="ds-ct"><i class="bi bi-geo-alt-fill"></i> Manage Cities</div>
    <div class="d-flex flex-wrap gap-2">
      <div class="ds-sw"><i class="bi bi-search ds-si-ic"></i><input class="ds-inp search" placeholder="Search City" style="width:220px" /></div>
      <button class="ds-btn prim sm"><i class="bi bi-plus-lg"></i> Add City</button>
    </div>
  </div>
  <div class="ds-cb">
    <div style="overflow-x:auto">
      <table class="ds-tbl">
        <thead><tr><th>City Name</th><th>Total Hotels</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          <tr><td><div class="fw-700">Mumbai</div></td><td>450</td><td><span class="ds-badge confirmed">Active</span></td><td><button class="ds-btn gho sm">Edit</button> <button class="ds-btn gho sm text-danger">Delete</button></td></tr>
          <tr><td><div class="fw-700">Delhi</div></td><td>380</td><td><span class="ds-badge confirmed">Active</span></td><td><button class="ds-btn gho sm">Edit</button> <button class="ds-btn gho sm text-danger">Delete</button></td></tr>
          <tr><td><div class="fw-700">Pune</div></td><td>210</td><td><span class="ds-badge pending">Inactive</span></td><td><button class="ds-btn gho sm">Edit</button> <button class="ds-btn gho sm text-danger">Delete</button></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include 'partials/footer.php'; ?>
