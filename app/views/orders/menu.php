<?php require_once APPROOT . '/views/inc/header.php'; ?>
<div style="max-width:1400px;margin:0 auto;padding:2rem;position:relative;z-index:1;">

  <!-- Page Header + Cart Toggle -->
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem;">
    <div>
      <h1 style="font-size:2rem;font-weight:800;">Our Menu</h1>
      <p style="color:var(--text-muted);">Select items and build your perfect meal</p>
    </div>
    <button class="btn btn-primary" onclick="toggleCart()">🛒 Cart <span id="cartCount" style="background:white;color:var(--primary);border-radius:50%;width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:800;margin-left:.3rem;">0</span></button>
  </div>

  <!-- Category Filter -->
  <div style="display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:2rem;" id="catFilter">
    <button class="btn btn-primary btn-sm cat-btn active" data-cat="all">All</button>
    <?php foreach($data['categories'] as $cat): ?>
    <button class="btn btn-outline btn-sm cat-btn" data-cat="<?= $cat->id ?>"><?= htmlspecialchars($cat->name) ?></button>
    <?php endforeach; ?>
  </div>

  <!-- Menu Grid -->
  <div class="menu-grid" id="menuGrid">
    <?php foreach($data['items'] as $item): ?>
    <div class="food-card" data-cat="<?= $item->category_id ?>">
      <?php if($item->image_url): ?>
        <img src="<?= htmlspecialchars($item->image_url) ?>" alt="<?= htmlspecialchars($item->name) ?>" loading="lazy" onerror="this.src='<?= URLROOT ?>/img/food-placeholder.jpg'">
      <?php else: ?>
        <div style="height:200px;background:linear-gradient(135deg,rgba(249,115,22,.3),rgba(139,92,246,.3));display:flex;align-items:center;justify-content:center;font-size:4rem;">🍽️</div>
      <?php endif; ?>
      <span class="food-badge"><?= htmlspecialchars($item->category_name) ?></span>
      <div class="food-card-body">
        <div class="food-card-name"><?= htmlspecialchars($item->name) ?></div>
        <div class="food-card-desc"><?= htmlspecialchars($item->description) ?></div>
        <div class="food-card-footer">
          <div class="food-price">₹<?= number_format($item->price, 2) ?></div>
          <button class="btn btn-primary btn-sm" onclick="addToCart(<?= $item->id ?>,'<?= addslashes($item->name) ?>',<?= $item->price ?>,'<?= addslashes($item->image_url) ?>')">+ Add</button>
        </div>
      </div>
    </div>

    <?php endforeach; ?>
  </div>
</div>

