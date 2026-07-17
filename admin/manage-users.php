<?php
$pageTitle = 'User Management';
$pageSubtitle = 'Customer accounts, account health, and platform access';
include 'partials/header.php';
?>
<section class="row g-3 mb-3">
  <div class="col-12 col-md-6 col-xl-3"><div class="ds-stat blue"><div class="ds-si"><i class="bi bi-people-fill"></i></div><div class="ds-sn">3,248</div><div class="ds-sl">Registered Users</div><div class="ds-tr up"><i class="bi bi-arrow-up-short"></i>+12.4% this month</div></div></div>
  <div class="col-12 col-md-6 col-xl-3"><div class="ds-stat green"><div class="ds-si"><i class="bi bi-person-check-fill"></i></div><div class="ds-sn">2,910</div><div class="ds-sl">Active Accounts</div><div class="ds-tr up"><i class="bi bi-arrow-up-short"></i>89.6% healthy</div></div></div>
  <div class="col-12 col-md-6 col-xl-3"><div class="ds-stat gold"><div class="ds-si"><i class="bi bi-person-plus-fill"></i></div><div class="ds-sn">124</div><div class="ds-sl">New This Week</div><div class="ds-tr up"><i class="bi bi-arrow-up-short"></i>Steady growth</div></div></div>
  <div class="col-12 col-md-6 col-xl-3"><div class="ds-stat red"><div class="ds-si"><i class="bi bi-shield-exclamation"></i></div><div class="ds-sn">18</div><div class="ds-sl">Flagged Accounts</div><div class="ds-tr down"><i class="bi bi-arrow-down-short"></i>3 urgent reviews</div></div></div>
</section>
<div class="ds-card">
  <div class="ds-ch">
    <div class="ds-ct"><i class="bi bi-people-fill"></i> Customer Accounts</div>
    <div class="d-flex flex-wrap gap-2">
      <div class="ds-sw"><i class="bi bi-search ds-si-ic"></i><input class="ds-inp search" placeholder="Search users" style="width:220px" /></div>
      <button class="ds-btn outl sm"><i class="bi bi-funnel-fill"></i> Filter</button>
      <button class="ds-btn prim sm"><i class="bi bi-person-plus-fill"></i> Add User</button>
    </div>
  </div>
  <div class="ds-cb">
    <div style="overflow-x:auto">
      <table class="ds-tbl">
        <thead><tr><th>User</th><th>Email</th><th>Role</th><th>Status</th><th>Joined</th><th>Actions</th></tr></thead>
        <tbody>
          <tr><td><div class="fw-700">Ravi Mehta</div><div class="small text-muted">Traveler</div></td><td>ravi@example.com</td><td>Customer</td><td><span class="ds-badge confirmed">Active</span></td><td>12 Jun 2026</td><td><button class="ds-btn gho sm">View</button></td></tr>
          <tr><td><div class="fw-700">Neha Rao</div><div class="small text-muted">Traveler</div></td><td>neha@example.com</td><td>Customer</td><td><span class="ds-badge pending">Pending</span></td><td>10 Jun 2026</td><td><button class="ds-btn gho sm">Inspect</button></td></tr>
          <tr><td><div class="fw-700">Aman Verma</div><div class="small text-muted">Traveler</div></td><td>aman@example.com</td><td>Customer</td><td><span class="ds-badge confirmed">Active</span></td><td>01 Jun 2026</td><td><button class="ds-btn gho sm">View</button></td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include 'partials/footer.php'; ?>