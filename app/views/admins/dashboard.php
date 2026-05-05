<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Dashboard | Modern Restaurant</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= URLROOT ?>/css/style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="loader-overlay" id="pageLoader"><div class="loader"></div></div>
<div class="toast-container" id="toastContainer"></div>

<div class="admin-layout">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-logo">
      <a href="<?= URLROOT ?>" style="text-decoration:none;font-size:1.1rem;font-weight:800;color:var(--primary);">🛠️ Admin Panel</a>
      <div style="color:var(--text-muted);font-size:.78rem;margin-top:.3rem;"><?= htmlspecialchars($_SESSION['admin_name']) ?></div>
    </div>
    <ul class="sidebar-nav">
      <li><a href="<?= URLROOT ?>/admins/dashboard" class="active">📊 Dashboard</a></li>
      <li><a href="<?= URLROOT ?>/admins/orders">🧾 Orders</a></li>
      <li><a href="<?= URLROOT ?>/admins/menu">🍕 Menu</a></li>
      <li><a href="<?= URLROOT ?>/admins/users">👤 Users</a></li>
      <li><a href="<?= URLROOT ?>/admins/waiters">🧑‍🍳 Waiters</a></li>
      <li><a href="<?= URLROOT ?>/admins/tables">🪑 Tables</a></li>
      <li style="margin-top:auto;"><a href="<?= URLROOT ?>/admins/logout">🚪 Logout</a></li>
    </ul>
    <div style="padding:1rem 1.5rem;">
      <button class="theme-toggle w-100" onclick="toggleTheme()" id="themeBtn">🌙 Theme</button>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <div class="page-header">
      <h1>Dashboard Overview</h1>
      <p>Welcome back, <?= htmlspecialchars($_SESSION['admin_name']) ?>! Here's what's happening today.</p>
    </div>
    <?php flash('admin_msg'); ?>

    <!-- KPI Stats -->
    <div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(160px,1fr));">
      <div class="card stat-card"><div class="stat-icon">👤</div><div class="stat-value"><?= $data['total_users'] ?></div><div class="stat-label">Total Customers</div></div>
      <div class="card stat-card"><div class="stat-icon">🧑‍🍳</div><div class="stat-value"><?= $data['total_waiters'] ?></div><div class="stat-label">Active Waiters</div></div>
      <div class="card stat-card" style="border-color:rgba(245,158,11,.3)"><div class="stat-icon">⏳</div><div class="stat-value" style="color:var(--warning);"><?= $data['pending_waiters'] ?></div><div class="stat-label">Pending Waiters</div></div>
      <div class="card stat-card"><div class="stat-icon">🧾</div><div class="stat-value"><?= $data['total_orders'] ?></div><div class="stat-label">Total Orders</div></div>
      <div class="card stat-card"><div class="stat-icon">📅</div><div class="stat-value"><?= $data['today_orders'] ?></div><div class="stat-label">Today's Orders</div></div>
      <div class="card stat-card"><div class="stat-icon">💰</div><div class="stat-value" style="font-size:1.4rem;">₹<?= number_format($data['total_revenue'],0) ?></div><div class="stat-label">Total Revenue</div></div>
      <div class="card stat-card"><div class="stat-icon">⭐</div><div class="stat-value" style="font-size:1.4rem;">₹<?= number_format($data['total_tips'],0) ?></div><div class="stat-label">Tips Collected</div></div>
      <div class="card stat-card"><div class="stat-icon">🪑</div><div class="stat-value"><?= $data['available_tables'] ?></div><div class="stat-label">Free Tables</div></div>
    </div>

    <!-- Charts Row -->
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;margin-bottom:2rem;">
      <div class="card chart-card">
        <h3 style="font-weight:700;margin-bottom:1rem;">📈 Daily Revenue (Last 30 Days)</h3>
        <canvas id="revenueChart" height="100"></canvas>
      </div>
      <div class="card chart-card">
        <h3 style="font-weight:700;margin-bottom:1rem;">💳 Revenue by Payment</h3>
        <canvas id="payChart" height="180"></canvas>
      </div>
    </div>

    <!-- Monthly Revenue Chart -->
    <div class="card chart-card" style="margin-bottom:2rem;">
      <h3 style="font-weight:700;margin-bottom:1rem;">📊 Monthly Revenue</h3>
      <canvas id="monthlyChart" height="60"></canvas>
    </div>

    <!-- Recent Orders -->
    <div class="card">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
        <h3 style="font-weight:700;">🕐 Recent Orders</h3>
        <a href="<?= URLROOT ?>/admins/orders" class="btn btn-outline btn-sm">View All</a>
      </div>
      <div class="table-wrapper">
        <table>
          <thead><tr><th>ID</th><th>Customer</th><th>Waiter</th><th>Table</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
          <tbody>
          <?php foreach($data['recent_orders'] as $o): ?>
          <tr>
            <td><strong>#<?= $o->id ?></strong></td>
            <td><?= htmlspecialchars($o->customer_name) ?></td>
            <td><?= htmlspecialchars($o->waiter_name ?? '-') ?></td>
            <td>Table <?= $o->table_number ?></td>
            <td style="color:var(--primary);font-weight:700;">₹<?= number_format($o->total_amount,2) ?></td>
            <td><span class="badge badge-<?= $o->status ?>"><?= ucfirst($o->status) ?></span></td>
            <td style="color:var(--text-muted);font-size:.82rem;"><?= date('d M, h:i A',strtotime($o->created_at)) ?></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>

