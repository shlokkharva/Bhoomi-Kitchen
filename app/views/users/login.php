<?php require_once APPROOT . '/views/inc/header.php'; ?>
<div class="auth-page">
  <div class="auth-box">
    <div class="card">
      <div class="auth-header">
        <div class="auth-logo">👤</div>
        <h1>Welcome Back</h1>
        <p>Sign in to your customer account</p>
      </div>
      <?php flash('register_success'); ?>
      <?php if(!empty($data['email_err'])): ?><div class="toast toast-danger"><?= $data['email_err'] ?></div><?php endif; ?>
      <?php if(!empty($data['pass_err'])): ?><div class="toast toast-danger"><?= $data['pass_err'] ?></div><?php endif; ?>
      <form action="<?= URLROOT ?>/users/login" method="POST">
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" class="form-control" value="<?= $data['email'] ?>" placeholder="you@example.com" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-lg">Sign In</button>
      </form>
      <hr class="divider">
      <p class="text-center text-muted" style="font-size:.88rem;">Don't have an account? <a href="<?= URLROOT ?>/users/register" style="color:var(--primary);">Register here</a></p>
      <p class="text-center text-muted mt-1" style="font-size:.82rem;">Are you a waiter? <a href="<?= URLROOT ?>/waiters/login" style="color:var(--secondary);">Waiter login</a></p>
    </div>
  </div>
</div>
<?php require_once APPROOT . '/views/inc/footer.php'; ?>
