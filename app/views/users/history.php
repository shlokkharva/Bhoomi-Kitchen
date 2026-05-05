<?php require_once APPROOT . '/views/inc/header.php'; ?>
<div style="max-width:1000px;margin:0 auto;padding:2rem;position:relative;z-index:1;">
  <h1 style="font-size:2rem;font-weight:800;margin-bottom:.5rem;">📋 Order History</h1>
  <p style="color:var(--text-muted);margin-bottom:2rem;">All your past and current orders</p>

  <?php if(empty($data['orders'])): ?>
  <div class="card" style="text-align:center;padding:3rem;">
    <div style="font-size:3rem;margin-bottom:1rem;">📭</div>
    <h3>No orders yet</h3>
    <p style="color:var(--text-muted);margin:.5rem 0 1.5rem;">Start your dining experience!</p>
    <a href="<?= URLROOT ?>/orders/menu" class="btn btn-primary">Explore Menu</a>
  </div>
  <?php else: ?>
  <div style="display:flex;flex-direction:column;gap:1.2rem;">
    <?php foreach($data['orders'] as $o): ?>
    <div class="card" style="padding:1.2rem 1.5rem;">
      <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.8rem;">
        <div>
          <span style="color:var(--text-muted);font-size:.8rem;">ORDER</span>
          <div style="font-weight:800;font-size:1.1rem;">#<?= $o->id ?></div>
        </div>
        <div>
          <span style="color:var(--text-muted);font-size:.8rem;">TABLE</span>
          <div style="font-weight:600;">Table <?= $o->table_number ?></div>
        </div>
        <div>
          <span style="color:var(--text-muted);font-size:.8rem;">WAITER</span>
          <div style="font-weight:600;"><?= htmlspecialchars($o->waiter_name ?? '-') ?></div>
        </div>
        <div>
          <span style="color:var(--text-muted);font-size:.8rem;">DATE</span>
          <div style="font-weight:600;"><?= date('d M Y, h:i A', strtotime($o->created_at)) ?></div>
        </div>
        <div>
          <span class="badge badge-<?= $o->status ?>"><?= ucfirst($o->status) ?></span>
        </div>
        <div style="text-align:right;">
          <span style="color:var(--text-muted);font-size:.8rem;">TOTAL</span>
          <div style="font-weight:800;color:var(--primary);font-size:1.2rem;">₹<?= number_format($o->total_amount, 2) ?></div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
<?php require_once APPROOT . '/views/inc/footer.php'; ?>
