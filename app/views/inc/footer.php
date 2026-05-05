<footer style="text-align:center;padding:2rem;color:var(--text-muted);font-size:.85rem;border-top:1px solid var(--border);margin-top:4rem;position:relative;z-index:1;">
  <p>© <?= date('Y') ?> Bhoomi's Kitchen Management System. All rights reserved.</p>
</footer>



<!-- Auth Modal (shown on checkout if not logged in) -->
<div id="authModal" style="display:none;position:fixed;inset:0;z-index:10000;background:rgba(0,0,0,.7);backdrop-filter:blur(6px);align-items:center;justify-content:center;">
  <div style="background:var(--bg-dark);border:1px solid var(--glass-border);border-radius:var(--radius);padding:2rem;width:100%;max-width:440px;margin:1rem;position:relative;max-height:90vh;overflow-y:auto;">
    <button onclick="closeAuthModal()" style="position:absolute;top:1rem;right:1rem;background:none;border:none;color:var(--text-muted);font-size:1.4rem;cursor:pointer;">✕</button>

    <!-- Tabs -->
    <div style="display:flex;gap:.5rem;margin-bottom:1.5rem;background:rgba(255,255,255,.05);border-radius:var(--radius-sm);padding:.3rem;">
      <button id="tabLoginBtn" onclick="switchTab('login')" style="flex:1;padding:.5rem;border:none;border-radius:6px;cursor:pointer;font-weight:600;font-size:.9rem;background:var(--primary);color:#fff;transition:.2s;">Login</button>
      <button id="tabRegisterBtn" onclick="switchTab('register')" style="flex:1;padding:.5rem;border:none;border-radius:6px;cursor:pointer;font-weight:600;font-size:.9rem;background:transparent;color:var(--text-muted);transition:.2s;">Register</button>
    </div>

    <!-- Login Form -->
    <div id="tabLogin">
      <h2 style="font-weight:800;margin-bottom:.3rem;">Welcome Back 👋</h2>
      <p style="color:var(--text-muted);font-size:.85rem;margin-bottom:1.2rem;">Login to complete your order</p>
      <div id="loginErr" style="display:none;background:rgba(239,68,68,.2);border:1px solid rgba(239,68,68,.3);border-radius:8px;padding:.6rem .9rem;margin-bottom:1rem;font-size:.85rem;color:#f87171;"></div>
      <div class="form-group"><label>Email</label><input type="email" id="loginEmail" class="form-control" placeholder="you@example.com"></div>
      <div class="form-group"><label>Password</label><input type="password" id="loginPass" class="form-control" placeholder="••••••••"></div>
      <button onclick="doLogin()" class="btn btn-primary w-100 btn-lg" id="loginBtn">Sign In & Order</button>
      <p style="text-align:center;color:var(--text-muted);font-size:.83rem;margin-top:1rem;">No account? <a href="#" onclick="switchTab('register')" style="color:var(--primary);">Register free</a></p>
    </div>

    <!-- Register Form -->
    <div id="tabRegister" style="display:none;">
      <h2 style="font-weight:800;margin-bottom:.3rem;">Create Account 🍽️</h2>
      <p style="color:var(--text-muted);font-size:.85rem;margin-bottom:1.2rem;">Quick sign-up to place your order</p>
      <div id="registerErr" style="display:none;background:rgba(239,68,68,.2);border:1px solid rgba(239,68,68,.3);border-radius:8px;padding:.6rem .9rem;margin-bottom:1rem;font-size:.85rem;color:#f87171;"></div>
      <div class="form-group"><label>Full Name</label><input type="text" id="regName" class="form-control" placeholder="John Doe"></div>
      <div class="form-group"><label>Email</label><input type="email" id="regEmail" class="form-control" placeholder="you@example.com"></div>
      <div class="form-group"><label>Password</label><input type="password" id="regPass" class="form-control" placeholder="Min 6 characters"></div>
      <button onclick="doRegister()" class="btn btn-primary w-100 btn-lg" id="registerBtn">Register & Order</button>
      <p style="text-align:center;color:var(--text-muted);font-size:.83rem;margin-top:1rem;">Already have an account? <a href="#" onclick="switchTab('login')" style="color:var(--primary);">Login</a></p>
    </div>
  </div>
</div>

<script>


// ── Loader ─────────────────────────────────────────────────


// ── Toast ──────────────────────────────────────────────────
function showToast(msg, type='success'){
  const c = document.getElementById('toastContainer');
  const t = document.createElement('div');
  t.className = 'toast toast-' + type;
  t.textContent = msg;
  c.appendChild(t);
  setTimeout(() => t.remove(), 4200);
}

