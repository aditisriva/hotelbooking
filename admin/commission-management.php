<?php
$pageTitle = 'Commission Management';
$pageSubtitle = 'Manage platform commissions and payouts';
include 'partials/header.php';
?>
<section class="row g-3 mb-3">
  <div class="col-12 col-md-6 col-xl-4"><div class="ds-stat blue"><div class="ds-si"><i class="bi bi-graph-up-arrow"></i></div><div class="ds-sn">$145,000</div><div class="ds-sl">Platform Revenue (YTD)</div></div></div>
  <div class="col-12 col-md-6 col-xl-4"><div class="ds-stat green"><div class="ds-si"><i class="bi bi-cash-stack"></i></div><div class="ds-sn">$18,500</div><div class="ds-sl">Total Commission Earned</div></div></div>
  <div class="col-12 col-md-6 col-xl-4"><div class="ds-stat gold"><div class="ds-si"><i class="bi bi-percent"></i></div><div class="ds-sn">12.5%</div><div class="ds-sl">Avg Active Commission Rate</div></div></div>
</section>

<div class="ds-card">
  <div class="ds-ch">
    <div class="ds-ct"><i class="bi bi-wallet-fill"></i> Commission Setup & History</div>
    <div class="d-flex flex-wrap gap-2">
      <div class="ds-sw"><i class="bi bi-search ds-si-ic"></i><input class="ds-inp search" placeholder="Search Hotel" style="width:200px" /></div>
    </div>
  </div>
  <div class="ds-cb">
    <div style="overflow-x:auto">
      <table class="ds-tbl">
        <thead><tr><th>Hotel Name</th><th>Revenue Generated</th><th>Commission %</th><th>Commission Amount</th><th>Actions</th></tr></thead>
        <tbody>
          <tr><td><div class="fw-700">The Grand Taj</div></td><td>$45,000</td><td>15%</td><td><div class="fw-600 text-success">$6,750</div></td><td><button class="ds-btn outl sm" data-bs-toggle="modal" data-bs-target="#editCommModal">Edit %</button></td></tr>
          <tr><td><div class="fw-700">Oberoi Splendor</div></td><td>$32,000</td><td>12%</td><td><div class="fw-600 text-success">$3,840</div></td><td><button class="ds-btn outl sm" data-bs-toggle="modal" data-bs-target="#editCommModal">Edit %</button></td></tr>
          <tr><td><div class="fw-700">Marriott Riverside</div></td><td>$28,000</td><td>10%</td><td><div class="fw-600 text-success">$2,800</div></td><td><button class="ds-btn outl sm" data-bs-toggle="modal" data-bs-target="#editCommModal">Edit %</button></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Edit Commission Modal -->
<div class="modal fade" id="editCommModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border:none; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.1);">
      <div class="modal-header border-bottom-0 pb-0">
        <h5 class="modal-title fw-700">Update Commission</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label text-muted small fw-600">Hotel Name</label>
          <input type="text" class="ds-inp w-100" value="The Grand Taj" disabled />
        </div>
        <div class="mb-3">
          <label class="form-label text-muted small fw-600">Commission Percentage (%)</label>
          <input type="number" class="ds-inp w-100" value="15" min="0" max="100" />
        </div>
      </div>
      <div class="modal-footer border-top-0 pt-0">
        <button type="button" class="ds-btn gho" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="ds-btn prim">Save Changes</button>
      </div>
    </div>
  </div>
</div>
<?php include 'partials/footer.php'; ?>
