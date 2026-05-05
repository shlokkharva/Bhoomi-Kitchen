<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Waiter Dashboard | Modern Restaurant</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= URLROOT ?>/css/style.css">
</head>
<body>
<div class="toast-container" id="toastContainer"></div>


<div class="admin-layout">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-logo">
      <a href="<?= URLROOT ?>" style="text-decoration:none;font-size:1.2rem;font-weight:800;color:var(--secondary);">🧑‍🍳 Waiter Panel</a>
      <div style="color:var(--text-muted);font-size:.8rem;margin-top:.3rem;"><?= htmlspecialchars($_SESSION['waiter_name']) ?></div>
    </div>
    <ul class="sidebar-nav">
      <li><a href="<?= URLROOT ?>/waiters/dashboard" class="active">📊 Dashboard</a></li>
      <li><a href="<?= URLROOT ?>/waiters/logout">🚪 Logout</a></li>
    </ul>
    <div style="padding:1.5rem;margin-top:auto;">
      <button class="theme-toggle w-100" onclick="toggleTheme()" id="themeBtn">🌙 Toggle Theme</button>
    </div>
  </aside>

  <!-- Main -->
  <main class="main-content">
    <div class="page-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
      <div>
        <h1>Welcome, <?= htmlspecialchars($_SESSION['waiter_name']) ?> 👋</h1>
        <p>Manage your assigned tables and orders</p>
      </div>
      <div style="font-size:.85rem;color:var(--text-muted);"><?= date('l, d F Y') ?></div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
      <div class="card stat-card">
        <div class="stat-icon">📦</div>
        <div class="stat-value"><?= count($data['active_orders']) ?></div>
        <div class="stat-label">Active Orders</div>
      </div>
      <div class="card stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-value"><?= count($data['completed_orders']) ?></div>
        <div class="stat-label">Completed Today</div>
      </div>
      <div class="card stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-value">₹<?= number_format($data['earnings']->total_tips ?? 0, 2) ?></div>
        <div class="stat-label">Total Tips Earned</div>
      </div>
      <div class="card stat-card">
        <div class="stat-icon">🍽️</div>
        <div class="stat-value"><?= $data['earnings']->total_orders ?? 0 ?></div>
        <div class="stat-label">Orders Served</div>
      </div>
    </div>

    <!-- Active Orders -->
    <h2 style="font-size:1.3rem;font-weight:700;margin-bottom:1rem;">🔥 Active Orders</h2>
    <?php if(empty($data['active_orders'])): ?>
    <div class="card" style="text-align:center;padding:3rem;">
      <div style="font-size:3rem;margin-bottom:.8rem;">😴</div>
      <p style="color:var(--text-muted);">No active orders right now. Enjoy the break!</p>
    </div>
    <?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:1.2rem;margin-bottom:2rem;">
      <?php foreach($data['active_orders'] as $order): ?>
      <div class="card" id="order-<?= $order->id ?>" style="border-left:3px solid var(--primary);">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem;">
          <div>
            <div style="font-weight:800;font-size:1.1rem;">Order #<?= $order->id ?></div>
            <div style="color:var(--text-muted);font-size:.82rem;"><?= date('h:i A', strtotime($order->created_at)) ?></div>
          </div>
          <span class="badge badge-<?= $order->status ?>"><?= ucfirst($order->status) ?></span>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.5rem;margin-bottom:1rem;font-size:.88rem;">
          <div><span style="color:var(--text-muted);">Customer</span><br><strong><?= htmlspecialchars($order->customer_name) ?></strong></div>
          <div><span style="color:var(--text-muted);">Table</span><br><strong>Table <?= $order->table_number ?></strong></div>
          <div><span style="color:var(--text-muted);">Total</span><br><strong style="color:var(--primary);">₹<?= number_format($order->total_amount, 2) ?></strong></div>
          <div><span style="color:var(--text-muted);">Tip</span><br><strong style="color:var(--success);">₹<?= number_format($order->tip_amount, 2) ?></strong></div>
        </div>
        <!-- Status Update Buttons -->
        <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
          <?php
          $transitions = [
            'pending'   => ['accepted','Accepted'],
            'accepted'  => ['preparing','Preparing'],
            'preparing' => ['serving','Serving'],
            'serving'   => ['completed','Completed'],
          ];
          if(isset($transitions[$order->status])):
            [$nextStatus, $nextLabel] = $transitions[$order->status];
          ?>
          <button class="btn btn-primary btn-sm" onclick="updateStatus(<?= $order->id ?>, '<?= $nextStatus ?>', this)">
            Mark <?= $nextLabel ?>
          </button>
          <?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Completed Orders -->
    <?php if(!empty($data['completed_orders'])): ?>
    <h2 style="font-size:1.3rem;font-weight:700;margin-bottom:1rem;">✅ Completed Orders</h2>
    <div class="card">
      <div class="table-wrapper">
        <table>
          <thead><tr><th>Order</th><th>Customer</th><th>Table</th><th>Total</th><th>Tip</th><th>Time</th></tr></thead>
          <tbody>
          <?php foreach(array_slice($data['completed_orders'], 0, 10) as $o): ?>
          <tr>
            <td><strong>#<?= $o->id ?></strong></td>
            <td><?= htmlspecialchars($o->customer_name) ?></td>
            <td>Table <?= $o->table_number ?></td>
            <td style="color:var(--primary);font-weight:700;">₹<?= number_format($o->total_amount,2) ?></td>
            <td style="color:var(--success);">₹<?= number_format($o->tip_amount,2) ?></td>
            <td style="color:var(--text-muted);"><?= date('d M, h:i A', strtotime($o->created_at)) ?></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>
  </main>
</div>

<script>
function updateStatus(orderId, status, btn){
  btn.disabled=true; btn.textContent='Updating...';
  fetch('<?= URLROOT ?>/waiters/updateStatus',{
    method:'POST',
    headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body: 'order_id='+orderId+'&status='+status
  })
  .then(r=>r.json())
  .then(res=>{
    if(res.success){
      showToast('Order #'+orderId+' marked as '+status,'success');
      setTimeout(()=>location.reload(), 1200);
    } else {
      showToast(res.msg||'Error updating status','danger');
      btn.disabled=false;
    }
  });
}

// Theme
function toggleTheme(){
  const html=document.documentElement;
  const isDark=html.getAttribute('data-theme')==='dark';
  html.setAttribute('data-theme',isDark?'light':'dark');
  localStorage.setItem('theme',isDark?'light':'dark');
}
(function(){const s=localStorage.getItem('theme')||'dark';document.documentElement.setAttribute('data-theme',s);})();

function showToast(msg,type='success'){const c=document.getElementById('toastContainer');const t=document.createElement('div');t.className='toast toast-'+type;t.textContent=msg;c.appendChild(t);setTimeout(()=>t.remove(),4200);}
</script>
</body>
</html>
