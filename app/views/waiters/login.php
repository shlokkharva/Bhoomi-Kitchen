<?php require_once APPROOT . '/views/inc/header.php'; ?>
<div class="auth-page">
  <div class="auth-box">
    <div class="card">
      <div class="auth-header">
        <div class="auth-logo">🧑‍🍳</div>
        <h1>Waiter Login</h1>
        <p>Access your waiter dashboard</p>
      </div>
      <?php flash('waiter_registered'); ?>
      <?php if(!empty($data['err'])): ?><div class="toast toast-danger" style="margin-bottom:1rem;"><?= $data['err'] ?></div><?php endif; ?>
      <form action="<?= URLROOT ?>/waiters/login" method="POST">
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" class="form-control" value="<?= $data['email'] ?>" placeholder="waiter@email.com" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-secondary w-100 btn-lg">Sign In</button>
      </form>
      <hr class="divider">
      <p class="text-center text-muted" style="font-size:.88rem;">New waiter? <a href="<?= URLROOT ?>/waiters/register" style="color:var(--secondary);">Apply here</a></p>
    </div>
  </div>
</div>
<?php require_once APPROOT . '/views/inc/footer.php'; ?>
