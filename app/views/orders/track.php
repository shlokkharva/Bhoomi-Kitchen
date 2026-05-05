<?php require_once APPROOT . '/views/inc/header.php'; ?>
<div style="max-width:800px;margin:0 auto;padding:2rem;position:relative;z-index:1;">
  <h1 style="font-size:2rem;font-weight:800;margin-bottom:.5rem;">📍 Order Tracking</h1>
  <p style="color:var(--text-muted);margin-bottom:2rem;">Track your order in real-time</p>

  <?php if($data['active_order']): $o = $data['active_order']; ?>
  <div class="card" style="margin-bottom:2rem;">
    <div style="display:flex;justify-content:space-between;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
      <div>
        <div style="color:var(--text-muted);font-size:.8rem;">ORDER ID</div>
        <div style="font-weight:800;font-size:1.3rem;">#<?= $o->id ?></div>
      </div>
      <div>
        <div style="color:var(--text-muted);font-size:.8rem;">TABLE</div>
        <div style="font-weight:700;">Table <?= $o->table_number ?></div>
      </div>
      <div>
        <div style="color:var(--text-muted);font-size:.8rem;">WAITER</div>
        <div style="font-weight:700;"><?= htmlspecialchars($o->waiter_name ?? 'Assigning...') ?></div>
      </div>
      <div>
        <div style="color:var(--text-muted);font-size:.8rem;">TOTAL</div>
        <div style="font-weight:800;color:var(--primary);font-size:1.2rem;">₹<?= number_format($o->total_amount, 2) ?></div>
      </div>
    </div>

    <!-- Timeline -->
    <div class="order-timeline" id="orderTimeline">
      <?php
      $steps = ['pending'=>'⏳','accepted'=>'✅','preparing'=>'👨‍🍳','serving'=>'🛎️','completed'=>'🎉'];
      $labels = ['pending'=>'Order Placed','accepted'=>'Accepted','preparing'=>'Preparing','serving'=>'On the Way','completed'=>'Completed'];
      $order_steps = array_keys($steps);
      $current_idx = array_search($o->status, $order_steps);
      foreach($steps as $key => $icon):
        $idx = array_search($key, $order_steps);
        $cls = $idx < $current_idx ? 'done' : ($idx === $current_idx ? 'active' : '');
      ?>
      <div class="timeline-step <?= $cls ?>">
        <div class="step-icon"><?= $icon ?></div>
        <div style="font-size:.75rem;color:var(--text-muted);margin-top:.3rem;"><?= $labels[$key] ?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <div style="text-align:center;margin-top:1.5rem;">
      <span class="badge badge-<?= $o->status ?>" style="font-size:1rem;padding:.5rem 1.5rem;" id="statusBadge"><?= ucfirst($o->status) ?></span>
    </div>
  </div>

  <?php if($o->status === 'completed'): ?>
  <div class="card" style="border-color:rgba(34,197,94,.3);text-align:center;">
    <div style="font-size:3rem;margin-bottom:1rem;">🎉</div>
    <h2 style="font-weight:800;color:var(--success);">Order Completed!</h2>
    <p style="color:var(--text-muted);margin:.5rem 0 1.5rem;">We hope you enjoyed your meal.</p>
    <a href="<?= URLROOT ?>/users/history" class="btn btn-success">View Order History</a>
  </div>
  <?php endif; ?>

  <script>
  // AJAX polling for status updates
  const orderId = <?= $o->id ?>;
  const statusClasses = ['pending','accepted','preparing','serving','completed'];
  function pollStatus(){
    fetch('<?= URLROOT ?>/orders/status/'+orderId)
    .then(r=>r.json())
    .then(res=>{
      if(res.status){
        document.getElementById('statusBadge').className = 'badge badge-'+res.status;
        document.getElementById('statusBadge').textContent = res.status.charAt(0).toUpperCase()+res.status.slice(1);
        // update timeline
        const steps = document.querySelectorAll('.timeline-step');
        const idx = statusClasses.indexOf(res.status);
        steps.forEach((s,i)=>{
          s.className='timeline-step';
          if(i<idx) s.classList.add('done');
          else if(i===idx) s.classList.add('active');
        });
        if(res.status==='completed') clearInterval(poller);
      }
    });
  }
  const poller = setInterval(pollStatus, 5000);
  </script>

  <?php else: ?>
  <div class="card" style="text-align:center;padding:4rem 2rem;">
    <div style="font-size:4rem;margin-bottom:1rem;">🍽️</div>
    <h2 style="font-weight:800;margin-bottom:.5rem;">No Active Order</h2>
    <p style="color:var(--text-muted);margin-bottom:1.5rem;">You don't have any active orders at the moment.</p>
    <a href="<?= URLROOT ?>/orders/menu" class="btn btn-primary btn-lg">Browse Menu</a>
  </div>
  <?php endif; ?>
</div>
<?php require_once APPROOT . '/views/inc/footer.php'; ?>
