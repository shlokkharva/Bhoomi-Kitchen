<?php require_once APPROOT . '/views/inc/header.php'; ?>

<!-- Hero Section -->
<section style="min-height:88vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:2rem;position:relative;z-index:1;">
  <div style="max-width:700px;">
    <div style="font-size:5rem;margin-bottom:1.5rem;animation:float 3s ease-in-out infinite;">🍽️</div>
    <h1 style="font-size:clamp(2rem,5vw,3.5rem);font-weight:800;line-height:1.2;margin-bottom:1.2rem;">
      Dining Reimagined<br><span style="background:linear-gradient(135deg,var(--primary),var(--secondary));-webkit-background-clip:text;-webkit-text-fill-color:transparent;">For the Modern Age</span>
    </h1>
    <p style="color:var(--text-muted);font-size:1.15rem;line-height:1.8;margin-bottom:2.5rem;">
      Browse our interactive menu, place orders seamlessly, track in real-time, and enjoy a premium dining experience.
    </p>
    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
      <a href="<?= URLROOT ?>/orders/menu" class="btn btn-primary btn-lg">🍕 Explore Menu</a>
      <?php if(!isset($_SESSION['user_id'])): ?>
        <a href="<?= URLROOT ?>/users/register" class="btn btn-outline btn-lg">Get Started</a>
      <?php else: ?>
        <a href="<?= URLROOT ?>/orders/track" class="btn btn-secondary btn-lg">📍 Track Order</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Features -->
<section style="padding:4rem 2rem;position:relative;z-index:1;max-width:1200px;margin:0 auto;">
  <h2 style="text-align:center;font-size:2rem;font-weight:800;margin-bottom:.5rem;">Why Choose Us</h2>
  <p style="text-align:center;color:var(--text-muted);margin-bottom:3rem;">Experience dining at its finest</p>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem;">
    <?php
    $features=[
      ['🚀','Instant Orders','Place orders in seconds with our streamlined cart system'],
      ['🤖','Smart Assignment','Automated table & waiter assignment based on your group size'],
      ['📍','Live Tracking','Track your order status in real-time from kitchen to table'],
      ['💳','Easy Payments','Cash, Card or UPI — pay the way you prefer'],
      ['⭐','Tip Waiters','Reward great service directly with custom tip amounts'],
      ['📊','Order History','Review all past orders and spending at a glance'],
    ];
    foreach($features as $f): ?>
    <div class="card" style="text-align:center;">
      <div style="font-size:2.5rem;margin-bottom:1rem;"><?= $f[0] ?></div>
      <h3 style="font-weight:700;margin-bottom:.5rem;"><?= $f[1] ?></h3>
      <p style="color:var(--text-muted);font-size:.88rem;line-height:1.6;"><?= $f[2] ?></p>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Role Cards -->
<section style="padding:4rem 2rem;position:relative;z-index:1;max-width:1200px;margin:0 auto;">
  <h2 style="text-align:center;font-size:2rem;font-weight:800;margin-bottom:3rem;">Portal Access</h2>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:2rem;">
    <div class="card" style="text-align:center;border-color:rgba(249,115,22,.3);">
      <div style="font-size:3rem;margin-bottom:1rem;">👤</div>
      <h3 style="font-weight:700;margin-bottom:.5rem;color:var(--primary);">Customer</h3>
      <p style="color:var(--text-muted);font-size:.88rem;margin-bottom:1.5rem;">Browse menu, place orders, track in real-time.</p>
      <a href="<?= URLROOT ?>/users/login" class="btn btn-primary w-100">Customer Login</a>
    </div>
    <div class="card" style="text-align:center;border-color:rgba(139,92,246,.3);">
      <div style="font-size:3rem;margin-bottom:1rem;">🧑‍🍳</div>
      <h3 style="font-weight:700;margin-bottom:.5rem;color:var(--secondary);">Waiter</h3>
      <p style="color:var(--text-muted);font-size:.88rem;margin-bottom:1.5rem;">Manage assigned tables and update order statuses.</p>
      <a href="<?= URLROOT ?>/waiters/login" class="btn btn-secondary w-100">Waiter Login</a>
    </div>
    <div class="card" style="text-align:center;border-color:rgba(34,197,94,.3);">
      <div style="font-size:3rem;margin-bottom:1rem;">🛠️</div>
      <h3 style="font-weight:700;margin-bottom:.5rem;color:var(--success);">Admin</h3>
      <p style="color:var(--text-muted);font-size:.88rem;margin-bottom:1.5rem;">Full control over menu, staff, and analytics.</p>
      <a href="<?= URLROOT ?>/admins/login" class="btn btn-success w-100">Admin Login</a>
    </div>
  </div>
</section>

<style>
@keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-20px)} }
</style>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>
