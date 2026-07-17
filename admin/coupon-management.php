<?php
$pageTitle = 'Coupon Management';
$pageSubtitle = 'Manage promotional coupons and discounts';
include 'partials/header.php';
?>
<section class="row g-3 mb-3">
  <div class="col-12 col-md-6 col-xl-3"><div class="ds-stat gold"><div class="ds-si"><i class="bi bi-tags-fill"></i></div><div class="ds-sn">12</div><div class="ds-sl">Active Coupons</div></div></div>
  <div class="col-12 col-md-6 col-xl-3"><div class="ds-stat green"><div class="ds-si"><i class="bi bi-check-circle-fill"></i></div><div class="ds-sn">1,450</div><div class="ds-sl">Coupons Used</div></div></div>
</section>

<div class="ds-card">
  <div class="ds-ch">
    <div class="ds-ct"><i class="bi bi-tags-fill"></i> Coupon Management</div>
    <div class="d-flex flex-wrap gap-2">
      <div class="ds-sw"><i class="bi bi-search ds-si-ic"></i><input class="ds-inp search" placeholder="Search code" style="width:200px" /></div>
      <button class="ds-btn prim sm"><i class="bi bi-plus-lg"></i> Create Coupon</button>
    </div>
  </div>
  <div class="ds-cb">
    <div style="overflow-x:auto">
      <table class="ds-tbl">
        <thead><tr><th>Coupon Code</th><th>Discount Type</th><th>Discount Value</th><th>Usage Limit</th><th>Expiry Date</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          <tr><td><div class="fw-700">SUMMER25</div></td><td>Percentage (%)</td><td>25%</td><td>500 / 1000</td><td>31 Aug 2026</td><td><span class="ds-badge confirmed">Active</span></td><td><button class="ds-btn gho sm">Edit</button> <button class="ds-btn gho sm text-danger">Delete</button></td></tr>
          <tr><td><div class="fw-700">FLAT500</div></td><td>Fixed Amount</td><td>₹500</td><td>120 / 200</td><td>15 Jul 2026</td><td><span class="ds-badge confirmed">Active</span></td><td><button class="ds-btn gho sm">Edit</button> <button class="ds-btn gho sm text-danger">Delete</button></td></tr>
          <tr><td><div class="fw-700">WELCOME10</div></td><td>Percentage (%)</td><td>10%</td><td>Unlimited</td><td>-</td><td><span class="ds-badge pending">Inactive</span></td><td><button class="ds-btn gho sm">Edit</button> <button class="ds-btn gho sm text-danger">Delete</button></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include 'partials/footer.php'; ?>