// ── Auth Modal ─────────────────────────────────────────────
function openAuthModal(e){
  if(e) e.preventDefault();
  document.getElementById('authModal').style.display = 'flex';
  document.body.style.overflow = 'hidden';
}
function closeAuthModal(){
  document.getElementById('authModal').style.display = 'none';
  document.body.style.overflow = '';
}
function switchTab(tab){
  const isLogin = tab === 'login';
  document.getElementById('tabLogin').style.display    = isLogin ? 'block' : 'none';
  document.getElementById('tabRegister').style.display = isLogin ? 'none'  : 'block';
  document.getElementById('tabLoginBtn').style.background    = isLogin ? 'var(--primary)' : 'transparent';
  document.getElementById('tabLoginBtn').style.color         = isLogin ? '#fff' : 'var(--text-muted)';
  document.getElementById('tabRegisterBtn').style.background = isLogin ? 'transparent' : 'var(--primary)';
  document.getElementById('tabRegisterBtn').style.color      = isLogin ? 'var(--text-muted)' : '#fff';
}

// Called from menu.php after login/register succeeds
window._pendingOrderFn = null;

function doLogin(){
  const email = document.getElementById('loginEmail').value.trim();
  const pass  = document.getElementById('loginPass').value.trim();
  const errEl = document.getElementById('loginErr');
  const btn   = document.getElementById('loginBtn');
  if(!email || !pass){ errEl.textContent='Please fill in all fields.'; errEl.style.display='block'; return; }
  btn.disabled=true; btn.textContent='Signing in...';
  errEl.style.display='none';
  fetch('<?= URLROOT ?>/users/ajaxLogin',{
    method:'POST', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({email, password: pass})
  })
  .then(r=>r.json())
  .then(res=>{
    if(res.success){
      closeAuthModal();
      showToast('Logged in as '+res.name+'!','success');
      updateHeader(res.name);
      window._sessionConfirmed = true; // allow placeOrder to proceed
      // Re-trigger pending order if any
      if(typeof window._pendingOrderFn === 'function') window._pendingOrderFn();
    } else {
      errEl.textContent = res.msg || 'Invalid credentials.';
      errEl.style.display='block';
      btn.disabled=false; btn.textContent='Sign In & Order';
    }
  })
  .catch(()=>{ errEl.textContent='Network error.'; errEl.style.display='block'; btn.disabled=false; btn.textContent='Sign In & Order'; });
}

function doRegister(){
  const name  = document.getElementById('regName').value.trim();
  const email = document.getElementById('regEmail').value.trim();
  const pass  = document.getElementById('regPass').value.trim();
  const errEl = document.getElementById('registerErr');
  const btn   = document.getElementById('registerBtn');
  if(!name||!email||!pass){ errEl.textContent='Please fill in all fields.'; errEl.style.display='block'; return; }
  if(pass.length < 6){ errEl.textContent='Password must be at least 6 characters.'; errEl.style.display='block'; return; }
  btn.disabled=true; btn.textContent='Creating account...';
  errEl.style.display='none';
  fetch('<?= URLROOT ?>/users/ajaxRegister',{
    method:'POST', headers:{'Content-Type':'application/json'},
    body: JSON.stringify({name, email, password: pass})
  })
  .then(r=>r.json())
  .then(res=>{
    if(res.success){
      closeAuthModal();
      showToast('Account created! Welcome '+res.name,'success');
      updateHeader(res.name);
      window._sessionConfirmed = true; // allow placeOrder to proceed
      if(typeof window._pendingOrderFn === 'function') window._pendingOrderFn();
    } else {
      errEl.textContent = res.msg || 'Registration failed.';
      errEl.style.display='block';
      btn.disabled=false; btn.textContent='Register & Order';
    }
  })
  .catch(()=>{ errEl.textContent='Network error.'; errEl.style.display='block'; btn.disabled=false; btn.textContent='Register & Order'; });
}

function updateHeader(userName){
  const nav = document.getElementById('navLinks');
  if(!nav) return;
  nav.innerHTML = `
    <li><a href="<?= URLROOT ?>">Menu</a></li>
    <li><a href="<?= URLROOT ?>/orders/track">Track Order</a></li>
    <li><a href="<?= URLROOT ?>/users/history">My Orders</a></li>
    <li><span style="color:var(--text-muted);font-size:.85rem;">👋 ${userName}</span></li>
    <li><a href="<?= URLROOT ?>/users/logout" class="btn btn-outline btn-sm">Logout</a></li>
  `;
}

// Close modal on backdrop click
document.getElementById('authModal').addEventListener('click', function(e){
  if(e.target === this) closeAuthModal();
});
// Enter key submits active tab
document.addEventListener('keydown', e=>{
  if(e.key==='Enter' && document.getElementById('authModal').style.display==='flex'){
    const isLogin = document.getElementById('tabLogin').style.display !== 'none';
    isLogin ? doLogin() : doRegister();
  }
});
</script>
</body>
</html>