<!-- Cart Sidebar -->
<div class="cart-sidebar" id="cartSidebar">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
    <h2 style="font-weight:800;">🛒 Your Cart</h2>
    <button onclick="toggleCart()" style="background:none;border:none;color:var(--text);font-size:1.5rem;cursor:pointer;">✕</button>
  </div>

  <div class="form-group">
    <label>Group Size (people)</label>
    <input type="number" id="groupSize" class="form-control" min="1" max="10" value="1">
  </div>

  <div class="cart-items" id="cartItems"><p style="color:var(--text-muted);text-align:center;margin-top:2rem;">Your cart is empty</p></div>

  <!-- Tip Selection -->
  <div id="tipSection" style="display:none;">
    <hr class="divider">
    <label style="font-size:.85rem;color:var(--text-muted);font-weight:500;">Add Tip</label>
    <div style="display:flex;gap:.4rem;flex-wrap:wrap;margin:.5rem 0;">
      <button class="btn btn-outline btn-sm tip-btn" data-tip="0">No Tip</button>
      <button class="btn btn-outline btn-sm tip-btn" data-tip="20">₹20</button>
      <button class="btn btn-outline btn-sm tip-btn" data-tip="50">₹50</button>
      <button class="btn btn-outline btn-sm tip-btn" data-tip="100">₹100</button>
    </div>
    <input type="number" id="customTip" class="form-control" placeholder="Custom tip amount (₹)" min="0" style="margin-bottom:.8rem;">
  </div>

  <!-- Payment Method -->
  <div id="paySection" style="display:none;">
    <label style="font-size:.85rem;color:var(--text-muted);font-weight:500;">Payment Method</label>
    <div style="display:flex;gap:.5rem;margin:.5rem 0 1rem;">
      <button class="btn btn-outline btn-sm pay-btn active" data-pay="cash">💵 Cash</button>
      <button class="btn btn-outline btn-sm pay-btn" data-pay="card">💳 Card</button>
      <button class="btn btn-outline btn-sm pay-btn" data-pay="online">📱 UPI</button>
    </div>
  </div>

  <!-- Summary -->
  <div id="cartSummary" style="display:none;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:1.25rem;margin-bottom:1.5rem;font-size:.95rem;">
    <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;"><span>Subtotal</span><span id="sumSubtotal" style="font-weight:600;">₹0</span></div>
    <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;color:var(--text-muted);"><span>Tax (5%)</span><span id="sumTax">₹0</span></div>
    <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;color:var(--text-muted);"><span>Service (10%)</span><span id="sumService">₹0</span></div>
    <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;color:var(--text-muted);"><span>Tip</span><span id="sumTip">₹0</span></div>
    <hr class="divider" style="margin:.75rem 0;">
    <div style="display:flex;justify-content:space-between;font-weight:800;font-size:1.25rem;color:var(--primary);"><span>Total</span><span id="sumTotal">₹0</span></div>
  </div>


  <button id="placeOrderBtn" class="btn btn-primary w-100" onclick="placeOrder()" style="display:none;">🍽️ Place Order</button>
</div>
<div id="cartOverlay" onclick="toggleCart()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:998;backdrop-filter:blur(4px);"></div>

<script>
let cart = {};
let selectedTip = 0;
let selectedPay = 'cash';

function toggleCart(){
  const s = document.getElementById('cartSidebar');
  const o = document.getElementById('cartOverlay');
  s.classList.toggle('open');
  o.style.display = s.classList.contains('open') ? 'block' : 'none';
}

function addToCart(id, name, price, img){
  if(cart[id]){ cart[id].qty++; }
  else { cart[id] = { id, name, price, img, qty:1 }; }
  renderCart();
  showToast(name + ' added to cart!', 'success');
  if(!document.getElementById('cartSidebar').classList.contains('open')) toggleCart();
}

function renderCart(){
  const container = document.getElementById('cartItems');
  const keys = Object.keys(cart);
  document.getElementById('cartCount').textContent = keys.reduce((a,k)=>a+cart[k].qty,0);
  if(!keys.length){
    container.innerHTML = '<p style="color:var(--text-muted);text-align:center;margin-top:2rem;">Your cart is empty</p>';
    ['tipSection','paySection','cartSummary','placeOrderBtn'].forEach(id=>document.getElementById(id).style.display='none');
    return;
  }
  let html = '';
  let subtotal = 0;
  keys.forEach(k=>{
    const it = cart[k];
    subtotal += it.price * it.qty;
    html += `<div class="cart-item">
      <img src="${it.img||'<?= URLROOT ?>/img/food-placeholder.jpg'}" alt="${it.name}" onerror="this.src='<?= URLROOT ?>/img/food-placeholder.jpg'">
      <div class="cart-item-info">
        <div class="cart-item-name">${it.name}</div>
        <div class="cart-item-price">₹${(it.price*it.qty).toFixed(2)}</div>
      </div>
      <div class="qty-controls">
        <button class="qty-btn" onclick="changeQty(${k},-1)">−</button>
        <span style="font-weight:700;">${it.qty}</span>
        <button class="qty-btn" onclick="changeQty(${k},1)">+</button>
      </div>
    </div>`;
  });
  container.innerHTML = html;
  updateSummary(subtotal);
  ['tipSection','paySection','cartSummary','placeOrderBtn'].forEach(id=>document.getElementById(id).style.display='block');
  document.getElementById('placeOrderBtn').style.display='block';
}

