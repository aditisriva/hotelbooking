<?php
require_once 'auth_guard.php';
$pageTitle = 'Review Moderation';
$pageSubtitle = 'Guest feedback, content quality, and trust signals';
include 'partials/header.php';
?>
<section class="row g-3 mb-3">
  <div class="col-12 col-md-6 col-xl-3"><div class="ds-stat blue"><div class="ds-si"><i class="bi bi-star-fill"></i></div><div class="ds-sn">4.8/5</div><div class="ds-sl">Average Rating</div><div class="ds-tr up"><i class="bi bi-arrow-up-short"></i>+0.1 this month</div></div></div>
  <div class="col-12 col-md-6 col-xl-3"><div class="ds-stat green"><div class="ds-si"><i class="bi bi-chat-dots-fill"></i></div><div class="ds-sn">218</div><div class="ds-sl">Published Reviews</div><div class="ds-tr up"><i class="bi bi-arrow-up-short"></i>steady volume</div></div></div>
  <div class="col-12 col-md-6 col-xl-3"><div class="ds-stat gold"><div class="ds-si"><i class="bi bi-flag-fill"></i></div><div class="ds-sn">12</div><div class="ds-sl">Reported Reviews</div><div class="ds-tr down"><i class="bi bi-arrow-down-short"></i>4 urgent</div></div></div>
  <div class="col-12 col-md-6 col-xl-3"><div class="ds-stat red"><div class="ds-si"><i class="bi bi-slash-circle"></i></div><div class="ds-sn">3</div><div class="ds-sl">Removed</div><div class="ds-tr down"><i class="bi bi-arrow-down-short"></i>Policy violations</div></div></div>
</section>
<div class="ds-card">
  <div class="ds-ch"><div class="ds-ct"><i class="bi bi-star-fill"></i> Review Queue</div><button class="ds-btn prim sm"><i class="bi bi-shield-check"></i> Moderate Reviews</button></div>
  <div class="ds-cb">
    <div style="overflow-x:auto"><table class="ds-tbl"><thead><tr><th>Review</th><th>Hotel</th><th>Rating</th><th>Status</th><th>Actions</th></tr></thead><tbody><tr><td>Excellent service and spotless rooms.</td><td>Blue Peak Retreat</td><td>5 ★</td><td><span class="ds-badge confirmed">Published</span></td><td><button class="ds-btn gho sm">Review</button></td></tr><tr><td>Unprofessional staff and misleading photos.</td><td>Grand Horizon</td><td>1 ★</td><td><span class="ds-badge pending">Reported</span></td><td><button class="ds-btn gho sm">Inspect</button></td></tr></tbody></table></div>
  </div>
</div>
<?php
require_once 'auth_guard.php'; include 'partials/footer.php'; ?>
