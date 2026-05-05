<?php require_once APPROOT . '/views/inc/header.php'; ?>
<div class="auth-page">
  <div class="auth-box">
    <div class="card">
      <div class="auth-header">
        <div class="auth-logo">🍽️</div>
        <h1>Create Account</h1>
        <p>Join us for a premium dining experience</p>
      </div>
      <form action="<?= URLROOT ?>/users/register" method="POST">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="name" class="form-control" value="<?= $data['name'] ?>" placeholder="John Doe" required>
          <?php if($data['name_err']): ?><div class="invalid-feedback"><?= $data['name_err'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" class="form-control" value="<?= $data['email'] ?>" placeholder="you@example.com" required>
          <?php if($data['email_err']): ?><div class="invalid-feedback"><?= $data['email_err'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
          <label>Password</label>
          <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required>
          <?php if($data['pass_err']): ?><div class="invalid-feedback"><?= $data['pass_err'] ?></div><?php endif; ?>
        </div>
        <div class="form-group">
          <label>Confirm Password</label>
          <input type="password" name="confirm_password" class="form-control" placeholder="Repeat password" required>
          <?php if($data['confirm_err']): ?><div class="invalid-feedback"><?= $data['confirm_err'] ?></div><?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary w-100 btn-lg">Create Account</button>
      </form>
      <hr class="divider">
      <p class="text-center text-muted" style="font-size:.88rem;">Already have an account? <a href="<?= URLROOT ?>/users/login" style="color:var(--primary);">Login here</a></p>
    </div>
  </div>
</div>
<?php require_once APPROOT . '/views/inc/footer.php'; ?>