function changeQty(id, delta){
  cart[id].qty += delta;
  if(cart[id].qty <= 0) delete cart[id];
  renderCart();
}

function updateSummary(subtotal){
  const tip = selectedTip;
  const tax = subtotal * 0.05;
  const svc = subtotal * 0.10;
  const total = subtotal + tax + svc + tip;
  document.getElementById('sumSubtotal').textContent = '₹'+subtotal.toFixed(2);
  document.getElementById('sumTax').textContent = '₹'+tax.toFixed(2);
  document.getElementById('sumService').textContent = '₹'+svc.toFixed(2);
  document.getElementById('sumTip').textContent = '₹'+tip.toFixed(2);
  document.getElementById('sumTotal').textContent = '₹'+total.toFixed(2);
}

// Tip buttons
document.querySelectorAll('.tip-btn').forEach(btn=>{
  btn.addEventListener('click',()=>{
    document.querySelectorAll('.tip-btn').forEach(b=>b.classList.remove('btn-primary'));
    btn.classList.add('btn-primary');
    selectedTip = parseFloat(btn.dataset.tip);
    document.getElementById('customTip').value='';
    renderCart();
  });
});
document.getElementById('customTip').addEventListener('input',e=>{
  selectedTip = parseFloat(e.target.value)||0;
  document.querySelectorAll('.tip-btn').forEach(b=>b.classList.remove('btn-primary'));
  renderCart();
});

// Pay buttons
document.querySelectorAll('.pay-btn').forEach(btn=>{
  btn.addEventListener('click',()=>{
    document.querySelectorAll('.pay-btn').forEach(b=>b.classList.remove('btn-primary'));
    btn.classList.add('btn-primary');
    selectedPay = btn.dataset.pay;
  });
});

// Category filter
document.querySelectorAll('.cat-btn').forEach(btn=>{
  btn.addEventListener('click',()=>{
    document.querySelectorAll('.cat-btn').forEach(b=>{b.classList.remove('btn-primary','active');b.classList.add('btn-outline');});
    btn.classList.add('btn-primary','active'); btn.classList.remove('btn-outline');
    const cat = btn.dataset.cat;
    document.querySelectorAll('.food-card').forEach(card=>{
      card.style.display = (cat==='all'||card.dataset.cat==cat) ? 'block' : 'none';
    });
  });
});

// Whether user is currently logged in (set by PHP)
const IS_LOGGED_IN = <?= json_encode($data['logged_in'] ?? false) ?>;

function placeOrder(){
  const cartArr = Object.values(cart).map(i=>({id:i.id,quantity:i.qty}));
  if(!cartArr.length){ showToast('Cart is empty','danger'); return; }

  // ── If not logged in, show auth modal and retry after login ──
  if(!IS_LOGGED_IN && !window._sessionConfirmed){
    window._pendingOrderFn = placeOrder;
    openAuthModal();
    return;
  }

  const groupSize = parseInt(document.getElementById('groupSize').value)||1;
  const btn = document.getElementById('placeOrderBtn');
  btn.disabled=true; btn.textContent='Placing order...';

  fetch('<?= URLROOT ?>/orders/place',{
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify({cart:cartArr,group_size:groupSize,tip:selectedTip,payment_method:selectedPay})
  })
  .then(r=>r.json())
  .then(res=>{
    if(res.success){
      cart={};
      renderCart();
      toggleCart();
      showToast('Order placed! Table '+res.table+' | Waiter: '+res.waiter,'success');
      setTimeout(()=>window.location.href='<?= URLROOT ?>/orders/track',2000);
    } else {
      showToast(res.msg||'Error placing order','danger');
      btn.disabled=false; btn.textContent='🍽️ Place Order';
    }
  })
  .catch(()=>{ showToast('Network error','danger'); btn.disabled=false; btn.textContent='🍽️ Place Order'; });
}

</script>
<?php require_once APPROOT . '/views/inc/footer.php'; ?>