<script>
// Chart.js defaults
Chart.defaults.color = '#94a3b8';
Chart.defaults.borderColor = 'rgba(255,255,255,0.07)';

// Daily Revenue
const dailyData = <?= json_encode(array_reverse($data['daily_revenue'])) ?>;
new Chart(document.getElementById('revenueChart'), {
  type:'line',
  data:{
    labels: dailyData.map(d=>d.date),
    datasets:[{
      label:'Revenue (₹)',
      data: dailyData.map(d=>parseFloat(d.revenue)||0),
      borderColor:'#f97316', backgroundColor:'rgba(249,115,22,0.15)',
      tension:.4, fill:true, pointRadius:3, pointHoverRadius:6
    }]
  },
  options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
});

// Payment Breakdown
const payData = <?= json_encode($data['payment_breakdown']) ?>;
new Chart(document.getElementById('payChart'), {
  type:'doughnut',
  data:{
    labels: payData.map(p=>p.method.charAt(0).toUpperCase()+p.method.slice(1)),
    datasets:[{
      data: payData.map(p=>parseFloat(p.total)||0),
      backgroundColor:['#f97316','#8b5cf6','#38bdf8'],
      borderWidth:0
    }]
  },
  options:{responsive:true,plugins:{legend:{position:'bottom'}}}
});

// Monthly Revenue
const monthlyData = <?= json_encode(array_reverse($data['monthly_revenue'])) ?>;
new Chart(document.getElementById('monthlyChart'), {
  type:'bar',
  data:{
    labels: monthlyData.map(m=>m.month),
    datasets:[{
      label:'Revenue (₹)',
      data: monthlyData.map(m=>parseFloat(m.total)||0),
      backgroundColor:'rgba(139,92,246,0.7)',
      borderRadius:6
    }]
  },
  options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}
});

function toggleTheme(){const h=document.documentElement;const d=h.getAttribute('data-theme')==='dark';h.setAttribute('data-theme',d?'light':'dark');localStorage.setItem('theme',d?'light':'dark');}
(function(){const s=localStorage.getItem('theme')||'dark';document.documentElement.setAttribute('data-theme',s);})();
window.addEventListener('load',()=>{const l=document.getElementById('pageLoader');if(l){l.classList.add('hidden');setTimeout(()=>l.remove(),600);}});
function showToast(msg,type='success'){const c=document.getElementById('toastContainer');const t=document.createElement('div');t.className='toast toast-'+type;t.textContent=msg;c.appendChild(t);setTimeout(()=>t.remove(),4200);}
</script>
</body></html>
