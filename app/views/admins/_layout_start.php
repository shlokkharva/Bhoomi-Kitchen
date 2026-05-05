<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin | Modern Restaurant</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= URLROOT ?>/css/style.css">
<script>
(function(){const s=localStorage.getItem('theme')||'dark';document.documentElement.setAttribute('data-theme',s);})();
function showToast(msg,type='success'){
  const c=document.getElementById('toastContainer');
  if(!c) return;
  const t=document.createElement('div');
  t.className='toast toast-'+type;
  t.textContent=msg;
  c.appendChild(t);
  setTimeout(()=>t.remove(),4200);
}

function openConfirmModal(formId, message = 'Are you sure you want to delete this?') {
  document.getElementById('confirmModalMsg').textContent = message;
  document.getElementById('confirmModal').style.display = 'flex';
  window._pendingFormId = formId;
}
function closeConfirmModal() {
  document.getElementById('confirmModal').style.display = 'none';
  window._pendingFormId = null;
}
function executePendingDelete() {
  if(window._pendingFormId) {
    document.getElementById(window._pendingFormId).submit();
  }
  closeConfirmModal();
}
</script>
</head>
<body>
<!-- Custom Confirm Modal -->
<div id="confirmModal" style="display:none;position:fixed;inset:0;z-index:10001;background:rgba(0,0,0,.7);backdrop-filter:blur(8px);align-items:center;justify-content:center;padding:1rem;">
  <div class="card" style="width:100%;max-width:400px;text-align:center;padding:2.5rem;box-shadow:0 20px 50px rgba(0,0,0,.5);border-color:var(--glass-border);">
    <div style="font-size:3.5rem;margin-bottom:1rem;">⚠️</div>
    <h2 style="font-weight:800;margin-bottom:.5rem;">Confirm Delete</h2>
    <p id="confirmModalMsg" style="color:var(--text-muted);margin-bottom:2rem;line-height:1.6;">Are you sure you want to proceed?</p>
    <div style="display:flex;gap:1rem;">
      <button class="btn btn-outline w-100" onclick="closeConfirmModal()">Cancel</button>
      <button class="btn btn-danger w-100" onclick="executePendingDelete()">Yes, Delete</button>
    </div>
  </div>
</div>

<div class="toast-container" id="toastContainer"></div>

<div class="admin-layout">

  <aside class="sidebar">
    <div class="sidebar-logo">
      <a href="<?= URLROOT ?>" style="text-decoration:none;font-size:1.1rem;font-weight:800;color:var(--primary);">🛠️ Admin Panel</a>
      <div style="color:var(--text-muted);font-size:.78rem;margin-top:.3rem;"><?= htmlspecialchars($_SESSION['admin_name'] ?? '') ?></div>
    </div>
    <ul class="sidebar-nav">
      <li><a href="<?= URLROOT ?>/admins/dashboard">📊 Dashboard</a></li>
      <li><a href="<?= URLROOT ?>/admins/orders">🧾 Orders</a></li>
      <li><a href="<?= URLROOT ?>/admins/menu">🍕 Menu</a></li>
      <li><a href="<?= URLROOT ?>/admins/users">👤 Users</a></li>
      <li><a href="<?= URLROOT ?>/admins/waiters">🧑‍🍳 Waiters</a></li>
      <li><a href="<?= URLROOT ?>/admins/tables">🪑 Tables</a></li>
      <li style="margin-top:1rem;"><a href="<?= URLROOT ?>/admins/logout">🚪 Logout</a></li>
    </ul>
    <div style="padding:1rem 1.5rem;">
      <button class="theme-toggle w-100" onclick="toggleTheme()" id="themeBtn">🌙 Theme</button>
    </div>
  </aside>
  <main class="main-content">
